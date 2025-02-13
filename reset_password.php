<?php
session_start();
// file: forgot_password.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


include_once __DIR__ . '/incs/dbConnect.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

if (!isset($_GET['token'])) {
    die("Geen geldig token gevonden.");
}

$token = $_GET['token'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE password_reset_token = ?");
$stmt->execute([$token]);
$gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$gebruiker || strtotime($gebruiker['password_reset_expires']) < time()) {
    die("Deze reset link is ongeldig of verlopen.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nieuwWachtwoord = $_POST['new_password'] ?? '';
    $bevestigWachtwoord = $_POST['confirm_password'] ?? '';

    if ($nieuwWachtwoord === $bevestigWachtwoord && !empty($nieuwWachtwoord)) {
        $hashedPassword = password_hash($nieuwWachtwoord, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET wachtwoord = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hashedPassword, $gebruiker['id']]);
        $melding = "Je wachtwoord is succesvol bijgewerkt!";
    } else {
        $melding = "De wachtwoorden komen niet overeen of zijn leeg.";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtwoord reset</title>
</head>
<body>
    <h2>Reset je wachtwoord</h2>
    <?php if(isset($melding)) echo "<p>$melding</p>"; ?>
    <form method="post" action="">
        <label for="new_password">Nieuw wachtwoord:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>
        <label for="confirm_password">Bevestig nieuw wachtwoord:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <button type="submit">Reset wachtwoord</button>
    </form>
</body>
</html>
