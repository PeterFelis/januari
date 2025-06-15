<?php
// file: order_afhandeling.php
include_once __DIR__ . '/incs/sessie.php';

if (!isset($_SESSION['klant_id'])) {
    header("Location: /loginForm.php");
    exit();
}

include_once __DIR__ . '/incs/dbConnect.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// 1. Haal klantgegevens op uit de 'klanten'-tabel
$stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ?");
$stmt->execute([$_SESSION['klant_id']]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$customer) {
    die("Klantgegevens niet gevonden.");
}

// 2. Haal winkelwagen-items op uit de 'shopping_cart'-tabel
$stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE klant_id = ? ORDER BY id ASC");
$stmt->execute([$_SESSION['klant_id']]);
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($cart)) {
    die("Winkelwagen is leeg.");
}

// 3. Bereken totaalbedragen
$totalExclBtw = 0;
foreach ($cart as $item) {
    $totalExclBtw += $item['totaal_prijs'];
}

// 4. Lees configuratie (BTW, verzendkosten, etc.)
$configPath = __DIR__ . '/order_config.ini';
if (!file_exists($configPath)) {
    die("Configuratiebestand niet gevonden: " . $configPath);
}
$config = parse_ini_file($configPath, true);
$btwPercentage = isset($config['VAT']) ? floatval($config['VAT']) : 21;
$verzendkosten = isset($config['SHIPPING']) ? floatval($config['SHIPPING']) : 7.50;
$btwBedrag = ($totalExclBtw + $verzendkosten) * ($btwPercentage / 100);
$totalInclBtw = $totalExclBtw + $verzendkosten + $btwBedrag;

// 5. Bepaal datum (orderdatum)
$currentDate = date("Y-m-d");

// 6. Sla de bestelling op in de orders-tabel met status 'bestelling'
$klant_id = $_SESSION['klant_id'];
$contactpersoon_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; // besteller of 0
$status = "bestelling";
$stmtOrder = $pdo->prepare("INSERT INTO orders (klant_id, contactpersoon_id, status, totaal_prijs) VALUES (?, ?, ?, ?)");
$stmtOrder->execute([$klant_id, $contactpersoon_id, $status, $totalInclBtw]);
$order_id = $pdo->lastInsertId();

// 7. Voeg elk winkelwagen-item toe aan de order_items-tabel
foreach ($cart as $item) {
    // Verkrijg het product-ID via TypeNummer
    $stmtProd = $pdo->prepare("SELECT id FROM products WHERE TypeNummer = ?");
    $stmtProd->execute([$item['TypeNummer']]);
    $product = $stmtProd->fetch(PDO::FETCH_ASSOC);
    $product_id = $product ? $product['id'] : 0;
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, aantal, prijs_per_stuk) VALUES (?, ?, ?, ?)");
    $stmtItem->execute([$order_id, $product_id, $item['aantal'], $item['prijs_per_stuk']]);
}

// 8. Maak de winkelwagen leeg (verwijder alle items voor deze klant)
$stmtClear = $pdo->prepare("DELETE FROM shopping_cart WHERE klant_id = ?");
$stmtClear->execute([$klant_id]);

// --- PDF Generatie ---
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Bestelling <?php echo $order_id; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
        }

        h1,
        h2 {
            text-align: center;
        }

        .details,
        .totals {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
        }
    </style>
</head>

