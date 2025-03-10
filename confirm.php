<?php
include_once __DIR__ . '/incs/sessie.php';
// confirm.php
// dit is de bevestigings pagina van een nieuwe klant
// hier wordt door het klikken op de link in de mail de email bevestigd

include_once __DIR__ . '/incs/dbConnect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Zoek naar de gebruiker met het opgegeven token die nog niet bevestigd is
    $sql = "SELECT id FROM users WHERE confirmation_token = ? AND email_confirmed = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update de gebruiker: zet email_confirmed op 1 en maak de token leeg
        $sql_update = "UPDATE users SET email_confirmed = 1, confirmation_token = NULL WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$user['id']]);
        header("Location: loginForm.php?msg=confirmed");
        exit();
        echo "Ongeldige of verlopen bevestigingslink.";
    }
} else {
    echo "Geen bevestigingstoken opgegeven.";
}
