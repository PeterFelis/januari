<?php
// klantenLijst.php
// Zorg dat de databaseverbinding beschikbaar is (variabelen zoals $host, $dbname, $user, $password)
include_once __DIR__ . '/dbConnect.php';

// Maak de PDO-verbinding aan
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<p>Databaseverbinding mislukt: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>Klant Overzicht</h2>";

try {
    // Haal alle klanten op (pas de query aan op basis van jouw tabellenstructuur)
    $stmt = $pdo->query("SELECT id, naam FROM klanten ORDER BY naam ASC");
    $klanten = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($klanten) {
        echo "<ul>";
        foreach ($klanten as $klant) {
            // Link naar een bewerkpagina voor de geselecteerde klant
            echo "<li><a href='editKlant.php?klant_id=" . $klant['id'] . "'>" . htmlspecialchars($klant['naam']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Geen klanten gevonden.</p>";
    }
} catch (Exception $e) {
    echo "<p>Er is een fout opgetreden: " . $e->getMessage() . "</p>";
}
?>
