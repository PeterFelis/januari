<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/incs/sessie.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$title = 'contact ';
$statusbalk = "Even bellen? 0174 769132";
$menu = 'normaal';
include_once __dir__ . '/incs/top.php';


// Verzenden van het contactformulier
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$melding = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Honeypot check – als dit veld is ingevuld, dan is het waarschijnlijk spam
    if (!empty($_POST['website'])) {
        exit; // Stop de verwerking
    }

    $form_start = intval($_POST['form_start_time'] ?? 0);
    if ($form_start === 0 || time() - $form_start < 4) {
        exit; // Te snel verzonden → waarschijnlijk bot
    }
    $naam = htmlspecialchars($_POST['naam'] ?? '');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';
    $bericht = htmlspecialchars($_POST['bericht'] ?? '');
    $ontvanger = "info@fetum.nl";

    if ($naam && $email && $bericht) {
        $onderwerp = "Nieuw contactformulier bericht van " . $naam;
        $body = "Naam: $naam\nEmail: $email\n\nBericht:\n$bericht";

        try {
            // Eerste e-mail: naar de beheerder
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'mail225.hostingdiscounter.nl';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info@fetum.nl';
            $mail->Password   = 'kbQena8rpn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->isHTML(false);

            // Zet de afzender en ontvanger
            $mail->setFrom('info@fetum.nl', 'Fetum');
            $mail->addAddress($ontvanger);
            $mail->addReplyTo($email, $naam);

            // Inhoud van de e-mail
            $mail->Subject = $onderwerp;
            $mail->Body    = $body;

            $mail->send();

            // Tweede e-mail: kopie naar de afzender
            $mailCopy = new PHPMailer(true);
            $mailCopy->isSMTP();
            $mailCopy->Host       = 'mail225.hostingdiscounter.nl';
            $mailCopy->SMTPAuth   = true;
            $mailCopy->Username   = 'info@fetum.nl';
            $mailCopy->Password   = 'kbQena8rpn';
            $mailCopy->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailCopy->Port       = 587;
            $mailCopy->isHTML(false);

            $mailCopy->setFrom('info@fetum.nl', 'Fetum');
            $mailCopy->addAddress($email, $naam);
            $mailCopy->Subject = "Kopie van uw bericht";
            $mailCopy->Body    = $body;

            $mailCopy->send();

            $melding = "<div class='snackbar success'>Uw bericht is succesvol verzonden.</div>";
            header("Location: contact.php?status=success");
            exit();
        } catch (Exception $e) {
            $melding = "<div class='snackbar error'>Er is een fout opgetreden bij het verzenden: {$mail->ErrorInfo}</div>";
        }
    } else {
        $melding = "<div class='snackbar error'>Vul alle velden correct in.</div>";
    }
}
?>

