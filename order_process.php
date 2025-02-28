<?php
session_start();
include_once __DIR__ . '/incs/dbConnect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . $e->getMessage());
}

// Controleer of de klant ingelogd is
if (!isset($_SESSION['klant_id'])) {
    header("Location: /login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal de gegevens uit het formulier
    $productType = $_POST['productType'] ?? '';
    $aantal = intval($_POST['aantal'] ?? 0);

    if ($aantal <= 0) {
        die("Ongeldig aantal.");
    }

    // Voorbeeld: stel de prijs per stuk vast op basis van productType.
    // In een productieomgeving haal je dit uit de productdata.
    if ($productType === 'main') {
        $prijs_per_stuk = 50.00;
    } elseif ($productType === 'variant') {
        $prijs_per_stuk = 40.00;
    } else {
        $prijs_per_stuk = 50.00;
    }

    $totaal_prijs = $aantal * $prijs_per_stuk;

    try {
        // Begin transactie
        $pdo->beginTransaction();

        // Maak een nieuwe order aan met status 'offerte'
        $klant_id = $_SESSION['klant_id'];
        $user_id = $_SESSION['user_id']; // Nu gebruiken we de ingelogde user's ID

        $stmt = $pdo->prepare("INSERT INTO orders (klant_id, contactpersoon_id, status, totaal_prijs) VALUES (?, ?, 'offerte', ?)");
        $stmt->execute([$klant_id, $user_id, $totaal_prijs]);
        $order_id = $pdo->lastInsertId();

        // Voeg een order item toe. Hier kun je bijvoorbeeld ook een specifiek product_id meesturen.
        // Voor dit voorbeeld hanteren we: product_id = 1 voor 'main', 2 voor 'variant'
        if ($productType === 'main') {
            $product_id = 1;
        } elseif ($productType === 'variant') {
            $product_id = 2;
        } else {
            $product_id = 1;
        }

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, aantal, prijs_per_stuk) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $aantal, $prijs_per_stuk]);

        // Commit de transactie
        $pdo->commit();

        // Na succesvolle verwerking, stuur door naar het order overzicht
        header("Location: /order_overview.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Fout bij het plaatsen van de bestelling: " . $e->getMessage());
    }
} else {
    die("Ongeldige aanvraag.");
}
