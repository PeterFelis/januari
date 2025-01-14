<?php
include_once __DIR__ . '/incs/dbConnect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Formuliergegevens ophalen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Klantinformatie
    $klant_naam = htmlspecialchars($_POST['klant_naam']);
    $adres = htmlspecialchars($_POST['adres']);
    $contact_email = htmlspecialchars($_POST['contact_email']);
    $telefoonnummer = htmlspecialchars($_POST['telefoonnummer']);

    // Gebruikersinformatie
    $naam = htmlspecialchars($_POST['naam']);
    $email = htmlspecialchars($_POST['email']);
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_BCRYPT);

    // Start transactie
    $pdo->beginTransaction();

    try {
        // Klant toevoegen
        $sql_klant = "INSERT INTO klanten (naam, adres, contact_email, telefoonnummer) VALUES (?, ?, ?, ?)";
        $stmt_klant = $pdo->prepare($sql_klant);
        $stmt_klant->execute([$klant_naam, $adres, $contact_email, $telefoonnummer]);
        $klant_id = $pdo->lastInsertId(); // Haal het klant-ID op

        // Gebruiker toevoegen
        $sql_user = "INSERT INTO users (naam, email, wachtwoord, klant_id) VALUES (?, ?, ?, ?)";
        $stmt_user = $pdo->prepare($sql_user);
        $stmt_user->execute([$naam, $email, $wachtwoord, $klant_id]);

        // Commit transactie
        $pdo->commit();
        header("Location: loginForm.php"); // Terug naar login als niet ingelogd
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Er is een fout opgetreden: " . $e->getMessage());
    }
}
