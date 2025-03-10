<?php
// file: order_process.php
include_once __DIR__ . '/incs/sessie.php';


include_once __DIR__ . '/incs/dbConnect.php';
include_once __DIR__ . '/incs/artikelkop.php';


// Definieer de functie om de prijs per stuk te berekenen
function calculatePricePerPiece($prijsstaffel, $aantal_per_doos, $totaalStuks)
{
    $tiers = [];
    $lines = explode("\n", $prijsstaffel);
    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line)) {
            $parts = preg_split('/\s+/', $line);
            if (count($parts) >= 2) {
                $minAantal = intval($parts[0]);
                $prijs = floatval(str_replace(',', '.', $parts[1]));
                $tiers[] = ['min' => $minAantal, 'prijs' => $prijs];
            }
        }
    }
    usort($tiers, function ($a, $b) {
        return $a['min'] - $b['min'];
    });
    $geldigePrijs = null;
    for ($i = 0; $i < count($tiers); $i++) {
        if ($i === count($tiers) - 1 || $totaalStuks < $tiers[$i + 1]['min']) {
            if ($totaalStuks >= $tiers[$i]['min']) {
                $geldigePrijs = $tiers[$i]['prijs'];
            }
            break;
        }
    }
    return $geldigePrijs;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . $e->getMessage());
}

if (!isset($_SESSION['klant_id'])) {
    header("Location: /loginForm.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal gegevens uit het formulier
    $productType = $_POST['productType'] ?? '';
    $aantal = intval($_POST['aantal'] ?? 0);
    $typeNummer = $_POST['TypeNummer'] ?? '';

    if ($aantal <= 0 || empty($typeNummer)) {
        die("Ongeldig aantal of ontbrekend productnummer.");
    }

    // Haal de productgegevens op via het type-nummer
    $productData = getProductData($typeNummer, $pdo);
    if (!$productData) {
        die("Product data niet gevonden.");
    }

    $prijsstaffel = $productData['prijsstaffel'];
    $aantalPerDoos = $productData['aantal_per_doos'];
    $calculatedPrijsPerStuk = calculatePricePerPiece($prijsstaffel, $aantalPerDoos, $aantal);
    if ($calculatedPrijsPerStuk === null) {
        die("Bestelling te laag voor de prijsstaffel.");
    }

    $totaal_prijs = $aantal * $calculatedPrijsPerStuk;

    try {
        $pdo->beginTransaction();
        $klant_id = $_SESSION['klant_id'];
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("INSERT INTO orders (klant_id, contactpersoon_id, status, totaal_prijs) VALUES (?, ?, 'offerte', ?)");
        $stmt->execute([$klant_id, $user_id, $totaal_prijs]);
        $order_id = $pdo->lastInsertId();

        // Indien beschikbaar, gebruik het product ID uit de productgegevens; anders fallback op een mapping
        $product_id = $productData['id'] ?? ($productType === 'main' ? 1 : 2);

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, aantal, prijs_per_stuk) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $aantal, $calculatedPrijsPerStuk]);

        $pdo->commit();
        header("Location: /order_overview.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Fout bij het plaatsen van de bestelling: " . $e->getMessage());
    }
} else {
    die("Ongeldige aanvraag.");
}
