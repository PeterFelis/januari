<?php
// file: prijs_component.php

function renderPriceComponent($prijsstaffel, $aantal_per_doos, $productType, $typeNummer, $prefix = 'prijs')
{
?>
    <div class="price-component" style="padding:1rem; margin:1rem 0;">
        <!-- Minimale bestelhoeveelheid tonen -->
        <p>Minimale bestelhoeveelheid: <?= htmlspecialchars($aantal_per_doos) ?></p>

        <!-- Weergave van de prijsstaffel -->
        <div id="<?= $prefix ?>_prijsstaffelList" style="margin-bottom:1rem;">
            <?php
            foreach (explode("\n", $prijsstaffel) as $regel) {
                $regel = trim($regel);
                if (!empty($regel)) {
                    $delen = preg_split('/\s+/', $regel);
                    if (count($delen) >= 2) {
                        $stuks = intval($delen[0]);
                        $prijs = $delen[1];
                        $dozen = $aantal_per_doos > 0 ? $stuks / $aantal_per_doos : 0;
                        $doosText = ($dozen == 1) ? "1 doos" : $dozen . " dozen";
                        echo htmlspecialchars($stuks) . " stuks (" . htmlspecialchars($doosText)
                            . ") €" . htmlspecialchars($prijs) . " per stuk<br>";
                    }
                }
            }
            ?>
        </div>

        <!-- Selectie voor het aantal dozen -->
        <div id="<?= $prefix ?>_dozenOrderSection" style="margin-bottom:1rem;">
            <label for="<?= $prefix ?>_dozenAantal">Aantal dozen:</label>
            <input type="number" id="<?= $prefix ?>_dozenAantal" name="<?= $prefix ?>_dozenAantal"
                   min="1" max="10" step="1" value="2">
        </div>

        <!-- Weergave van de berekende totaalprijs -->
        <div id="<?= $prefix ?>_prijsDisplay" style="margin-bottom:1rem; font-weight:bold;"></div>

        <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
            <!-- Bestelformulier voor reguliere gebruikers -->
            <form method="post" action="/cart_process.php">
                <!-- Producttype meesturen -->
                <input type="hidden" name="productType" value="<?= htmlspecialchars($productType) ?>">
                <!-- TypeNummer (of productnummer) meesturen -->
                <input type="hidden" name="TypeNummer" value="<?= htmlspecialchars($typeNummer) ?>">
                <!-- Verborgen veld dat het uiteindelijke aantal stuks bevat -->
                <input type="hidden" id="<?= $prefix ?>_hiddenAantal" name="aantal" value="">
                <button type="submit">Bestel</button>
            </form>
        <?php else: ?>
            <!-- Melding voor admin gebruikers -->
            <p style="color:red; font-weight:bold;">Als admin kunt u geen aankopen doen.</p>
        <?php endif; ?>

        <!-- Verborgen data voor de JavaScript -->
        <div id="<?= $prefix ?>_hiddenData"
             data-prijsstaffel="<?= htmlspecialchars($prijsstaffel); ?>"
             data-aantalperdoos="<?= htmlspecialchars($aantal_per_doos); ?>"
             style="display:none;"></div>
    </div>

    <script>
        (function() {
            function calculatePrice<?= $prefix ?>() {
                var aantalDozen = parseInt(document.getElementById('<?= $prefix ?>_dozenAantal').value, 10);
                var hiddenDataElem = document.getElementById('<?= $prefix ?>_hiddenData');
                var aantalPerDoos = parseInt(hiddenDataElem.getAttribute('data-aantalperdoos'), 10);
                var totaalStuks = aantalDozen * aantalPerDoos;
                var staffelRuwe = hiddenDataElem.getAttribute('data-prijsstaffel');
                var regels = staffelRuwe.split("\n");
                var tiers = [];
                regels.forEach(function(regel) {
                    regel = regel.trim();
                    if (regel.length > 0) {
                        var delen = regel.split(" ");
                        if (delen.length >= 2) {
                            var minAantal = parseInt(delen[0], 10);
                            var prijsPerStuk = parseFloat(delen[1].replace(',', '.'));
                            tiers.push({ min: minAantal, prijs: prijsPerStuk });
                        }
                    }
                });
                tiers.sort(function(a, b) {
                    return a.min - b.min;
                });
                var geldigePrijs = null;
                for (var i = 0; i < tiers.length; i++) {
                    if (i === tiers.length - 1 || totaalStuks < tiers[i + 1].min) {
                        if (totaalStuks >= tiers[i].min) {
                            geldigePrijs = tiers[i].prijs;
                        }
                        break;
                    }
                }
                var displayElem = document.getElementById('<?= $prefix ?>_prijsDisplay');
                if (geldigePrijs === null) {
                    displayElem.innerText = "Bestelling te laag voor prijsstaffel.";
                } else {
                    var totaalPrijs = totaalStuks * geldigePrijs;
                    displayElem.innerText = "Totaal: " + totaalStuks + " stuks à €" + geldigePrijs.toFixed(2) +
                        " = €" + totaalPrijs.toFixed(2);
                }
                document.getElementById('<?= $prefix ?>_hiddenAantal').value = totaalStuks;
            }

            document.getElementById('<?= $prefix ?>_dozenAantal')
                .addEventListener('change', calculatePrice<?= $prefix ?>);

            calculatePrice<?= $prefix ?>();
        })();
    </script>
<?php
}
?>
