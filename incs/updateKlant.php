<?php
// updateKlant.php
session_start();
include_once __DIR__ . '/incs/dbConnect.php';

// Maak de PDO-verbinding aan
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Zorg dat klant_id aanwezig is (via hidden field)
    if (!isset($_POST['klant_id']) || empty($_POST['klant_id'])) {
        die("Klant ID ontbreekt.");
    }
    $klant_id = $_POST['klant_id'];

    // Haal en ontsmet de klantgegevens op
    $naam                = htmlspecialchars($_POST['naam']);
    $straat              = htmlspecialchars($_POST['straat']);
    $nummer              = htmlspecialchars($_POST['nummer']);
    $postcode            = htmlspecialchars($_POST['postcode']);
    $plaats              = htmlspecialchars($_POST['plaats']);
    $extra_veld          = htmlspecialchars($_POST['extra_veld']);
    $algemeen_telefoonnummer = htmlspecialchars($_POST['algemeen_telefoonnummer']);
    $algemene_email      = htmlspecialchars($_POST['algemene_email']);
    $website             = htmlspecialchars($_POST['website']);
    $factuur_email       = htmlspecialchars($_POST['factuur_email']);
    $factuur_extra_info  = htmlspecialchars($_POST['factuur_extra_info']);
    $factuur_straat      = htmlspecialchars($_POST['factuur_straat']);
    $factuur_nummer      = htmlspecialchars($_POST['factuur_nummer']);
    $factuur_postcode    = htmlspecialchars($_POST['factuur_postcode']);
    $factuur_plaats      = htmlspecialchars($_POST['factuur_plaats']);
    $aflever_straat      = htmlspecialchars($_POST['aflever_straat']);
    $aflever_nummer      = htmlspecialchars($_POST['aflever_nummer']);
    $aflever_postcode    = htmlspecialchars($_POST['aflever_postcode']);
    $aflever_plaats      = htmlspecialchars($_POST['aflever_plaats']);

    // Haal en ontsmet de gebruikersgegevens op
    $voornaam    = htmlspecialchars($_POST['voornaam']);
    $achternaam  = htmlspecialchars($_POST['achternaam']);
    $geslacht    = htmlspecialchars($_POST['geslacht']);
    $email       = htmlspecialchars($_POST['email']);

    // Controleer of er een nieuw wachtwoord is ingevoerd (in editmodus kan dit veld leeg blijven)
    $wachtwoord = null;
    if (!empty($_POST['wachtwoord'])) {
        if ($_POST['wachtwoord'] !== $_POST['wachtwoord_confirm']) {
            die("Wachtwoorden komen niet overeen.");
        }
        $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_BCRYPT);
    }

    // Begin een transactie
    $pdo->beginTransaction();
    try {
        // Update de klantgegevens in de tabel 'klanten'
        $sql_klant = "UPDATE klanten SET 
            naam = ?,
            straat = ?,
            nummer = ?,
            postcode = ?,
            plaats = ?,
            extra_veld = ?,
            algemeen_telefoonnummer = ?,
            algemene_email = ?,
            website = ?,
            factuur_email = ?,
            factuur_extra_info = ?,
            factuur_straat = ?,
            factuur_nummer = ?,
            factuur_postcode = ?,
            factuur_plaats = ?,
            aflever_straat = ?,
            aflever_nummer = ?,
            aflever_postcode = ?,
            aflever_plaats = ?
            WHERE id = ?";
        $stmt_klant = $pdo->prepare($sql_klant);
        $stmt_klant->execute([
            $naam,
            $straat,
            $nummer,
            $postcode,
            $plaats,
            $extra_veld,
            $algemeen_telefoonnummer,
            $algemene_email,
            $website,
            $factuur_email,
            $factuur_extra_info,
            $factuur_straat,
            $factuur_nummer,
            $factuur_postcode,
            $factuur_plaats,
            $aflever_straat,
            $aflever_nummer,
            $aflever_postcode,
            $aflever_plaats,
            $klant_id
        ]);

        // Update de gekoppelde gebruikersgegevens in de tabel 'users'
        if ($wachtwoord) {
            $sql_user = "UPDATE users SET 
                voornaam = ?,
                achternaam = ?,
                geslacht = ?,
                email = ?,
                wachtwoord = ?
                WHERE klant_id = ?";
            $stmt_user = $pdo->prepare($sql_user);
            $stmt_user->execute([
                $voornaam,
                $achternaam,
                $geslacht,
                $email,
                $wachtwoord,
                $klant_id
            ]);
        } else {
            $sql_user = "UPDATE users SET 
                voornaam = ?,
                achternaam = ?,
                geslacht = ?,
                email = ?
                WHERE klant_id = ?";
            $stmt_user = $pdo->prepare($sql_user);
            $stmt_user->execute([
                $voornaam,
                $achternaam,
                $geslacht,
                $email,
                $klant_id
            ]);
        }

        // Commit de transactie
        $pdo->commit();

        // Na een succesvolle update, doorsturen naar het dashboard
        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Er is een fout opgetreden: " . $e->getMessage());
    }
} else {
    die("Ongeldig verzoek.");
}
?>
