<?php
// klantEdit.php

// Zorg dat de verbindingsvariabelen beschikbaar zijn
include_once __DIR__ . '/dbConnect.php';

// Maak de PDO-verbinding aan
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<p>Databaseverbinding mislukt: " . $e->getMessage() . "</p>";
    exit;
}

// Check of klant_id in de sessie staat
if (!isset($_SESSION['klant_id'])) {
    echo "<p>Geen klantgegevens gevonden.</p>";
    return;
}

// Haal de klantgegevens op uit de database
try {
    $stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ?");
    $stmt->execute([$_SESSION['klant_id']]);
    $klant = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$klant) {
        echo "<p>Geen klantgegevens gevonden.</p>";
        return;
    }
} catch (Exception $e) {
    echo "<p>Er is een fout opgetreden: " . $e->getMessage() . "</p>";
    return;
}
?>

<h2>Mijn Gegevens Bewerken</h2>
<form action="updateKlant.php" method="POST">
    <!-- Verberg de klant-ID zodat updateKlant.php weet welke klant aangepast moet worden -->
    <input type="hidden" name="klant_id" value="<?php echo htmlspecialchars($klant['id']); ?>">

    <label for="klant_naam">Klantnaam:</label>
    <input type="text" id="klant_naam" name="klant_naam" value="<?php echo htmlspecialchars($klant['naam']); ?>" required>

    <label for="straat">Straat:</label>
    <input type="text" id="straat" name="straat" value="<?php echo htmlspecialchars($klant['straat']); ?>" required>

    <label for="nummer">Nummer:</label>
    <input type="text" id="nummer" name="nummer" value="<?php echo htmlspecialchars($klant['nummer']); ?>" required>

    <label for="postcode">Postcode:</label>
    <input type="text" id="postcode" name="postcode" value="<?php echo htmlspecialchars($klant['postcode']); ?>" required>

    <label for="plaats">Plaats:</label>
    <input type="text" id="plaats" name="plaats" value="<?php echo htmlspecialchars($klant['plaats']); ?>" required>

    <label for="extra_veld">Extra veld:</label>
    <textarea id="extra_veld" name="extra_veld"><?php echo htmlspecialchars($klant['extra_veld']); ?></textarea>

    <button type="submit">Gegevens bijwerken</button>
</form>