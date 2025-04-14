<?php
include_once __DIR__ . '/incs/sessie.php';

// Bepaal of we in edit-mode zitten
$edit = isset($_GET['edit']) && $_GET['edit'] == 1;

// Instellingen voor registratie of bewerken
$action = $edit ? "updateKlant.php" : "registreer.php";
$pageTitle = $edit ? "Bewerk uw gegevens" : "Welkom als nieuwe klant!";
$submitLabel = $edit ? "Bijwerken" : "Registreer";

// Stel standaard waarden voor klantgegevens in
$klantData = [
    "naam" => "",
    "straat" => "",
    "nummer" => "",
    "postcode" => "",
    "plaats" => "",
    "land" => "Nederland",
    "extra_veld" => "",
    "voornaam" => "",
    "achternaam" => "",
    "geslacht" => "",
    "email" => "",
    "algemeen_telefoonnummer" => "",
    "algemene_email" => "",
    "website" => "",
    "factuur_email" => "",
    "factuur_extra_info" => "",
    "factuur_straat" => "",
    "factuur_nummer" => "",
    "factuur_postcode" => "",
    "factuur_plaats" => "",
    "aflever_straat" => "",
    "aflever_nummer" => "",
    "aflever_postcode" => "",
    "aflever_plaats" => ""
];

// Als we in edit-mode zitten, vul dan de klantgegevens in vanuit de database
if ($edit) {
    if (isset($_GET['klant_id'])) {
        $klant_id = $_GET['klant_id'];
    } elseif (isset($_SESSION['klant_id'])) {
        $klant_id = $_SESSION['klant_id'];
    }
    if (isset($klant_id)) {
        include_once __DIR__ . '/incs/dbConnect.php';
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Databaseverbinding mislukt: " . $e->getMessage());
        }
        // Haal de klantgegevens op
        $stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ?");
        $stmt->execute([$klant_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $klantData = array_merge($klantData, $data);
        }
        // Haal de gebruikersgegevens op
        $stmtUser = $pdo->prepare("SELECT * FROM users WHERE klant_id = ?");
        $stmtUser->execute([$klant_id]);
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
        if ($userData) {
            $klantData['voornaam']   = $userData['voornaam'] ?? '';
            $klantData['achternaam'] = $userData['achternaam'] ?? '';
            $klantData['geslacht']   = $userData['geslacht'] ?? '';
            $klantData['email']      = $userData['email'] ?? '';
        }
    }
}

$menu = 'normaal';
$title = $pageTitle;
include_once __DIR__ . '/incs/top.php';
?>

