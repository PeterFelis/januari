<?php
// file: prijs_component.php

function renderPriceComponent($prijsstaffel, $aantal_per_doos, $productType) {
    echo "<div class='price-component'>";
    // Weergeven van de prijsstaffel en minimale bestelhoeveelheid
    echo "<p>Prijsstaffel: " . htmlspecialchars($prijsstaffel) . "</p>";
    echo "<p>Minimale bestelhoeveelheid: " . htmlspecialchars($aantal_per_doos) . "</p>";
    // Bestelformulier geïntegreerd in de prijscomponent
    echo "<form method='post' action='/order_process.php'>";
    echo "<input type='hidden' name='productType' value='" . htmlspecialchars($productType) . "'>";
    // Indien nodig kun je ook het type nummer of product_id meesturen
    echo "<label for='aantal'>Aantal:</label>";
    echo "<input type='number' id='aantal' name='aantal' min='" . htmlspecialchars($aantal_per_doos) . "' step='" . htmlspecialchars($aantal_per_doos) . "' value='" . htmlspecialchars($aantal_per_doos) . "' required>";
    echo "<button type='submit'>Bestel</button>";
    echo "</form>";
    echo "</div>";

?>
    <div class="prijs-component">
        <!-- Weergave van de prijsstaffel -->
        <div id="<?= $prefix ?>_prijsstaffelList">
            <?php
            foreach (explode("\n", $prijsstaffel) as $regel) {
                $regel = trim($regel);
                if (!empty($regel)) {
                    $delen = preg_split('/\s+/', $regel);
                    if (count($delen) >= 2) {
                        $stuks = intval($delen[0]);
                        $prijs = $delen[1];
                        $dozen = $aantal_per_doos > 0 ? $stuks / $aantal_per_doos : 0;
                        $doosText = $dozen == 1 ? "1 doos" : $dozen . " dozen";
                        echo htmlspecialchars($stuks) . " stuks (" . htmlspecialchars($doosText) . ") €" . htmlspecialchars($prijs) . " per stuk<br>";
                    }
                }
            }
            ?>
        </div>
        <hr>
        <!-- Selectie voor het aantal dozen -->
        <div id="<?= $prefix ?>_dozenOrderSection">
            <label for="<?= $prefix ?>_dozenAantal">Aantal dozen:</label>
            <select id="<?= $prefix ?>_dozenAantal">
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <option value="<?= $i; ?>"><?= $i; ?></option>
                <?php endfor; ?>
            </select>
            <div id="<?= $prefix ?>_prijsDisplay" style="margin-top:1rem;"></div>
        </div>
        <!-- Verborgen data voor JavaScript -->
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
                            tiers.push({
                                min: minAantal,
                                prijs: prijsPerStuk
                            });
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
                    displayElem.innerText =
                        "Totaal: " + totaalStuks + " stuks à €" + geldigePrijs.toFixed(2) + " = €" + totaalPrijs.toFixed(2);
                }
            }
            document.getElementById('<?= $prefix ?>_dozenAantal').addEventListener('change', calculatePrice<?= $prefix ?>);
            calculatePrice<?= $prefix ?>();
        })();
    </script>
<?php
}
?>