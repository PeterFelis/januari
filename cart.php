<?php
include_once __DIR__ . '/incs/sessie.php';
// file: cart.php
// winkelwagen, werkt samen met andere cart scripts
// 05-03-2025

$menu = "beheer";
$title = "Winkelwagen";

if (isset($_SESSION['klant_id'])) {
    include_once __DIR__ . '/incs/dbConnect.php';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Databaseverbinding mislukt: ' . $e->getMessage());
    }

    // Zorg voor een consistente volgorde (bijv. op id)
    $stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE klant_id = ? ORDER BY id ASC");
    $stmt->execute([$_SESSION['klant_id']]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

// Lees het configuratiebestand in (bijv. order_config.ini)
$configPath = __DIR__ . '/order_config.ini';
if (!file_exists($configPath)) {
    die("Configuratiebestand niet gevonden: " . $configPath);
}
$config = parse_ini_file($configPath, true);

// Haal BTW, verzendkosten en cadeaucheck-drempels op
$btwPercentage = isset($config['VAT']) ? floatval($config['VAT']) : 21;
$verzendkosten = isset($config['SHIPPING']) ? floatval($config['SHIPPING']) : 7.50;
$cadeauThresholds = isset($config['thresholds']) ? array_map('floatval', $config['thresholds']) : [];
ksort($cadeauThresholds, SORT_NUMERIC);

// Bereken (server-side) het totaalbedrag (excl. BTW) op basis van de winkelwagen-items
$totalExclBtw = 0;
foreach ($cart as $item) {
    $totalExclBtw += $item['totaal_prijs'];
}

// Server-side berekening voor de eerste weergave
$btwBedrag = ($totalExclBtw + $verzendkosten) * ($btwPercentage / 100);
$totaalInclBtw = $totalExclBtw + $verzendkosten + $btwBedrag;

// Cadeaucheck (initieel, alleen voor de eerste rendering)
$cadeauCheck = 0.0;
$nextThreshold = null;
foreach ($cadeauThresholds as $threshold => $voucher) {
    if ($totalExclBtw >= $threshold) {
        $cadeauCheck = floatval($voucher);
    } else {
        $nextThreshold = floatval($threshold);
        break;
    }
}

// Inclusies voor menu en algemene layout
include_once __DIR__ . '../incs/top.php';
?>

<style>
    /* Dashboard styling voor tabellen */
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        width: 100%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        text-align: left;
    }
    .styled-table tr {
        border-bottom: 1px solid #dddddd;
    }
    .styled-table tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }
    .styled-table tr:last-of-type {
        border-bottom: 2px solid var(--heellichtpaars);
    }

    /* Overige cart-specifieke styling */
    .page-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    .cart-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .order-overview {
        background: #fafafa;
        border: 1px solid #e0e0e0;
        padding: 1.5rem;
        border-radius: 8px;
    }
    .order-overview p {
        margin: 0.5rem 0;
    }
    .gift-check {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1.5rem;
    }
    .gift-check a {
        color: #0056b3;
        text-decoration: underline;
    }
    .cart-actions {
        margin-top: 1rem;
    }
    .cart-actions a {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(45deg, #007bff, #00aaff);
        color: #fff;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        transition: background 0.3s ease;
    }
    .cart-actions a:hover {
        background: linear-gradient(45deg, #0056b3, #0077cc);
    }
</style>

<body>
    <?php include_once __DIR__ . '../incs/menu.php'; ?>
    <main>
        <div class="page-container">
            <h2>Winkelwagen: <?php echo count($cart); ?> verschillende producten</h2>

            <?php if (empty($cart)): ?>
                <p>Je winkelwagen is leeg. <a href="/shop.php">Klik hier om naar de webshop te gaan.</a></p>
            <?php else: ?>
                <form method="post" action="/cart_update.php" id="cartForm">
                    <div class="cart-layout">
                        <!-- Linkerkolom: Tabel met producten -->
                        <div class="cart-table">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th>TypeNummer</th>
                                        <th>Aantal dozen</th>
                                        <th>Totaal stuks</th>
                                        <th>Prijs per stuk</th>
                                        <th>Totaal prijs</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $index => $item):
                                        // Bepaal op basis van aantal en aantal_per_doos
                                        $dozenAantal = ($item['aantal_per_doos'] > 0)
                                            ? intval($item['aantal'] / $item['aantal_per_doos'])
                                            : $item['aantal'];
                                        // Als gebruiker ingelogd is, voeg dan de record-id toe
                                        $recordId = isset($item['id']) ? $item['id'] : '';
                                    ?>
                                        <tr data-index="<?php echo $index; ?>" data-recordid="<?php echo $recordId; ?>"
                                            data-aantalperdoos="<?php echo htmlspecialchars($item['aantal_per_doos']); ?>"
                                            data-prijsstaffel="<?php echo htmlspecialchars($item['prijsstaffel']); ?>">
                                            <td><?php echo htmlspecialchars($item['TypeNummer']); ?></td>
                                            <td>
                                                <input type="number"
                                                    name="cart[<?php echo $index; ?>][dozen]"
                                                    value="<?php echo $dozenAantal; ?>"
                                                    min="1"
                                                    class="dozen-input"
                                                    style="width:4rem;">
                                            </td>
                                            <td class="total-stuks">
                                                <?php echo $dozenAantal * $item['aantal_per_doos']; ?>
                                            </td>
                                            <td class="price-per-piece">
                                                €<span><?php echo number_format($item['prijs_per_stuk'], 2, ',', '.'); ?></span>
                                            </td>
                                            <td class="total-price">
                                                €<span data-value="<?php echo $item['totaal_prijs']; ?>">
                                                    <?php echo number_format($item['totaal_prijs'], 2, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" onclick="removeItem(<?php echo $index; ?>, '<?php echo $recordId; ?>')">
                                                    Verwijderen
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div class="cart-actions">
                                <a href="/shop.php">Verder winkelen</a>
                            </div>
                        </div>

                        <!-- Rechterkolom: Orderoverzicht -->
                        <div class="order-overview">
                            <p><strong>Subtotaal (excl. BTW):</strong>
                                €<span id="subtotalValue"><?php echo number_format($totalExclBtw, 2, ',', '.'); ?></span>
                            </p>
                            <p><strong>Verzendkosten:</strong>
                                €<span id="shippingValue"><?php echo number_format($verzendkosten, 2, ',', '.'); ?></span>
                            </p>
                            <p><strong>Bedrag ex BTW:</strong>
                                €<span id="orderExclValue"><?php echo number_format($totalExclBtw + $verzendkosten, 2, ',', '.'); ?></span>
                            </p>
                            <p><strong>BTW (<?php echo $btwPercentage; ?>%):</strong>
                                €<span id="vatValue"><?php echo number_format($btwBedrag, 2, ',', '.'); ?></span>
                            </p>
                            <p><strong>Totaal incl. BTW:</strong>
                                €<span id="totalValue"><?php echo number_format($totaalInclBtw, 2, ',', '.'); ?></span>
                            </p>
                        </div>
                    </div>
                </form>

                <!-- Cadeaucheck-blok -->
                <div class="gift-check">
                    <p id="giftCheckMessage">
                        <?php if ($cadeauCheck > 0): ?>
                            Je hebt recht op een cadeaucheck van €<?php echo number_format($cadeauCheck, 2, ',', '.'); ?>.
                        <?php else: ?>
                            Nog geen cadeaucheck. Vanaf €<?php echo array_key_first($cadeauThresholds); ?> krijg je een cadeaucheck.
                        <?php endif; ?>
                    </p>
                    <p>
                        Lees meer over onze cadeauchecks op de
                        <a href="/webshopinfo.php">webshoppagina</a>.
                    </p>
                </div>

                <div class="cart-actions" style="margin-top:1rem;">
                    <a href="/order_review.php">Bestellen/Offerte maken</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Helper: formatteert een nummer met 2 decimalen
        function formatNumber(num) {
            return num.toFixed(2).replace('.', ',');
        }

        // Herbereken één rij en update de totalen
        function recalcRow(row) {
            var aantalPerDoos = parseInt(row.getAttribute('data-aantalperdoos'), 10);
            var prijsstaffel = row.getAttribute('data-prijsstaffel');
            var dozenInput = row.querySelector('.dozen-input');
            var dozenAantal = parseInt(dozenInput.value, 10) || 0;
            var totaalStuks = dozenAantal * aantalPerDoos;
            row.querySelector('.total-stuks').innerText = totaalStuks;

            // Parse de prijsstaffel
            var regels = prijsstaffel.split("\n");
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
            tiers.sort(function(a, b) { return a.min - b.min; });

            var geldigePrijs = null;
            for (var i = 0; i < tiers.length; i++) {
                if (i === tiers.length - 1 || totaalStuks < tiers[i + 1].min) {
                    if (totaalStuks >= tiers[i].min) {
                        geldigePrijs = tiers[i].prijs;
                    }
                    break;
                }
            }

            var pricePerPieceElem = row.querySelector('.price-per-piece span');
            var totalPriceElem = row.querySelector('.total-price span');

            if (geldigePrijs === null) {
                pricePerPieceElem.innerText = "N/A";
                totalPriceElem.innerText = "Te laag";
                totalPriceElem.setAttribute('data-value', "0");
            } else {
                var rowTotal = totaalStuks * geldigePrijs;
                pricePerPieceElem.innerText = formatNumber(geldigePrijs);
                totalPriceElem.innerText = formatNumber(rowTotal);
                totalPriceElem.setAttribute('data-value', rowTotal);
            }
        }

        // Herbereken het overzicht (subtotal, BTW, totaal, etc.)
        function recalcSummary() {
            var subtotal = 0;
            document.querySelectorAll('.total-price span').forEach(function(span) {
                var value = parseFloat(span.getAttribute('data-value'));
                if (!isNaN(value)) {
                    subtotal += value;
                }
            });
            var shippingCost = <?php echo $verzendkosten; ?>;
            var vatRate = <?php echo $btwPercentage; ?>;
            var orderExcl = subtotal + shippingCost;
            var vat = orderExcl * (vatRate / 100);
            var total = orderExcl + vat;

            document.getElementById('subtotalValue').innerText = formatNumber(subtotal);
            document.getElementById('shippingValue').innerText = formatNumber(shippingCost);
            document.getElementById('orderExclValue').innerText = formatNumber(orderExcl);
            document.getElementById('vatValue').innerText = formatNumber(vat);
            document.getElementById('totalValue').innerText = formatNumber(total);

            var orderForGift = subtotal;
            var giftThresholds = <?php echo json_encode($cadeauThresholds); ?>;
            var keys = Object.keys(giftThresholds).map(Number).sort(function(a, b) { return a - b; });
            var giftAmount = 0;
            var nextThresholdValue = null;
            for (var i = 0; i < keys.length; i++) {
                if (orderForGift >= keys[i]) {
                    giftAmount = parseFloat(giftThresholds[keys[i]]);
                } else {
                    nextThresholdValue = keys[i];
                    break;
                }
            }
            var giftMessage = "";
            if (giftAmount > 0) {
                giftMessage = "Je hebt recht op een cadeaucheck van €" + formatNumber(giftAmount) + ".";
                if (nextThresholdValue !== null) {
                    var needed = nextThresholdValue - orderForGift;
                    giftMessage += "<br>Nog €" + formatNumber(needed) + " om een check van €" + formatNumber(giftThresholds[nextThresholdValue]) + " te krijgen!";
                }
            } else {
                if (nextThresholdValue !== null) {
                    var needed = nextThresholdValue - orderForGift;
                    giftMessage = "Nog €" + formatNumber(needed) + " om een cadeaucheck van €" + formatNumber(giftThresholds[nextThresholdValue]) + " te krijgen!";
                } else {
                    giftMessage = "Je hebt recht op de hoogste cadeaucheck van €" + formatNumber(giftAmount) + ".";
                }
            }
            document.getElementById("giftCheckMessage").innerHTML = giftMessage;
        }

        // Update functie voor een rij; stuurt recordId mee als beschikbaar
        function updateCartItem(index, dozenValue, recordId) {
            var formData = new FormData();
            formData.append('update', '1');
            formData.append('cart[' + index + '][dozen]', dozenValue);
            if (recordId) {
                formData.append('cart[' + index + '][recordId]', recordId);
            }
            fetch('/cart_update.php', { method: 'POST', body: formData })
                .then(function(response) { return response.text(); })
                .then(function(text) { console.log('Cart item ' + index + ' bijgewerkt (server).'); })
                .catch(function(error) { console.error('Fout bij update:', error); });
        }

        // Remove functie; stuurt recordId mee als beschikbaar
        function removeItem(index, recordId) {
            var formData = new FormData();
            formData.append('remove', index);
            if (recordId) {
                formData.append('recordId', recordId);
            }
            fetch('/cart_update.php', { method: 'POST', body: formData })
                .then(function(response) { return response.text(); })
                .then(function(text) { location.reload(); })
                .catch(function(error) { console.error('Fout bij verwijderen:', error); });
        }

        // Bind eventlisteners aan de dozen-inputs
        document.querySelectorAll('.dozen-input').forEach(function(input) {
            input.addEventListener('change', function() {
                var row = this.closest('tr');
                var index = row.getAttribute('data-index');
                var newDozen = parseInt(this.value, 10);
                var recordId = row.getAttribute('data-recordid');
                recalcRow(row);
                recalcSummary();
                updateCartItem(index, newDozen, recordId);
            });
        });

        window.addEventListener('load', function() {
            document.querySelectorAll('.dozen-input').forEach(function(input) {
                var row = input.closest('tr');
                recalcRow(row);
            });
            recalcSummary();
        });
    </script>

    <?php include_once __DIR__ . '../incs/bottom.php'; ?>
</body>
</html>
