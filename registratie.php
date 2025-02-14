<?php
// registratie.php
session_start();

$title = "Welkom als nieuwe klant!";
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>

<body class='indexPaginaKleur'>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <form action="registreer.php" method="POST">
            <h3>Klantinformatie</h3>

            <label for="klant_naam">Klantnaam:</label>
            <input type="text" id="klant_naam" name="klant_naam" required><br><br>

            <label for="straat">Straat:</label>
            <input type="text" id="straat" name="straat" required><br><br>

            <label for="nummer">Nummer:</label>
            <input type="text" id="nummer" name="nummer" required><br><br>

            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode" required><br><br>

            <label for="plaats">Plaats:</label>
            <input type="text" id="plaats" name="plaats" required><br><br>

            <label for="extra_veld">Extra veld:</label>
            <textarea id="extra_veld" name="extra_veld"></textarea><br><br>

            <label for="algemeen_telefoonnummer">Algemeen telefoonnummer:</label>
            <input type="text" id="algemeen_telefoonnummer" name="algemeen_telefoonnummer"><br><br>

            <label for="algemene_email">Algemene email:</label>
            <input type="email" id="algemene_email" name="algemene_email"><br><br>

            <label for="url">URL:</label>
            <input type="text" id="url" name="url"><br><br>

            <script>
                // Zorg dat de script pas wordt uitgevoerd als de pagina volledig is geladen
                document.addEventListener('DOMContentLoaded', function() {
                    // Selecteer het formulier
                    const form = document.querySelector('form');
                    form.addEventListener('submit', function(event) {
                        // Haal de waarde van de URL-input op en trim spaties
                        const urlField = document.getElementById('url');
                        let urlValue = urlField.value.trim();

                        // Als er iets ingevuld is en het begint niet met http:// of https://
                        if (urlValue && !/^https?:\/\//i.test(urlValue)) {
                            // Voeg http:// toe
                            urlField.value = 'http://' + urlValue;
                        }
                    });
                });
            </script>

            <label for="factuur_email">Factuur email adres:</label>
            <input type="email" id="factuur_email" name="factuur_email"><br><br>

            <label for="factuur_extra_info">Extra info voor factuur:</label>
            <textarea id="factuur_extra_info" name="factuur_extra_info"></textarea><br><br>

            <h3>Gebruikersinformatie</h3>

            <label for="naam">Naam:</label>
            <input type="text" id="naam" name="naam" required><br><br>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="wachtwoord">Wachtwoord:</label>
            <input type="password" id="wachtwoord" name="wachtwoord" required><br><br>

            <button type="submit">Registreer</button>
        </form>
    </main>
</body>

</html>