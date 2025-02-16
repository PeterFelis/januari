<?php
// registratieForm.php 
session_start();

$title = "Welkom als nieuwe klant!";
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <style>
    /* Formulier smaller maken en centreren */
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

    form.registration-form p.optional {
      font-size: 0.9em;
      color: #666;
      margin-bottom: 10px;
    }

    .required {
      color: red;
    }

    /* De instructietekst centreren */
    p.instructions {
      text-align: center;
      font-size: 1em;
      margin-bottom: 20px;
    }

    /* Verplichte velden in stap 1 en 2 (container blijft links uitgelijnd) */
    .form-step.center-required {
      font-size: 0.9em;
    }

    /* Groepen (adresregels) blijven links */
    .form-step.center-required .adres-regel,
    .form-step.center-required .adres-regel2 {
      text-align: left;
    }

    /* Standaard invoervelden */
    input[type="text"],
    input[type="email"],
    input[type="password"],
    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      transition: border-color 0.3s;
      margin-bottom: 15px;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    textarea:focus {
      border-color: #007bff;
      outline: none;
    }

    /* Kleinere velden (bv. e-mail, wachtwoord, telefoon, website) */
    input.small-field {
      width: 50%;
    }

    /* Groepering: Straat & Nummer (3/4 & 1/4) */
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

    /* Groepering: Postcode & Plaats (1/4 & 3/4) */
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

    /* Styling voor de radio buttons: alle opties links uitgelijnd */
    .radio-group {
      display: flex;
      justify-content: flex-start;
      gap: 10px;
      margin-bottom: 15px;
    }

    .radio-group label {
      display: inline-block;
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

    .button-group {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .error-message {
      color: red;
      font-size: 0.9em;
      margin-top: -10px;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <?php include_once __DIR__ . '/incs/menu.php'; ?>
  <main>
    <p class="instructions">Velden gemarkeerd met <span class="required">*</span> zijn verplicht.</p>
    <form id="multiStepForm" class="registration-form" action="registreer.php" method="POST">
      <!-- Stap 1: Klantinformatie (verplicht) -->
      <div class="form-step center-required active" id="step-1">
        <h3>Klantinformatie</h3>
        <label for="klant_naam">Klantnaam: <span class="required">*</span></label>
        <input type="text" id="klant_naam" name="klant_naam" required>

        <!-- Straat en nummer op één regel -->
        <div class="adres-regel">
          <div class="straat">
            <label for="straat">Straat: <span class="required">*</span></label>
            <input type="text" id="straat" name="straat" required>
          </div>
          <div class="nummer">
            <label for="nummer">Nummer: <span class="required">*</span></label>
            <input type="text" id="nummer" name="nummer" required>
          </div>
        </div>

        <!-- Postcode en plaats op één regel -->
        <div class="adres-regel2">
          <div class="postcode">
            <label for="postcode">Postcode: <span class="required">*</span></label>
            <input type="text" id="postcode" name="postcode" required>
          </div>
          <div class="plaats">
            <label for="plaats">Plaats: <span class="required">*</span></label>
            <input type="text" id="plaats" name="plaats" required>
          </div>
        </div>

        <label for="extra_veld">Extra veld:</label>
        <textarea id="extra_veld" name="extra_veld" placeholder="als u hier iets invoert dan wordt dit in de adressering gezet"></textarea>

        <div class="button-group">
          <button type="button" id="nextBtn1">Volgende</button>
        </div>
      </div>

      <!-- Stap 2: Gebruikersinformatie (verplicht) -->
      <div class="form-step center-required" id="step-2">
        <h3>Gebruikersinformatie</h3>
        <label for="voornaam">Voornaam: <span class="required">*</span></label>
        <input type="text" id="voornaam" name="voornaam" required>

        <label for="achternaam">Achternaam: <span class="required">*</span></label>
        <input type="text" id="achternaam" name="achternaam" required>

        <p>Geslacht: <span class="required">*</span></p>
        <div class="radio-group">
          <label>
            <input type="radio" name="geslacht" value="M" required> M
          </label>
          <label>
            <input type="radio" name="geslacht" value="V" required> V
          </label>
          <label>
            <input type="radio" name="geslacht" value="X" required> X
          </label>
        </div>

        <label for="email">E-mail: <span class="required">*</span></label>
        <input type="email" id="email" name="email" class="small-field" required>

        <label for="wachtwoord">Wachtwoord: <span class="required">*</span></label>
        <input type="password" id="wachtwoord" name="wachtwoord" class="small-field" required>

        <label for="wachtwoord_confirm">Herhaal wachtwoord: <span class="required">*</span></label>
        <input type="password" id="wachtwoord_confirm" name="wachtwoord_confirm" class="small-field" required>
        <div id="passwordError" class="error-message" style="display: none;">De wachtwoorden komen niet overeen!</div>

        <div class="button-group">
          <button type="button" id="prevBtn2">Vorige</button>
          <button type="button" id="nextBtn2">Volgende</button>
        </div>
      </div>

      <!-- Stap 3: Aanvullende informatie (optioneel) -->
      <div class="form-step" id="step-3">
        <h3>Aanvullende informatie</h3>
        <label for="algemeen_telefoonnummer">Algemeen telefoonnummer:</label>
        <input type="text" id="algemeen_telefoonnummer" name="algemeen_telefoonnummer" class="small-field">

        <label for="algemene_email">Algemene email:</label>
        <input type="email" id="algemene_email" name="algemene_email" class="small-field">

        <label for="website">Website:</label>
        <input type="text" id="website" name="website" class="small-field">

        <label for="factuur_email">Factuur email adres:</label>
        <input type="email" id="factuur_email" name="factuur_email" class="small-field">

        <!-- Extra info voor factuur, zoals brinnummer -->
        <label for="factuur_extra_info">Extra info voor factuur:</label>
        <textarea id="factuur_extra_info" name="factuur_extra_info" placeholder="bijv. brinnummer"></textarea>

        <!-- Accordion voor Ander factuuradres -->
        <h4 id="toggleFactuur">Optioneel: Ander factuuradres &#9660;</h4>
        <div id="factuurAccordion" class="accordion-content">
          <div class="adres-regel">
            <div class="straat">
              <label for="factuur_straat">Straat:</label>
              <input type="text" id="factuur_straat" name="factuur_straat">
            </div>
            <div class="nummer">
              <label for="factuur_nummer">Nummer:</label>
              <input type="text" id="factuur_nummer" name="factuur_nummer">
            </div>
          </div>
          <div class="adres-regel2">
            <div class="postcode">
              <label for="factuur_postcode">Postcode:</label>
              <input type="text" id="factuur_postcode" name="factuur_postcode">
            </div>
            <div class="plaats">
              <label for="factuur_plaats">Plaats:</label>
              <input type="text" id="factuur_plaats" name="factuur_plaats">
            </div>
          </div>
        </div>

        <!-- Accordion voor Ander afleveradres -->
        <h4 id="toggleAflever">Optioneel: Ander afleveradres &#9660;</h4>
        <div id="afleverAccordion" class="accordion-content">
          <div class="adres-regel">
            <div class="straat">
              <label for="aflever_straat">Straat:</label>
              <input type="text" id="aflever_straat" name="aflever_straat">
            </div>
            <div class="nummer">
              <label for="aflever_nummer">Nummer:</label>
              <input type="text" id="aflever_nummer" name="aflever_nummer">
            </div>
          </div>
          <div class="adres-regel2">
            <div class="postcode">
              <label for="aflever_postcode">Postcode:</label>
              <input type="text" id="aflever_postcode" name="aflever_postcode">
            </div>
            <div class="plaats">
              <label for="aflever_plaats">Plaats:</label>
              <input type="text" id="aflever_plaats" name="aflever_plaats">
            </div>
          </div>
        </div>

        <div class="button-group">
          <button type="button" id="prevBtn3">Vorige</button>
          <button type="submit">Registreer</button>
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

    // Navigatie tussen de stappen
    document.getElementById('nextBtn1').addEventListener('click', () => {
      currentStep = 1;
      showStep(currentStep);
    });

    document.getElementById('prevBtn2').addEventListener('click', () => {
      currentStep = 0;
      showStep(currentStep);
    });

    document.getElementById('nextBtn2').addEventListener('click', () => {
      // Controleer of de wachtwoorden overeenkomen
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

    // Directe controle op het herhaal wachtwoord veld bij invoer
    document.getElementById('wachtwoord_confirm').addEventListener('input', function() {
      const wachtwoord = document.getElementById('wachtwoord').value;
      if (wachtwoord !== this.value) {
        document.getElementById('passwordError').style.display = 'block';
      } else {
        document.getElementById('passwordError').style.display = 'none';
      }
    });

    // Website-validatie: voeg 'http://' toe indien nodig
    document.getElementById('multiStepForm').addEventListener('submit', function(event) {
      const websiteField = document.getElementById('website');
      let websiteValue = websiteField.value.trim();
      if (websiteValue && !/^https?:\/\//i.test(websiteValue)) {
        websiteField.value = 'http://' + websiteValue;
      }
    });

    // Accordion toggles voor optionele adressen
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
  </script>
</body>

</html>