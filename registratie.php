<?php
include_once  __DIR__ . '/incs/menuBeheer.php';
?>


<form action="registreer.php" method="POST">
    <h3>Klantinformatie</h3>
    <label for="klant_naam">Klantnaam:</label>
    <input type="text" id="klant_naam" name="klant_naam" required><br><br>

    <label for="adres">Adres:</label>
    <textarea id="adres" name="adres"></textarea><br><br>

    <label for="contact_email">Contact E-mail:</label>
    <input type="email" id="contact_email" name="contact_email"><br><br>

    <label for="telefoonnummer">Telefoonnummer:</label>
    <input type="text" id="telefoonnummer" name="telefoonnummer"><br><br>

    <h3>Gebruikersinformatie</h3>
    <label for="naam">Naam:</label>
    <input type="text" id="naam" name="naam" required><br><br>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="wachtwoord">Wachtwoord:</label>
    <input type="password" id="wachtwoord" name="wachtwoord" required><br><br>

    <button type="submit">Registreer</button>
</form>