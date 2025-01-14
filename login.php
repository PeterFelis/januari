<?php
session_start(); // Start een sessie


// Databaseverbinding
include_once __DIR__ . '/incs/dbConnect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Controleer of formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $wachtwoord = $_POST['wachtwoord'];

    // Controleer of gebruiker bestaat
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker) {
        // Controleer wachtwoord
        if (password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            // Sessie variabelen instellen
            $_SESSION['user_id'] = $gebruiker['id'];
            $_SESSION['user_name'] = $gebruiker['naam'];
            $_SESSION['user_role'] = $gebruiker['rol'];

            echo "Inloggen succesvol! Welkom, " . $gebruiker['naam'];
            // Redirect naar dashboard of homepage
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Ongeldig wachtwoord.";
        }
    } else {
        echo "Gebruiker niet gevonden.";
    }
}