<body>
    <h1>Bestelling</h1>
    <h2>Order #<?php echo $order_id; ?></h2>
    <p>Datum: <?php echo $currentDate; ?></p>
    <div class="details">
        <h3>Klantgegevens</h3>
        <p>
            Naam: <?php echo htmlspecialchars($customer['naam'] ?? ''); ?><br>
            Straat: <?php echo htmlspecialchars($customer['straat'] ?? ''); ?> <?php echo htmlspecialchars($customer['nummer'] ?? ''); ?><br>
            Postcode: <?php echo htmlspecialchars($customer['postcode'] ?? ''); ?><br>
            Plaats: <?php echo htmlspecialchars($customer['plaats'] ?? ''); ?><br>
            Email: <?php echo htmlspecialchars($customer['algemene_email'] ?? ''); ?>
        </p>
    </div>
    <div class="products">
        <h3>Producten</h3>
        <table>
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
                    // Bouw de absolute URL voor dit product. Pas de URL aan indien nodig.
                    $productUrl = "https://www.fetum.nl/artikelen/" . urlencode($item['TypeNummer']) . "/index.php";
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['TypeNummer']); ?></td>
                        <td><?php echo $dozenAantal; ?></td>
                        <td><?php echo $totaalStuks; ?></td>
                        <td>€<?php echo number_format($item['prijs_per_stuk'], 2, ',', '.'); ?></td>
                        <td>€<?php echo number_format($item['totaal_prijs'], 2, ',', '.'); ?></td>
                        <td><a href="<?php echo $productUrl; ?>">Bekijk product</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="totals">
        <h3>Overzicht</h3>
        <p>Subtotaal (excl. BTW): €<?php echo number_format($totalExclBtw, 2, ',', '.'); ?></p>
        <p>Verzendkosten: €<?php echo number_format($verzendkosten, 2, ',', '.'); ?></p>
        <p>BTW (<?php echo $btwPercentage; ?>%): €<?php echo number_format($btwBedrag, 2, ',', '.'); ?></p>
        <p><strong>Totaal (incl. BTW): €<?php echo number_format($totalInclBtw, 2, ',', '.'); ?></strong></p>
    </div>
</body>

</html>
<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdfOutput = $dompdf->output();

// --- E-mail Verzending ---
$adminEmail = "info@fetum.nl";
$customerEmail = $customer['algemene_email'] ?? '';
$subject = "Uw Bestelling bij Fetum - Order #$order_id";
$bodyCustomer = "Beste " . htmlspecialchars($customer['naam']) . ",\n\n"
    . "Hierbij bevestigen wij uw bestelling (Order #$order_id).\n\n"
    . "Met vriendelijke groet,\nFetum";
$bodyAdmin = "Er is een nieuwe bestelling geplaatst (Order #$order_id).\n\nTotaal: €" . number_format($totalInclBtw, 2, ',', '.');

$mailCustomer = new PHPMailer(true);
try {

    $mailCustomer->isSMTP();
    $mailCustomer->Host = 'mail225.hostingdiscounter.nl';
    $mailCustomer->SMTPAuth = true;
    $mailCustomer->Username = 'info@fetum.nl';
    $mailCustomer->Password = 'kbQena8rpn';
    $mailCustomer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailCustomer->Port = 587;
    $mailCustomer->isHTML(false);
    $mailCustomer->setFrom('info@fetum.nl', 'Fetum');
    $mailCustomer->addAddress($customerEmail);
    $mailCustomer->Subject = $subject;
    $mailCustomer->Body = $bodyCustomer;
    $mailCustomer->addStringAttachment($pdfOutput, "bestelling_$order_id.pdf", 'base64', 'application/pdf');
    $mailCustomer->send();
} catch (Exception $e) {

    error_log("Fout bij versturen bestelling naar klant: " . $mailCustomer->ErrorInfo);
}

$mailAdmin = new PHPMailer(true);
try {
    $mailAdmin->isSMTP();
    $mailAdmin->Host = 'mail225.hostingdiscounter.nl';

    $mailAdmin->SMTPAuth = true;
    $mailAdmin->Username = 'info@fetum.nl';
    $mailAdmin->Password = 'kbQena8rpn';
    $mailAdmin->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailAdmin->Port = 587;
    $mailAdmin->isHTML(false);
    $mailAdmin->setFrom('info@fetum.nl', 'Fetum');
    $mailAdmin->addAddress($adminEmail);
    $mailAdmin->Subject = "Nieuwe Bestelling ontvangen - Order #$order_id";
    $mailAdmin->Body = $bodyAdmin;
    $mailAdmin->addStringAttachment($pdfOutput, "bestelling_$order_id.pdf", 'base64', 'application/pdf');
    $mailAdmin->send();
} catch (Exception $e) {
    error_log("Fout bij versturen bestelling naar admin: " . $mailAdmin->ErrorInfo);
}

$_SESSION['flash_message'] = "De bestelling is succesvol verwerkt! U ontvangt een e-mail met uw bestelling.";
header("Location: dashboard.php");
exit();
