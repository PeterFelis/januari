<?php
include_once __DIR__ . '/incs/sessie.php';
// file: forgot_password.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


include_once __DIR__ . '/incs/dbConnect.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Databaseverbinding mislukt: " . $e->getMessage());
        }

        // Controleer of de gebruiker bestaat
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($gebruiker) {
            // Genereer een uniek token en stel een vervaltijd in (bijv. 1 uur)
            $token = bin2hex(random_bytes(16));
            $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Sla token en vervaltijd op in de database
            $stmt = $pdo->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE email = ?");
            $stmt->execute([$token, $expires, $email]);

            // Maak de reset-link (pas je domein aan)
            $resetLink = "https://yourdomain.com/reset_password.php?token=" . $token;
            $onderwerp = "Wachtwoord reset aanvraag";
            $body = "Hallo,\n\nEr is een verzoek ingediend om je wachtwoord te resetten.\nKlik op de volgende link om je wachtwoord te resetten:\n$resetLink\n\nAls je dit verzoek niet hebt ingediend, kun je deze e-mail negeren.";

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = 'mail225.hostingdiscounter.nl';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'info@fetum.nl';
                $mail->Password   = 'rNqjQ2h4EC';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->isHTML(false);
                $mail->setFrom('info@fetum.nl', 'Fetum');
                $mail->addAddress($email);
                $mail->Subject = $onderwerp;
                $mail->Body    = $body;
                $mail->send();
                $melding = "Er is een e-mail verzonden met instructies om je wachtwoord te resetten.";
            } catch (Exception $e) {
                $melding = "Er is een fout opgetreden bij het verzenden van de e-mail: " . $mail->ErrorInfo;
            }
        } else {
            $melding = "Geen gebruiker gevonden met dit e-mailadres.";
        }
    } else {
        $melding = "Voer een geldig e-mailadres in.";
    }
}

?>
<style>
    .inlog {
        width: 50%;
        max-width: 400px;
        margin: 0 auto;
        padding-top: 10vh;
    }
</style>


<?php
$menu = 'normaal';
$title = "Paswoord vergeten? Geen probleem!";
include_once __DIR__ . '/incs/top.php';
?>

<body>

    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <div class='inlog'>
        <h2>Wachtwoord reset aanvragen</h2>
        <?php
        if (isset($melding)) {
            echo "<p>$melding</p>";
        }
        // Toon het formulier alleen als de bevestiging niet is gegeven
        if (!isset($melding) || $melding !== "Er is een e-mail verzonden met instructies om je wachtwoord te resetten.") {
        ?>
            <form method="post" action="">
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Verstuur reset password aanvraag naar uw mail</button>
            </form>
        <?php } ?>
    </div>
</body>

</html>