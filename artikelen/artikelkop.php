<?php
// filenaam: artikelkop.php

// Laad de algemene header en database connectie instellingen
include dirname(__DIR__, 1) . "/incs/top.php";
include dirname(__DIR__, 1) . "/incs/dbConnect.php";

// Maak de databaseconnectie
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connectie mislukt: " . $e->getMessage());
}

/**
 * Haalt productgegevens op op basis van het typenummer.
 *
 * @param string $typenummer Het unieke productnummer.
 * @param PDO $pdo De PDO-databaseverbinding.
 * @return array|false Associatieve array met productdata of false als niet gevonden.
 */
function getProductData($typenummer, $pdo) {
    $stmt = $pdo->prepare("SELECT TypeNummer, USP, omschrijving, prijsstaffel, aantal_per_doos FROM products WHERE TypeNummer = ?");
    $stmt->execute([$typenummer]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<link rel="stylesheet" href="../prod.css">
