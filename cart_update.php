<?php
include_once __DIR__ . '/incs/sessie.php';
//file: cart_update.php
include_once __DIR__ . '/incs/dbConnect.php';

if (!isset($_SESSION['klant_id'])) {
    header("Location: /loginForm.php");
    exit();
}


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Verwijder-functionaliteit ---
    if (isset($_POST['remove'])) {
        // Als er een recordId wordt meegegeven, gaan we ervan uit dat dit ingelogde gebruiker betreft.
        if (isset($_POST['recordId']) && !empty($_POST['recordId'])) {
            $recordId = $_POST['recordId'];
            $stmt = $pdo->prepare("DELETE FROM shopping_cart WHERE id = ? AND klant_id = ?");
            $stmt->execute([$recordId, $_SESSION['klant_id']]);
        } else {
            // Mocht er geen recordId zijn, dan werkt het voor de sessiewinkelwagen (gast)
            $index = $_POST['remove'];
            if (isset($_SESSION['cart'][$index])) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
        }
    }

    // --- Update-functionaliteit ---
    if (isset($_POST['update']) && isset($_POST['cart'])) {
        if (isset($_SESSION['klant_id'])) {  // ingelogde gebruiker => database
            foreach ($_POST['cart'] as $index => $data) {
                if (isset($data['recordId']) && !empty($data['recordId'])) {
                    $recordId = $data['recordId'];
                    $newDozen = intval($data['dozen']);
                    if ($newDozen < 1) {
                        $newDozen = 1;
                    }
                    // Haal het huidige item op uit de database voor deze recordId en klant
                    $stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE id = ? AND klant_id = ?");
                    $stmt->execute([$recordId, $_SESSION['klant_id']]);
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($item) {
                        $aantalPerDoos = $item['aantal_per_doos'];
                        $totalPieces = $newDozen * $aantalPerDoos;
                        $prijsstaffel = $item['prijsstaffel'];
                        $newPricePerPiece = calculatePricePerPiece($prijsstaffel, $aantalPerDoos, $totalPieces);
                        if ($newPricePerPiece === null) {
                            $newPricePerPiece = 0;
                        }
                        $newTotal = $totalPieces * $newPricePerPiece;
                        $stmtUpdate = $pdo->prepare("UPDATE shopping_cart SET aantal = ?, totaal_prijs = ? WHERE id = ? AND klant_id = ?");
                        $stmtUpdate->execute([$totalPieces, $newTotal, $recordId, $_SESSION['klant_id']]);
                    }
                }
            }
        } else {
            // Voor gasten: update de sessie-winkelwagen
            foreach ($_POST['cart'] as $index => $data) {
                if (isset($_SESSION['cart'][$index])) {
                    $newDozen = intval($data['dozen']);
                    if ($newDozen < 1) {
                        $newDozen = 1;
                    }
                    $aantalPerDoos = $_SESSION['cart'][$index]['aantal_per_doos'];
                    $totalPieces = $newDozen * $aantalPerDoos;
                    $prijsstaffel = $_SESSION['cart'][$index]['prijsstaffel'];
                    $newPricePerPiece = calculatePricePerPiece($prijsstaffel, $aantalPerDoos, $totalPieces);
                    if ($newPricePerPiece === null) {
                        $newPricePerPiece = 0;
                    }
                    $_SESSION['cart'][$index]['aantal'] = $totalPieces;
                    $_SESSION['cart'][$index]['prijs_per_stuk'] = $newPricePerPiece;
                    $_SESSION['cart'][$index]['totaal_prijs'] = $totalPieces * $newPricePerPiece;
                }
            }
        }
    }
}

header("Location: /cart.php");
exit();

/**
 * Bereken de geldige prijs per stuk op basis van de prijsstaffel en het totale aantal stuks.
 */
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
    foreach ($tiers as $tier) {
        if ($totaalStuks >= $tier['min']) {
            $geldigePrijs = $tier['prijs'];
        } else {
            break;
        }
    }
    return $geldigePrijs;
}
