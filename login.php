<?php
// file: login.php
include_once __DIR__ . '/incs/sessie.php';

// Databaseverbinding
include_once __DIR__ . '/incs/dbConnect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Controleer of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $wachtwoord = $_POST['wachtwoord'];

    // Controleer of de gebruiker bestaat
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker) {
        // Controleer het wachtwoord
        if (password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            // Sessievariabelen instellen
            $_SESSION['user_id'] = $gebruiker['id'];
            $_SESSION['voornaam'] = $gebruiker['voornaam'];
            $_SESSION['achternaam'] = $gebruiker['achternaam'];
            $_SESSION['geslacht'] = $gebruiker['geslacht'];
            $_SESSION['role'] = $gebruiker['rol']; // Rol wordt nu opgeslagen als 'role'
            
            // Indien de gebruiker een klant is, slaan we ook de klant_id op
            if (isset($gebruiker['klant_id'])) {
                $_SESSION['klant_id'] = $gebruiker['klant_id'];
            }

            // Combineer voor weergave
            $_SESSION['user_name'] = $gebruiker['voornaam'] . " " . $gebruiker['achternaam'];

            echo "Inloggen succesvol! Welkom, " . $_SESSION['user_name'];
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
?>
