<?php
// dashboard.php
session_start();

$title = "Le Dashboard -- ssstt!";
$menu = 'beheer';
include_once __DIR__ . '/incs/top.php';
?>
<body class="indexPaginaKleur">
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <?php
        // Als niet ingelogd, terug naar login
        if (!isset($_SESSION['user_id'])) {
            header("Location: loginForm.php");
            exit;
        }

        // Bepaal de gebruikersnaam
        $name = "";
        if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
            $name = $_SESSION['user_name'];
        } elseif (isset($_SESSION['voornaam']) && isset($_SESSION['achternaam'])) {
            $name = $_SESSION['voornaam'] . " " . $_SESSION['achternaam'];
        } else {
            $name = "Gebruiker";
        }

        echo "<h2>Welkom, " . htmlspecialchars($name) . "!</h2>";

        // Afhankelijk van de rol tonen we andere content
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            // Admin: lijst met klanten, met links naar klantForm.php in edit modus
            echo "<h3>Klant Overzicht</h3>";

            // Maak een PDO-verbinding zodat we de klanten uit de database kunnen halen
            include_once __DIR__ . '/incs/dbConnect.php';
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Databaseverbinding mislukt: " . $e->getMessage());
            }

            try {
                $stmt = $pdo->query("SELECT id, naam FROM klanten ORDER BY naam ASC");
                $klanten = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($klanten) {
                    echo "<ul>";
                    foreach ($klanten as $klant) {
                        // Link naar klantForm.php in edit modus, met klant_id als parameter
                        echo "<li><a href='klantForm.php?edit=1&klant_id=" . $klant['id'] . "'>" . htmlspecialchars($klant['naam']) . "</a></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Geen klanten gevonden.</p>";
                }
            } catch (Exception $e) {
                echo "<p>Er is een fout opgetreden: " . $e->getMessage() . "</p>";
            }
        } else {
            // Niet-admin: toon een link waarmee de gebruiker zijn eigen gegevens kan bewerken
            if (isset($_SESSION['klant_id'])) {
                echo "<p><a href='klantForm.php?edit=1&klant_id=" . urlencode($_SESSION['klant_id']) . "'>Wijzig uw gegevens</a></p>";
            } else {
                echo "<p>Geen klantgegevens gevonden.</p>";
            }
        }
        ?>
    </main>
</body>
</html>
