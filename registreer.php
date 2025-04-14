<?php
// registreer.php
include_once __DIR__ . '/incs/sessie.php';
include_once __DIR__ . '/incs/dbConnect.php';

// PHPMailer classes includen
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Klantinformatie ophalen en ontsmetten
    $naam = htmlspecialchars($_POST['naam']);
    $straat = htmlspecialchars($_POST['straat']);
    $nummer = htmlspecialchars($_POST['nummer']);
    $postcode = htmlspecialchars($_POST['postcode']);
    $plaats = htmlspecialchars($_POST['plaats']);
    $extra_veld = htmlspecialchars($_POST['extra_veld']);
    $algemeen_telefoonnummer = htmlspecialchars($_POST['algemeen_telefoonnummer']);
    $algemene_email = htmlspecialchars($_POST['algemene_email']);
    $website = htmlspecialchars($_POST['website']); // voorheen 'url'
    $factuur_email = htmlspecialchars($_POST['factuur_email']);
    $factuur_extra_info = htmlspecialchars($_POST['factuur_extra_info']);
    // Optionele velden voor ander factuuradres
    $factuur_straat = htmlspecialchars($_POST['factuur_straat']);
    $factuur_nummer = htmlspecialchars($_POST['factuur_nummer']);
    $factuur_postcode = htmlspecialchars($_POST['factuur_postcode']);
    $factuur_plaats = htmlspecialchars($_POST['factuur_plaats']);
    // Optionele velden voor ander afleveradres
    $aflever_straat = htmlspecialchars($_POST['aflever_straat']);
    $aflever_nummer = htmlspecialchars($_POST['aflever_nummer']);
    $aflever_postcode = htmlspecialchars($_POST['aflever_postcode']);
    $aflever_plaats = htmlspecialchars($_POST['aflever_plaats']);

    // Gebruikersinformatie ophalen en ontsmetten
    $voornaam = htmlspecialchars($_POST['voornaam']);
    $achternaam = htmlspecialchars($_POST['achternaam']);
    $geslacht = htmlspecialchars($_POST['geslacht']);
    $email = htmlspecialchars($_POST['email']);
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_BCRYPT);

    // Genereer een bevestigingstoken
    $confirmation_token = bin2hex(random_bytes(16));

    // Start een transactie
    $pdo->beginTransaction();

    try {
        // Klant toevoegen
        $sql_klant = "INSERT INTO klanten 
            (naam, straat, nummer, postcode, plaats, extra_veld, algemeen_telefoonnummer, algemene_email, website, 
             factuur_email, factuur_extra_info, 
             factuur_straat, factuur_nummer, factuur_postcode, factuur_plaats, 
             aflever_straat, aflever_nummer, aflever_postcode, aflever_plaats)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $aflever_plaats
        ]);
        $klant_id = $pdo->lastInsertId();

        // Gebruiker toevoegen met bevestigingstoken en email_confirmed op 0
        $sql_user = "INSERT INTO users 
            (voornaam, achternaam, geslacht, email, wachtwoord, klant_id, email_confirmed, confirmation_token)
            VALUES (?, ?, ?, ?, ?, ?, 0, ?)";
        $stmt_user = $pdo->prepare($sql_user);
        $stmt_user->execute([
            $voornaam,
            $achternaam,
            $geslacht,
            $email,
            $wachtwoord,
            $klant_id,
            $confirmation_token
        ]);

        // Commit transactie
        $pdo->commit();

        // Bevestigingslink samenstellen (pas de domeinnaam aan indien nodig)
        $confirmLink = "http://fetum.nl/confirm.php?token=" . $confirmation_token;
        $subject = "Bevestig uw registratie";
        $body = "Beste " . $voornaam . ",\n\nBedankt voor uw registratie.\n\nKlik op de volgende link om uw e-mailadres te bevestigen:\n" . $confirmLink . "\n\nAls u zich niet heeft geregistreerd, negeer dan deze e-mail.";

        // Verstuur de bevestigingsmail met PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'mail225.hostingdiscounter.nl';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info@fetum.nl';
            $mail->Password   = 'rNqjQ2h4EC';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->isHTML(false);
            $mail->setFrom('info@fetum.nl', 'Fetum');
            $mail->addAddress($email, $voornaam . " " . $achternaam);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
        } catch (PHPMailer\PHPMailer\Exception $e) {
            // Mocht het verzenden van de mail falen, log dit dan
            error_log("Mail versturen mislukt: " . $mail->ErrorInfo);
        }

        header("Location: registratieSucces.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Er is een fout opgetreden: " . $e->getMessage());
    }
}
