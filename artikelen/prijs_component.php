<?php
//file: prijs_component.php

// Verwachte variabelen: $prijsstaffel (string) en $aantal_per_doos (int)
// Optioneel: controleer of de variabelen bestaan
if (!isset($prijsstaffel) || !isset($aantal_per_doos)) {
    echo "Prijscomponent niet beschikbaar. Variabelen ontbreken.";
    return;
}
?>
<div class="prijs-component">
    <!-- Overzichtelijke weergave van de prijsstaffel -->
    <div id="prijsstaffelList">
        <?php
        foreach (explode("\n", $prijsstaffel) as $regel) {
            $regel = trim($regel);
            if (!empty($regel)) {
                // Splits de regel in aantal stuks en prijs
                $delen = preg_split('/\s+/', $regel);
                if (count($delen) >= 2) {
                    $stuks = intval($delen[0]);
                    $prijs = $delen[1];
                    // Bereken aantal dozen (we gaan ervan uit dat $stuks een veelvoud is van $aantal_per_doos)
                    $dozen = $aantal_per_doos > 0 ? $stuks / $aantal_per_doos : 0;
                    $doosText = $dozen == 1 ? "1 doos" : $dozen . " dozen";
                    echo htmlspecialchars($stuks) . " stuks (" . htmlspecialchars($doosText) . ") €" . htmlspecialchars($prijs) . " per stuk<br>";
                }
            }
        }
        ?>
    </div>
    <hr>
    <!-- Invoer voor dozen en dynamische prijsberekening -->
    <div id="dozenOrderSection">
        <label for="dozenAantal">Aantal dozen:</label>
        <select id="dozenAantal">
            <?php for ($i = 1; $i <= 8; $i++): ?>
                <option value="<?= $i; ?>"><?= $i; ?></option>
            <?php endfor; ?>
        </select>
        <div id="prijsDisplay" style="margin-top:1rem;"></div>
    </div>
    <!-- Verborgen data voor JavaScript -->
    <div id="hiddenData"
        data-prijsstaffel="<?= htmlspecialchars($prijsstaffel); ?>"
        data-aantalperdoos="<?= htmlspecialchars($aantal_per_doos); ?>"
        style="display:none;"></div>
</div>

<script>
    // Zorg dat de script-code alleen voor dit component geldt.
    // Let op: als je van plan bent om dit component meerdere keren op dezelfde pagina te gebruiken, dan is het handig
    // om unieke id's of een andere selectie-methode te gebruiken.
    (function() {
        function calculatePrice() {
            var aantalDozen = parseInt(document.getElementById('dozenAantal').value, 10);
            var hiddenDataElem = document.getElementById('hiddenData');
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

            var displayElem = document.getElementById('prijsDisplay');
            if (geldigePrijs === null) {
                displayElem.innerText = "Bestelling te laag voor prijsstaffel.";
            } else {
                var totaalPrijs = totaalStuks * geldigePrijs;
                displayElem.innerText =
                    "Totaal: " + totaalStuks + " stuks à €" + geldigePrijs.toFixed(2) + " = €" + totaalPrijs.toFixed(2);
            }
        }
        document.getElementById('dozenAantal').addEventListener('change', calculatePrice);
        calculatePrice();
    })();
</script>