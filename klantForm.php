<?php
// klantForm.php
session_start();

// Standaard instellingen voor registreren
$edit = false;
$action = "registreer.php";
$pageTitle = "Welkom als nieuwe klant!";
$submitLabel = "Registreer";

// Zet een array met alle velden op met lege standaardwaarden
$klantData = [
    "naam" => "",
    "straat" => "",
    "nummer" => "",
    "postcode" => "",
    "plaats" => "",
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

if (isset($_GET['edit']) && $_GET['edit'] == 1) {
    $edit = true;
    $pageTitle = "Bewerk uw gegevens";
    $action = "updateKlant.php";
    $submitLabel = "Bijwerken";
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
        $stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ?");
        $stmt->execute([$klant_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $klantData = array_merge($klantData, $data);
        }
        $stmtUser = $pdo->prepare("SELECT * FROM users WHERE klant_id = ?");
        $stmtUser->execute([$klant_id]);
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
        if ($userData) {
            $klantData['voornaam'] = $userData['voornaam'];
            $klantData['achternaam'] = $userData['achternaam'];
            $klantData['geslacht'] = $userData['geslacht'];
            $klantData['email'] = $userData['email'];
        }
    }
}

$title = $pageTitle;
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <style>
        /* Formulier styling */
        form.registration-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: transparent;
            padding: 2rem;
            border: 1px dashed #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        form.registration-form h3 {
            border-bottom: 2px dashed var(--groen);
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 20px;
            color: var(--groen);
        }

        form.registration-form h4 {
            margin-top: 20px;
            margin-bottom: 5px;
            color: var(--groen);
            cursor: pointer;
        }

        p.instructions {
            text-align: center;
            font-size: 1em;
            margin-bottom: 20px;
        }

        .required {
            color: red;
        }

        /* Adresregels: straat en nummer op één regel */
        .adres-regel {
            display: flex;
            gap: 1rem;
            margin-bottom: 15px;
        }

        .adres-regel .straat {
            flex: 3;
        }

        .adres-regel .nummer {
            flex: 1;
        }

        /* Adresregels: postcode en plaats op één regel */
        .adres-regel2 {
            display: flex;
            gap: 1rem;
            margin-bottom: 15px;
        }

        .adres-regel2 .postcode {
            flex: 1;
        }

        .adres-regel2 .plaats {
            flex: 3;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .cancelBtn {
            background-color: red;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancelBtn:hover {
            background-color: darkred;
        }

        /* Accordion styling voor optionele adressen */
        .accordion-content {
            display: none;
            padding: 0 10px;
            margin-top: 10px;
            border-left: 2px solid #ccc;
        }

        /* Multi-step formulier */
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .radio-group {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-bottom: 15px;
        }

        .radio-group label {
            display: inline-block;
        }
    </style>
</head>

<body class="indexPaginaKleur">
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <p class="instructions">Velden gemarkeerd met <span class="required">*</span> zijn verplicht.</p>
        <form id="multiStepForm" class="registration-form" action="<?php echo $action; ?>" method="POST">
            <?php if ($edit && isset($klant_id)) { ?>
                <input type="hidden" name="klant_id" value="<?php echo htmlspecialchars($klant_id); ?>">
            <?php } ?>
            <!-- Stap 1: Klantinformatie -->
            <div class="form-step center-required active" id="step-1">
                <h3>Klantinformatie</h3>
                <label for="naam">Klantnaam: <span class="required">*</span></label>
                <input type="text" id="naam" name="naam" required value="<?php echo htmlspecialchars($klantData['naam']); ?>">
                <!-- Straat en nummer op één regel -->
                <div class="adres-regel">
                    <div class="straat">
                        <label for="straat">Straat: <span class="required">*</span></label>
                        <input type="text" id="straat" name="straat" required value="<?php echo htmlspecialchars($klantData['straat']); ?>">
                    </div>
                    <div class="nummer">
                        <label for="nummer">Nummer: <span class="required">*</span></label>
                        <input type="text" id="nummer" name="nummer" required value="<?php echo htmlspecialchars($klantData['nummer']); ?>">
                    </div>
                </div>
                <!-- Postcode en plaats op één regel -->
                <div class="adres-regel2">
                    <div class="postcode">
                        <label for="postcode">Postcode: <span class="required">*</span></label>
                        <input type="text" id="postcode" name="postcode" required value="<?php echo htmlspecialchars($klantData['postcode']); ?>">
                    </div>
                    <div class="plaats">
                        <label for="plaats">Plaats: <span class="required">*</span></label>
                        <input type="text" id="plaats" name="plaats" required value="<?php echo htmlspecialchars($klantData['plaats']); ?>">
                    </div>
                </div>
                <!-- Knop om adresgegevens via de API op te halen -->
                <div>
                    <button type="button" id="getAddressBtn">Adres ophalen</button>
                </div>
                <label for="extra_veld">Extra veld:</label>
                <textarea id="extra_veld" name="extra_veld" placeholder="als u hier iets invoert dan wordt dit in de adressering gezet"><?php echo htmlspecialchars($klantData['extra_veld']); ?></textarea>
                <div class="button-group">
                    <span></span>
                    <button type="button" class="cancelBtn">Cancel</button>
                    <button type="button" id="nextBtn1">Volgende</button>
                </div>
            </div>
            <!-- Stap 2: Gebruikersinformatie -->
            <div class="form-step center-required" id="step-2">
                <h3>Gebruikersinformatie</h3>
                <label for="voornaam">Voornaam: <span class="required">*</span></label>
                <input type="text" id="voornaam" name="voornaam" required value="<?php echo htmlspecialchars($klantData['voornaam']); ?>">
                <label for="achternaam">Achternaam: <span class="required">*</span></label>
                <input type="text" id="achternaam" name="achternaam" required value="<?php echo htmlspecialchars($klantData['achternaam']); ?>">
                <p>Geslacht: <span class="required">*</span></p>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="geslacht" value="M" required <?php if ($klantData['geslacht'] === 'M') echo "checked"; ?>> M
                    </label>
                    <label>
                        <input type="radio" name="geslacht" value="V" required <?php if ($klantData['geslacht'] === 'V') echo "checked"; ?>> V
                    </label>
                    <label>
                        <input type="radio" name="geslacht" value="X" required <?php if ($klantData['geslacht'] === 'X') echo "checked"; ?>> X
                    </label>
                </div>
                <label for="email">E-mail: <span class="required">*</span></label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($klantData['email']); ?>">
                <label for="wachtwoord">Wachtwoord: <span class="required">*</span></label>
                <input type="password" id="wachtwoord" name="wachtwoord" <?php echo $edit ? "" : "required"; ?>>
                <label for="wachtwoord_confirm">Herhaal wachtwoord: <span class="required">*</span></label>
                <input type="password" id="wachtwoord_confirm" name="wachtwoord_confirm" <?php echo $edit ? "" : "required"; ?>>
                <div id="passwordError" class="error-message" style="display: none;">De wachtwoorden komen niet overeen!</div>
                <div class="button-group">
                    <button type="button" id="prevBtn2">Vorige</button>
                    <button type="button" class="cancelBtn">Cancel</button>
                    <button type="button" id="nextBtn2">Volgende</button>
                </div>
            </div>
            <!-- Stap 3: Aanvullende informatie -->
            <div class="form-step" id="step-3">
                <h3>Aanvullende informatie</h3>
                <label for="algemeen_telefoonnummer">Algemeen telefoonnummer:</label>
                <input type="text" id="algemeen_telefoonnummer" name="algemeen_telefoonnummer" value="<?php echo htmlspecialchars($klantData['algemeen_telefoonnummer']); ?>">
                <label for="algemene_email">Algemene email:</label>
                <input type="email" id="algemene_email" name="algemene_email" value="<?php echo htmlspecialchars($klantData['algemene_email']); ?>">
                <label for="website">Website:</label>
                <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($klantData['website']); ?>">
                <label for="factuur_email">Factuur email adres:</label>
                <input type="email" id="factuur_email" name="factuur_email" value="<?php echo htmlspecialchars($klantData['factuur_email']); ?>">
                <label for="factuur_extra_info">Extra info voor factuur:</label>
                <textarea id="factuur_extra_info" name="factuur_extra_info" placeholder="bijv. brinnummer"><?php echo htmlspecialchars($klantData['factuur_extra_info']); ?></textarea>
                <h4 id="toggleFactuur">Optioneel: Ander factuuradres &#9660;</h4>
                <div id="factuurAccordion" class="accordion-content">
                    <div class="adres-regel">
                        <div class="straat">
                            <label for="factuur_straat">Straat:</label>
                            <input type="text" id="factuur_straat" name="factuur_straat" value="<?php echo htmlspecialchars($klantData['factuur_straat']); ?>">
                        </div>
                        <div class="nummer">
                            <label for="factuur_nummer">Nummer:</label>
                            <input type="text" id="factuur_nummer" name="factuur_nummer" value="<?php echo htmlspecialchars($klantData['factuur_nummer']); ?>">
                        </div>
                    </div>
                    <div class="adres-regel2">
                        <div class="postcode">
                            <label for="factuur_postcode">Postcode:</label>
                            <input type="text" id="factuur_postcode" name="factuur_postcode" value="<?php echo htmlspecialchars($klantData['factuur_postcode']); ?>">
                        </div>
                        <div class="plaats">
                            <label for="factuur_plaats">Plaats:</label>
                            <input type="text" id="factuur_plaats" name="factuur_plaats" value="<?php echo htmlspecialchars($klantData['factuur_plaats']); ?>">
                        </div>
                    </div>
                </div>
                <h4 id="toggleAflever">Optioneel: Ander afleveradres &#9660;</h4>
                <div id="afleverAccordion" class="accordion-content">
                    <div class="adres-regel">
                        <div class="straat">
                            <label for="aflever_straat">Straat:</label>
                            <input type="text" id="aflever_straat" name="aflever_straat" value="<?php echo htmlspecialchars($klantData['aflever_straat']); ?>">
                        </div>
                        <div class="nummer">
                            <label for="aflever_nummer">Nummer:</label>
                            <input type="text" id="aflever_nummer" name="aflever_nummer" value="<?php echo htmlspecialchars($klantData['aflever_nummer']); ?>">
                        </div>
                    </div>
                    <div class="adres-regel2">
                        <div class="postcode">
                            <label for="aflever_postcode">Postcode:</label>
                            <input type="text" id="aflever_postcode" name="aflever_postcode" value="<?php echo htmlspecialchars($klantData['aflever_postcode']); ?>">
                        </div>
                        <div class="plaats">
                            <label for="aflever_plaats">Plaats:</label>
                            <input type="text" id="aflever_plaats" name="aflever_plaats" value="<?php echo htmlspecialchars($klantData['aflever_plaats']); ?>">
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" id="prevBtn3">Vorige</button>
                    <button type="button" class="cancelBtn">Cancel</button>
                    <button type="submit"><?php echo $submitLabel; ?></button>
                </div>
            </div>
        </form>
    </main>
    <script>
        // Multi-step formulier logica
        const steps = document.querySelectorAll('.form-step');
        let currentStep = 0;

        function showStep(step) {
            steps.forEach((elem, index) => {
                elem.classList.toggle('active', index === step);
            });
        }
        document.getElementById('nextBtn1').addEventListener('click', () => {
            currentStep = 1;
            showStep(currentStep);
        });
        document.getElementById('prevBtn2').addEventListener('click', () => {
            currentStep = 0;
            showStep(currentStep);
        });
        document.getElementById('nextBtn2').addEventListener('click', () => {
            const wachtwoord = document.getElementById('wachtwoord').value;
            const wachtwoordConfirm = document.getElementById('wachtwoord_confirm').value;
            if (wachtwoord !== wachtwoordConfirm) {
                document.getElementById('passwordError').style.display = 'block';
                return;
            } else {
                document.getElementById('passwordError').style.display = 'none';
            }
            currentStep = 2;
            showStep(currentStep);
        });
        document.getElementById('prevBtn3').addEventListener('click', () => {
            currentStep = 1;
            showStep(currentStep);
        });
        document.getElementById('wachtwoord_confirm').addEventListener('input', function() {
            const wachtwoord = document.getElementById('wachtwoord').value;
            if (wachtwoord !== this.value) {
                document.getElementById('passwordError').style.display = 'block';
            } else {
                document.getElementById('passwordError').style.display = 'none';
            }
        });
        document.getElementById('multiStepForm').addEventListener('submit', function(event) {
            const websiteField = document.getElementById('website');
            let websiteValue = websiteField.value.trim();
            if (websiteValue && !/^https?:\/\//i.test(websiteValue)) {
                websiteField.value = 'http://' + websiteValue;
            }
        });
        // Accordion toggles voor factuur- en afleveradres
        document.getElementById('toggleFactuur').addEventListener('click', function() {
            const content = document.getElementById('factuurAccordion');
            content.style.display = content.style.display === 'block' ? 'none' : 'block';
            this.innerHTML = content.style.display === 'block' ? 'Optioneel: Ander factuuradres &#9650;' : 'Optioneel: Ander factuuradres &#9660;';
        });
        document.getElementById('toggleAflever').addEventListener('click', function() {
            const content = document.getElementById('afleverAccordion');
            content.style.display = content.style.display === 'block' ? 'none' : 'block';
            this.innerHTML = content.style.display === 'block' ? 'Optioneel: Ander afleveradres &#9650;' : 'Optioneel: Ander afleveradres &#9660;';
        });
        // Cancel-knoppen: ga terug naar dashboard
        document.querySelectorAll('.cancelBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                window.location.href = "dashboard.php";
            });
        });
        // Postcode API integratie via proxy
        document.getElementById('getAddressBtn').addEventListener('click', function() {
            let postcode = document.getElementById('postcode').value.trim();
            let nummer = document.getElementById('nummer').value.trim();
            if (!postcode || !nummer) {
                alert("Vul zowel postcode als nummer in om het adres op te halen.");
                return;
            }
            // Verwijder spaties uit de postcode (bijv. "1422 BE" wordt "1422BE")
            postcode = postcode.replace(/\s+/g, '');
            document.getElementById('postcode').value = postcode;

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
                        alert("Geen adresgegevens gevonden voor deze postcode en nummer.");
                    }
                })
                .catch(error => {
                    console.error('Fout:', error);
                    alert('Er is een fout opgetreden bij het ophalen van het adres.');
                });
        });
    </script>
</body>

</html>