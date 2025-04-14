<?php
include_once __DIR__ . '/incs/sessie.php';
include_once __DIR__ . '/incs/dbConnect.php';
include_once __DIR__ . '/incs/artikelkop.php';

// Functie om de prijs per stuk te berekenen
function calculatePricePerPiece($prijsstaffel, $aantal_per_doos, $totaalStuks) {
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

$isLoggedIn = isset($_SESSION['klant_id']);

$productType = $_POST['productType'] ?? '';
$aantal = intval($_POST['aantal'] ?? 0);
$typeNummer = $_POST['TypeNummer'] ?? '';

if ($aantal <= 0 || empty($typeNummer)) {
    die("Ongeldig aantal of ontbrekend productnummer.");
}

// Haal de productgegevens op
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

if ($isLoggedIn) {
    // Controleer of het item al in de shopping_cart staat
    $stmt = $pdo->prepare("SELECT id, aantal, totaal_prijs FROM shopping_cart WHERE klant_id = ? AND TypeNummer = ?");
    $stmt->execute([$_SESSION['klant_id'], $typeNummer]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existing) {
        $newAantal = $existing['aantal'] + $aantal;
        $newTotaalPrijs = $existing['totaal_prijs'] + $totaal_prijs;
        $updateStmt = $pdo->prepare("UPDATE shopping_cart SET aantal = ?, totaal_prijs = ? WHERE id = ?");
        $updateStmt->execute([$newAantal, $newTotaalPrijs, $existing['id']]);
    } else {
        $insertStmt = $pdo->prepare("INSERT INTO shopping_cart (klant_id, TypeNummer, productType, aantal, prijs_per_stuk, totaal_prijs, prijsstaffel, aantal_per_doos) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->execute([$_SESSION['klant_id'], $typeNummer, $productType, $aantal, $calculatedPrijsPerStuk, $totaal_prijs, $prijsstaffel, $aantalPerDoos]);
    }
} else {
    // Werk de sessie-winkelwagen bij
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['TypeNummer'] === $typeNummer) {
            $item['aantal'] += $aantal;
            $item['totaal_prijs'] += $totaal_prijs;
            $found = true;
            break;
        }
    }
    unset($item);
    if (!$found) {
        $_SESSION['cart'][] = [
            'TypeNummer'    => $typeNummer,
            'productType'   => $productType,
            'aantal'        => $aantal,
            'prijs_per_stuk'=> $calculatedPrijsPerStuk,
            'totaal_prijs'  => $totaal_prijs,
            'prijsstaffel'  => $prijsstaffel,
            'aantal_per_doos'=> $aantalPerDoos
        ];
    }
}

header("Location: /cart.php");
exit();
?>

