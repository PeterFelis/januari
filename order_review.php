<?php
// file: order_review.php
include_once __DIR__ . '/incs/sessie.php';

if (!isset($_SESSION['klant_id'])) {
    header("Location: /loginForm.php");
    exit();
}

include_once __DIR__ . '/incs/dbConnect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Haal klantgegevens op uit de 'klanten'-tabel
$stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ?");
$stmt->execute([$_SESSION['klant_id']]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

// Haal bestellergegevens op uit de 'users'-tabel, indien beschikbaar
$besteller = null;
if (isset($_SESSION['user_id'])) {
    $stmt2 = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt2->execute([$_SESSION['user_id']]);
    $besteller = $stmt2->fetch(PDO::FETCH_ASSOC);
}

// Haal winkelwagen-items op (voor ingelogde gebruikers uit de database)
$stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE klant_id = ? ORDER BY id ASC");
$stmt->execute([$_SESSION['klant_id']]);
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart)) {
    header("Location: /cart.php");
    exit();
}

// Bereken totaalbedragen
$totalExclBtw = 0;
foreach ($cart as $item) {
    $totalExclBtw += $item['totaal_prijs'];
}

// Haal configuratie op (BTW, verzendkosten, etc.)
$configPath = __DIR__ . '/order_config.ini';
if (!file_exists($configPath)) {
    die("Configuratiebestand niet gevonden: " . $configPath);
}
$config = parse_ini_file($configPath, true);
$btwPercentage = isset($config['VAT']) ? floatval($config['VAT']) : 21;
$verzendkosten = isset($config['SHIPPING']) ? floatval($config['SHIPPING']) : 7.50;
$btwBedrag = ($totalExclBtw + $verzendkosten) * ($btwPercentage / 100);
$totalInclBtw = $totalExclBtw + $verzendkosten + $btwBedrag;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Overzicht</title>
    <style>
        /* Basis styling voor een A4-achtig overzicht */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 20mm auto;
            padding: 20mm;
            border: 1px solid #ccc;
            background: #fff;
            font-family: Arial, sans-serif;
        }

        h1,
        h2 {
            text-align: left;
            /* aangepaste uitlijning */
        }

        .section {
            margin-bottom: 20px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .products-table th,
        .products-table td {
            border: 1px solid #aaa;
            padding: 5px;
            text-align: left;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
        }

        .actions button {
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .notice {
            text-align: center;
            font-style: italic;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="page">
        <h1>Order Overzicht</h1>
        <p style="font-size: 18px;">(Offerte / Bestelling)</p>

        <div class="section">
            <h2>Klantgegevens</h2>
            <p>
                Naam: <?php echo htmlspecialchars($customer['naam'] ?? ''); ?><br>
                Straat: <?php echo htmlspecialchars($customer['straat'] ?? ''); ?> <?php echo htmlspecialchars($customer['nummer'] ?? ''); ?><br>
                Postcode: <?php echo htmlspecialchars($customer['postcode'] ?? ''); ?><br>
                Plaats: <?php echo htmlspecialchars($customer['plaats'] ?? ''); ?><br>
                Email: <?php echo htmlspecialchars($customer['algemene_email'] ?? ''); ?>
            </p>
        </div>

        <?php if ($besteller): ?>
            <div class="section">
                <h2>Besteller</h2>
                <p>
                    <?php echo htmlspecialchars($besteller['voornaam'] ?? ''); ?> <?php echo htmlspecialchars($besteller['achternaam'] ?? ''); ?><br>
                    Email: <?php echo htmlspecialchars($besteller['email'] ?? ''); ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="section">
            <h2>Producten</h2>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>TypeNummer</th>
                        <th>Aantal Dozen</th>
                        <th>Totaal Stuks</th>
                        <th>Prijs per stuk</th>
                        <th>Totaal Prijs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item):
                        $dozenAantal = ($item['aantal_per_doos'] > 0) ? intval($item['aantal'] / $item['aantal_per_doos']) : $item['aantal'];
                        $totaalStuks = $dozenAantal * $item['aantal_per_doos'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['TypeNummer']); ?></td>
                            <td><?php echo $dozenAantal; ?></td>
                            <td><?php echo $totaalStuks; ?></td>
                            <td>€<?php echo number_format($item['prijs_per_stuk'], 2, ',', '.'); ?></td>
                            <td>€<?php echo number_format($item['totaal_prijs'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Overzicht</h2>
            <p>Subtotaal (excl. BTW): €<?php echo number_format($totalExclBtw, 2, ',', '.'); ?></p>
            <p>Verzendkosten: €<?php echo number_format($verzendkosten, 2, ',', '.'); ?></p>
            <p>BTW (<?php echo $btwPercentage; ?>%): €<?php echo number_format($btwBedrag, 2, ',', '.'); ?></p>
            <p><strong>Totaal (incl. BTW): €<?php echo number_format($totalInclBtw, 2, ',', '.'); ?></strong></p>
        </div>

        <!-- Extra melding over geldigheid -->
        <div class="section">
            <p><strong>Let op:</strong> Deze offerte is 7 dagen geldig.</p>
        </div>

        <!-- Actieknoppen -->
        <div class="actions">
            <form method="post" action="offer_process.php" style="display:inline;">
                <input type="hidden" name="action" value="offerte">
                <button type="submit">
                    Druk hier om dit als offerte per mail te ontvangen
                </button>
            </form>
            <form method="post" action="order_afhandeling.php" style="display:inline;">
                <input type="hidden" name="action" value="bestelling">
                <button type="submit">
                    Druk hier om te bestellen
                </button>
            </form>
        </div>

        <div class="notice">
            <p>Dit overzicht dient als orderbevestiging/offerte.</p>
        </div>
    </div>
</body>

</html>