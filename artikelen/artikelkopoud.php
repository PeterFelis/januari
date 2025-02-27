<?php
//filenaam: artikelkop.php

include dirname(__DIR__, 1) . "/incs/top.php";
include dirname(__DIR__, 1) . "/incs/dbConnect.php";

// Database query om producttype op te halen
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Haal het producttype op aan de hand van de productnaam
    $stmt = $pdo->prepare("SELECT TypeNummer, USP, omschrijving, prijsstaffel,aantal_per_doos FROM products WHERE TypeNummer = ?");
    $stmt->execute([$TypeNummer]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $productType = $product ? $product['TypeNummer'] : 'Onbekend type';
    $USP = $product ? $product['USP'] : 'Onbekende USP';
    $omschrijving = $product ? $product['omschrijving'] : 'Onbekende omschrijving';
    $prijsstaffel = $product ? $product['prijsstaffel'] : 'Onbekende prijsstaffel';
    $aantal_per_doos = $product ? $product['aantal_per_doos'] : 0;
} catch (PDOException $e) {
    $productType = 'Fout bij ophalen producttype';
}
?>
<link rel="stylesheet" href="../prod.css">