<style>
    /* Basis styling voor het formulier */
    form.registration-form {
        max-width: 600px;
        margin: 20px auto;
        padding: 2rem;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    main {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
    }

    h3 {
        border-bottom: 2px dashed #008000;
        padding-bottom: 5px;
        color: #800080;
    }

    .instructions {
        text-align: center;
        font-size: 1em;
        margin-bottom: 20px;
    }

    .required {
        color: red;
    }

    .adres-regel,
    .adres-regel2 {
        display: flex;
        gap: 1rem;
        margin-bottom: 15px;
    }

    .adres-regel>div,
    .adres-regel2>div {
        flex: 1;
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .cancelBtn {
        background-color: red;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
    }

    .cancelBtn:hover {
        background-color: darkred;
    }

    .accordion-content {
        display: none;
        padding: 0 10px;
        margin-top: 10px;
        border-left: 2px solid #ccc;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .radio-group {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <p class="instructions">Velden gemarkeerd met <span class="required">*</span> zijn verplicht.</p>
        <form id="multiStepForm" class="registration-form" action="<?php echo $action; ?>" method="POST">
            <?php if ($edit && isset($klant_id)) { ?>
                <input type="hidden" name="klant_id" value="<?php echo htmlspecialchars($klant_id); ?>">
            <?php } ?>

            <?php if (!$edit) { ?>
                <!-- Stap 0: Uitleg over hoe wij werken (alleen bij registratie) -->
                <div class="form-step active" id="step-intro">
                    <h3>Hoe werken wij?</h3>
                    <p>Welkom als klant! Super dat je hier bent. <br><br>
                     We vragen je even om ons registratieformulier in te vullen, zodat we je bestellingen goed kunnen verwerken.<br><br>
                     Het formulier is simpel en stap voor stap uitgelegd – vul minimaal de velden met een rood sterretje (*) in. <br>
                     We gaan zorgvuldig met je gegevens om en behandelen alles vertrouwelijk. <br>
                     Heb je vragen of kom je iets tegen? Neem gerust contact op met ons. <br><br>
                     Na je registratie kun je meteen aan de slag. <br>
                    </P>
                    <div class="button-group">
                        <button type="button" class="cancelBtn" onclick="window.location.href='dashboard.php';">Cancel</button>
                        <button type="button" id="nextBtnIntro">Volgende</button>
                    </div>
                </div>
            <?php } ?>

            <!-- Stap: Noodzakelijke klant info -->
            <div class="form-step <?php echo $edit ? 'active' : ''; ?>" id="step-klantinfo">
                <h3>Noodzakelijke klant info</h3>
                <label for="naam">Klantnaam: <span class="required">*</span></label>
                <input type="text" id="naam" name="naam" required value="<?php echo htmlspecialchars($klantData['naam'] ?? ''); ?>">

                <div class="adres-regel">
                    <div>
                        <label for="postcode">Postcode: <span class="required">*</span></label>
                        <input type="text" id="postcode" name="postcode" required value="<?php echo htmlspecialchars($klantData['postcode'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="nummer">Nummer: <span class="required">*</span></label>
                        <input type="text" id="nummer" name="nummer" required value="<?php echo htmlspecialchars($klantData['nummer'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="land">Land: <span class="required">*</span></label>
                        <input type="text" id="land" name="land" required value="<?php echo htmlspecialchars($klantData['land'] ?? 'Nederland'); ?>">
                    </div>
                </div>

                <div class="adres-regel2">
                    <div>
                        <label for="straat">Straat: <span class="required">*</span></label>
                        <input type="text" id="straat" name="straat" required value="<?php echo htmlspecialchars($klantData['straat'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="plaats">Plaats: <span class="required">*</span></label>
                        <input type="text" id="plaats" name="plaats" required value="<?php echo htmlspecialchars($klantData['plaats'] ?? ''); ?>">
                    </div>
                </div>
                <label for="extra_veld">Extra veld:</label>
                <textarea id="extra_veld" name="extra_veld" placeholder="Als u hier iets invoert, dan wordt dit in de adressering gezet."><?php echo htmlspecialchars($klantData['extra_veld'] ?? ''); ?></textarea>
                <div class="button-group">
                    <button type="button" class="cancelBtn" onclick="window.location.href='dashboard.php';">Cancel</button>
                    <button type="button" id="nextBtnKlantinfo">Volgende</button>
                </div>
            </div>

            <!-- Stap: Besteller (u dus) -->
            <div class="form-step" id="step-besteller">
                <h3>Besteller (u dus)</h3>
                <label for="voornaam">Voornaam: <span class="required">*</span></label>
                <input type="text" id="voornaam" name="voornaam" required value="<?php echo htmlspecialchars($klantData['voornaam'] ?? ''); ?>">
                <label for="achternaam">Achternaam: <span class="required">*</span></label>
                <input type="text" id="achternaam" name="achternaam" required value="<?php echo htmlspecialchars($klantData['achternaam'] ?? ''); ?>">
                <p>Geslacht: <span class="required">*</span></p>
                <div class="radio-group">
                    <label><input type="radio" name="geslacht" value="M" required <?php echo (($klantData['geslacht'] ?? '') == 'M' ? 'checked' : ''); ?>> M</label>
                    <label><input type="radio" name="geslacht" value="V" required <?php echo (($klantData['geslacht'] ?? '') == 'V' ? 'checked' : ''); ?>> V</label>
                    <label><input type="radio" name="geslacht" value="X" required <?php echo (($klantData['geslacht'] ?? '') == 'X' ? 'checked' : ''); ?>> X</label>
                </div>
                <label for="email">E-mail: <span class="required">*</span></label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($klantData['email'] ?? ''); ?>">
                <label for="wachtwoord">Wachtwoord: <span class="required">*</span></label>
                <input type="password" id="wachtwoord" name="wachtwoord" <?php echo $edit ? '' : 'required'; ?>>
                <label for="wachtwoord_confirm">Herhaal wachtwoord: <span class="required">*</span></label>
                <input type="password" id="wachtwoord_confirm" name="wachtwoord_confirm" <?php echo $edit ? '' : 'required'; ?>>
                <div id="passwordError" style="display:none; color:red;">De wachtwoorden komen niet overeen!</div>
                <div class="button-group">
                    <button type="button" id="prevBtnBesteller">Vorige</button>
                    <button type="button" class="cancelBtn" onclick="window.location.href='dashboard.php';">Cancel</button>
                    <button type="button" id="nextBtnBesteller">Volgende</button>
                </div>
            </div>

            <!-- Stap: Eventuele aanvullende informatie -->
            <div class="form-step" id="step-aanvullende">
                <h3>Eventuele aanvullende informatie</h3>
                <label for="algemeen_telefoonnummer">Algemeen telefoonnummer:</label>
                <input type="text" id="algemeen_telefoonnummer" name="algemeen_telefoonnummer" value="<?php echo htmlspecialchars($klantData['algemeen_telefoonnummer'] ?? ''); ?>">
                <label for="algemene_email">Algemene email:</label>
                <input type="email" id="algemene_email" name="algemene_email" value="<?php echo htmlspecialchars($klantData['algemene_email'] ?? ''); ?>">
                <label for="website">Website:</label>
                <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($klantData['website'] ?? ''); ?>">
                <label for="factuur_email">Factuur email adres:</label>
                <input type="email" id="factuur_email" name="factuur_email" value="<?php echo htmlspecialchars($klantData['factuur_email'] ?? ''); ?>">
                <label for="factuur_extra_info">Extra info voor factuur:</label>
                <textarea id="factuur_extra_info" name="factuur_extra_info" placeholder="bijv. brinnummer"><?php echo htmlspecialchars($klantData['factuur_extra_info'] ?? ''); ?></textarea>
                <h4 id="toggleFactuur" style="cursor:pointer;">Optioneel: Ander factuuradres &#9660;</h4>
                <div id="factuurAccordion" class="accordion-content">
                    <div class="adres-regel">
                        <div>
                            <label for="factuur_straat">Straat:</label>
                            <input type="text" id="factuur_straat" name="factuur_straat" value="<?php echo htmlspecialchars($klantData['factuur_straat'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="factuur_nummer">Nummer:</label>
                            <input type="text" id="factuur_nummer" name="factuur_nummer" value="<?php echo htmlspecialchars($klantData['factuur_nummer'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="adres-regel2">
                        <div>
                            <label for="factuur_postcode">Postcode:</label>
                            <input type="text" id="factuur_postcode" name="factuur_postcode" value="<?php echo htmlspecialchars($klantData['factuur_postcode'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="factuur_plaats">Plaats:</label>
                            <input type="text" id="factuur_plaats" name="factuur_plaats" value="<?php echo htmlspecialchars($klantData['factuur_plaats'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <h4 id="toggleAflever" style="cursor:pointer;">Optioneel: Ander afleveradres &#9660;</h4>
                <div id="afleverAccordion" class="accordion-content">
                    <div class="adres-regel">
                        <div>
                            <label for="aflever_straat">Straat:</label>
                            <input type="text" id="aflever_straat" name="aflever_straat" value="<?php echo htmlspecialchars($klantData['aflever_straat'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="aflever_nummer">Nummer:</label>
                            <input type="text" id="aflever_nummer" name="aflever_nummer" value="<?php echo htmlspecialchars($klantData['aflever_nummer'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="adres-regel2">
                        <div>
                            <label for="aflever_postcode">Postcode:</label>
                            <input type="text" id="aflever_postcode" name="aflever_postcode" value="<?php echo htmlspecialchars($klantData['aflever_postcode'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="aflever_plaats">Plaats:</label>
                            <input type="text" id="aflever_plaats" name="aflever_plaats" value="<?php echo htmlspecialchars($klantData['aflever_plaats'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" id="prevBtnAanvullende">Vorige</button>
                    <button type="button" class="cancelBtn" onclick="window.location.href='dashboard.php';">Cancel</button>
                    <button type="submit"><?php echo $submitLabel; ?></button>
                </div>
            </div>
        </form>
    </main>
    <script>
        // Multi-step formulier navigatie
        let currentStep = 0;
        let steps = document.querySelectorAll('.form-step');
        const isEdit = <?php echo $edit ? 'true' : 'false'; ?>;

        function showStep(stepIndex) {
            steps.forEach((step, index) => {
                step.classList.toggle('active', index === stepIndex);
            });
        }

        if (!isEdit) {
            // Registratie modus: de stappen zijn:
            // 0: Intro, 1: Noodzakelijke klant info, 2: Besteller, 3: Aanvullende info.
            document.getElementById('nextBtnIntro').addEventListener('click', function() {
                currentStep = 1;
                showStep(currentStep);
            });
            document.getElementById('nextBtnKlantinfo').addEventListener('click', function() {
                currentStep = 2;
                showStep(currentStep);
            });
            document.getElementById('prevBtnBesteller').addEventListener('click', function() {
                currentStep = 1;
                showStep(currentStep);
            });
            document.getElementById('nextBtnBesteller').addEventListener('click', function() {
                // Controleer of de wachtwoorden overeenkomen
                let wachtwoord = document.getElementById('wachtwoord').value;
                let wachtwoordConfirm = document.getElementById('wachtwoord_confirm').value;
                if (wachtwoord !== wachtwoordConfirm) {
                    document.getElementById('passwordError').style.display = 'block';
                    return;
                } else {
                    document.getElementById('passwordError').style.display = 'none';
                }
                currentStep = 3;
                showStep(currentStep);
            });
            document.getElementById('prevBtnAanvullende').addEventListener('click', function() {
                currentStep = 2;
                showStep(currentStep);
            });
        } else {
            // Edit modus: de stappen zijn:
            // 0: Noodzakelijke klant info, 1: Besteller, 2: Aanvullende info.
            steps = document.querySelectorAll('.form-step');
            currentStep = 0;
            showStep(currentStep);
            document.getElementById('nextBtnKlantinfo').addEventListener('click', function() {
                currentStep = 1;
                showStep(currentStep);
            });
            document.getElementById('prevBtnBesteller').addEventListener('click', function() {
                currentStep = 0;
                showStep(currentStep);
            });
            document.getElementById('nextBtnBesteller').addEventListener('click', function() {
                let wachtwoord = document.getElementById('wachtwoord').value;
                let wachtwoordConfirm = document.getElementById('wachtwoord_confirm').value;
                if (wachtwoord !== wachtwoordConfirm) {
                    document.getElementById('passwordError').style.display = 'block';
                    return;
                } else {
                    document.getElementById('passwordError').style.display = 'none';
                }
                currentStep = 2;
                showStep(currentStep);
            });
            document.getElementById('prevBtnAanvullende').addEventListener('click', function() {
                currentStep = 1;
                showStep(currentStep);
            });
        }

        // Zorg dat de website-URL begint met http:// als dat nog niet zo is
        document.getElementById('multiStepForm').addEventListener('submit', function(event) {
            const websiteField = document.getElementById('website');
            let websiteValue = websiteField.value.trim();
            if (websiteValue && !/^https?:\/\//i.test(websiteValue)) {
                websiteField.value = 'http://' + websiteValue;
            }
        });

        // Functie om adresgegevens op te halen
        function fetchAddress() {
            let postcode = document.getElementById('postcode').value.trim();
            let nummer = document.getElementById('nummer').value.trim();
            if (!postcode || !nummer) return;
            // Verwijder spaties uit de postcode
            postcode = postcode.replace(/\s+/g, '');
            document.getElementById('postcode').value = postcode;
            const dutchRegex = /^\d{4}[A-Z]{2}$/i;
            if (dutchRegex.test(postcode)) {
                document.getElementById('land').value = 'Nederland';
                fetch('proxy.php?postcode=' + postcode + '&number=' + nummer)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Netwerkfout bij ophalen adresgegevens.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.street && data.city) {
                            document.getElementById('straat').value = data.street;
                            document.getElementById('plaats').value = data.city;
                        } else {
                            document.getElementById('straat').value = '';
                            document.getElementById('plaats').value = '';
                            alert("Geen adresgegevens gevonden voor deze Nederlandse postcode en nummer.");
                        }
                    })
                    .catch(error => {
                        console.error('Fout:', error);
                        alert('Er is een fout opgetreden bij het ophalen van het adres.');
                    });
            } else {
                document.getElementById('land').value = 'België';
            }
        }

        document.getElementById('postcode').addEventListener('blur', fetchAddress);
        document.getElementById('nummer').addEventListener('blur', fetchAddress);

        // Toggle voor optionele adressen
        document.getElementById('toggleFactuur').addEventListener('click', function() {
            let acc = document.getElementById('factuurAccordion');
            if (acc.style.display === 'block') {
                acc.style.display = 'none';
                this.innerHTML = 'Optioneel: Ander factuuradres &#9660;';
            } else {
                acc.style.display = 'block';
                this.innerHTML = 'Optioneel: Ander factuuradres &#9650;';
            }
        });
        document.getElementById('toggleAflever').addEventListener('click', function() {
            let acc = document.getElementById('afleverAccordion');
            if (acc.style.display === 'block') {
                acc.style.display = 'none';
                this.innerHTML = 'Optioneel: Ander afleveradres &#9660;';
            } else {
                acc.style.display = 'block';
                this.innerHTML = 'Optioneel: Ander afleveradres &#9650;';
            }
        });
    </script>
</body>

</html>