<style>
    .grid2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        max-width: 100%;
        margin: 0 auto 10vh auto;
        align-items: stretch;
    }

    .contact-info,
    .contact-form {
        background: transparent;
        padding: 20px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        max-width: 100%;
        font-size: 2rem;

    }

    .contact-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2px;
    }

    .grid2col .tr {
        text-align: right;
        padding-right: 10px;
    }

    .contact-form {
        background: #f8f8f8;
        display: flex;
    }

    .contact-form form {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        font-size: 1rem;
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .contact-form textarea {
        flex-grow: 1;
        resize: none;
    }

    .contact-form button {
        width: 100%;
        padding: 10px;
        background: #007BFF;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .contact-form button:hover {
        background: #0056b3;
    }

    .snackbar {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .snackbar.success {
        background: #28a745;
    }

    .snackbar.error {
        background: #dc3545;
    }

    .kaart {
        width: 100vw;
        height: 90vh;
        margin: 0 auto;
    }

    .social {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid var(--lichtpaars);
        border-radius: 4px;
        margin-bottom: 10vh;
    }

    .bol a {
        display: grid;
        grid-template-columns: 1fr 1fr;
        /* Indien gewenst kun je een vaste hoogte instellen */
        height: 150px;
        gap: 20px;
    }

    .bollogo {
        /* Zorgt ervoor dat de afbeelding de hele cel opvult */
        overflow: hidden;
        display: flex;
        justify-content: end;
        align-items: center;

    }

    .bollogo img {
        width: auto;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 8px;
    }

    .boltekst {
        /* Centreert de tekst horizontaal en verticaal */
        display: flex;
        justify-content: left;
        align-items: center;
        padding: 0 10px;
        /* optioneel: ruimte rondom de tekst */
    }

    .container section .text h2 {
        padding-left: 20px;
    }

    /* Zorg dat in de gekleurde sectie de flex-items even hoog worden */
    .full-sectioncolour .section {
        align-items: stretch;
    }

    /* Maak het contactformulier even hoog als de naastliggende afbeelding */
    .contact-form {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }


    .social-bol-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        padding: 3rem 2rem;
        border-radius: 12px;
        max-width: 1200px;
        margin: 4rem auto;
    }

    .socials {
        flex: 1;
        min-width: 280px;
    }

    .socials h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .icons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .icons img {
        width: 40px;
        height: 40px;
        padding: 6px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .icons a:hover img {
        transform: scale(1.1);
    }

    .trustpilot {
        margin-top: 1.5rem;
        font-size: 1rem;
    }

    .trustpilot img.tp-logo {
        height: 20px;
        margin-left: 0.5rem;
        vertical-align: middle;
    }

    .bol-wrapper {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .bol-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: #000;
    }

    .bol-link img {
        height: 60px;
        width: auto;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .bol-link span {
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .social-bol-wrapper {
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .bol-wrapper {
            justify-content: flex-start;
            margin-top: 2rem;
        }
    }
</style>

<body>

    <?php include_once __dir__ . '/incs/menu.php';
    include_once __dir__ . '/incs/statusbalk.php'; ?>



    <section class="bovenlicht">
        <div class="bovenlicht__wrapper">
            <div class="bovenlicht__text">
                <h1>Contact</h1>
                <h3>Altijd handig</h3>
            </div>
            <div class="bovenlicht__image" style="background-image: url('afbeeldingen/2710 alle vier geen achtergrond.png'); ">
                <!-- Inhoud, indien nodig -->
            </div>
        </div>
    </section>


    <?= $melding ?>

    <section class="full-sectiontransparent">
        <main
            <section class="section reverse">
            <div class="text">
                <h2>Contactgegevens</h2>
                <!-- Contactinformatie links -->
                <div class="contact-info">
                    <div class="tr">Naam</div>
                    <div>Fetum</div>
                    <div class="tr">Adres</div>
                    <div>Grote waard 36</div>
                    <div class="tr">Postcode</div>
                    <div>2675 BX</div>
                    <div class="tr">Plaats</div>
                    <div>Honselersdijk</div>
                    <div class="tr">&nbsp;</div>
                    <div></div>
                    <div class="tr">Telefoon</div>
                    <div><a href="tel:+31174769132">+31 (0) 174 769132</a></div>
                    <div class="tr">Mail</div>
                    <div><a href="mailto:info@fetum.nl">info@fetum.nl</a></div>
                    <div class="tr">&nbsp;</div>
                    <div></div>
                    <div class="tr">KVK</div>
                    <div>Den Haag 28045481</div>
                    <div class="tr">BTW nummer</div>
                    <div>801462.265.B01</div>
                    <div class="tr">Bank</div>
                    <div>NL78 KNAB 0724 8909 47</div>
                    <div class="tr">BIC</div>
                    <div>KNABNL2H</div>
                </div>


            </div>
            <div class="image">
                <img src="afbeeldingen/hp-136-zijaanzicht.png" alt="Illustratie">
            </div>
    </section>
    </main>
    </section>



    <!-- Sectie: Contactformulier met gekleurde achtergrond -->
    <section class="full-sectioncolour">
        <main>
            <section class="section first-section">
                <div class="image">
                    <img src="afbeeldingen/vvv.jpg" alt="Illustratie">
                </div>
                <div class="text">
                    <div class="contact-form">
                        <h3>Neem contact op</h3>
                        <form action="contact.php" method="post" id="contactForm">
                            <!-- Honeypot veld -->
                            <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
                            <input type="hidden" name="form_start_time" value="<?= time(); ?>">
                            <input type="text" name="naam" placeholder="Uw naam" required>
                            <input type="email" name="email" placeholder="Uw e-mailadres" required>
                            <textarea name="bericht" placeholder="Uw bericht" rows="4" required></textarea>
                            <button type="submit">Verstuur</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Als er een succesvolle snackbar aanwezig is, verberg deze na 2 seconden en reset het formulier
            var snackbarSuccess = document.querySelector('.snackbar.success');
            if (snackbarSuccess) {
                setTimeout(function() {
                    snackbarSuccess.style.display = 'none';
                    // Reset het formulier
                    var form = document.getElementById('contactForm');
                    if (form) {
                        form.reset();
                    }
                }, 4000);
            }
        });
    </script>


    <section class="full-sectiontransparent">
        <main>

            <section class="social-bol-wrapper">
                <div class="socials">
                    <h3>Volg ons</h3>
                    <div class="icons">
                        <a href="https://www.facebook.com/profile.php?id=100064868221019" target="_blank" aria-label="Facebook">
                            <img src="afbeeldingen/icons/facebook-new.png" alt="Facebook">
                        </a>
                        <a href="https://www.google.com/search?q=Fetum" target="_blank" aria-label="Google Business">
                            <img src="afbeeldingen/icons/google-logo.png" alt="Google">
                        </a>
                        <a href="https://www.linkedin.com/company/3049791/admin/dashboard/" target="_blank" aria-label="LinkedIn">
                            <img src="afbeeldingen/icons/linkedin.png" alt="LinkedIn">
                        </a>
                        <a href="https://www.instagram.com/fetum.nl/?hl=en" target="_blank" aria-label="Instagram">
                            <img src="afbeeldingen/icons/instagram-new--v1.png" alt="Instagram">
                        </a>
                    </div>
                    <div class="trustpilot">
                        <a href="https://nl.trustpilot.com/review/fetum.nl" target="_blank">
                            Beoordeel ons op <img src="https://cdn.trustpilot.net/brand-assets/4.0.0/logo-white.svg" alt="Trustpilot" class="tp-logo">
                        </a>
                    </div>
                </div>

                <div class="bol-wrapper">
                    <a href="https://www.bol.com/nl/nl/b/fetum/607014364/" target="_blank" class="bol-link">
                        <img src="afbeeldingen/bolklein.png" alt="Fetum bij Bol.com">
                        <span>Ook te koop bij bol</span>
                    </a>
                </div>
            </section>

        </main>

    </section>





    <!-- TrustBox script -->
    <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
    <!-- End TrustBox script -->



    <style>
        .social {
            text-align: center;
            margin-top: 20px;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            display: inline-block;
            margin: 0 10px;
        }

        .social-icons img {
            width: 32px;
            /* pas de grootte aan naar wens */
            height: auto;
        }
    </style>

    </section>


    <section class="kaart">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1032.551418345417!2d4.223417460607463!3d52.00988473968992!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5dac3b3643789%3A0x490ecab243450195!2sFetum!5e0!3m2!1sen!2snl!4v1739470699835!5m2!1sen!2snl" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>



</body>

</html>