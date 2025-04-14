<?php
// dashboard.php
include_once __DIR__ . '/incs/sessie.php';

$title = "Le Dashboard -- ssstt!";
$menu = 'beheer';
include_once __DIR__ . '/incs/top.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

// Maak een PDO-verbinding (als dit nog niet in top.php zit)
include_once __DIR__ . '/incs/dbConnect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Haal klantgegevens op uit de 'klanten'-tabel als de klant ingelogd is
$customer = null;
if (isset($_SESSION['klant_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ?");
    $stmt->execute([$_SESSION['klant_id']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Bepaal de bestellernaam (van de users-tabel)
$name = "";
if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
    $name = $_SESSION['user_name'];
} elseif (isset($_SESSION['voornaam']) && isset($_SESSION['achternaam'])) {
    $name = $_SESSION['voornaam'] . " " . $_SESSION['achternaam'];
} else {
    $name = "Gebruiker";
}
?>

<style>
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        width: 100%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        text-align: left;
    }

    .styled-table tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tr:last-of-type {
        border-bottom: 2px solid var(--heellichtpaars);
    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <?php
        // Toon een flash message als deze is gezet (en verberg deze na 5 seconden)
        if (isset($_SESSION['flash_message'])) {
            echo '<div id="flashMessage" style="background: #dff0d8; padding: 10px; margin-bottom: 20px; text-align: center;">' .
                htmlspecialchars($_SESSION['flash_message']) . '</div>';
            unset($_SESSION['flash_message']);
        }
        ?>
        <script>
            setTimeout(function() {
                var flash = document.getElementById('flashMessage');
                if (flash) {
                    flash.style.display = 'none';
                }
            }, 5000);
        </script>

        <h2>Welkom, <?php echo htmlspecialchars($name); ?>!</h2>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <!-- ADMIN WEERGAVE -->
            <p><a href="klanten_overzicht.php">Ga naar Klant Overzicht</a></p>

            <h3>Openstaande Orders</h3>
            <?php
            try {
                // Wijzig de query zodat de naam van de klant (klant_naam) wordt opgehaald
                $stmt = $pdo->query("
        SELECT o.id, k.naam AS klant_naam, o.status, o.totaal_prijs, o.datum 
        FROM orders o 
        LEFT JOIN klanten k ON o.klant_id = k.id 
        WHERE o.afgehandeld = 0 
        ORDER BY o.datum DESC
    ");
                $openOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($openOrders) {
                    echo "<table class='styled-table'>";
                    echo "<tr><th>Order ID</th><th>Klant Naam</th><th>Status</th><th>Totaal Prijs</th><th>Datum</th><th>Actie</th></tr>";
                    foreach ($openOrders as $order) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['klant_naam'] ?? 'Onbekend') . "</td>";
                        echo "<td>" . htmlspecialchars($order['status']) . "</td>";
                        echo "<td>€" . number_format($order['totaal_prijs'], 2, ',', '.') . "</td>";
                        echo "<td>" . htmlspecialchars($order['datum']) . "</td>";
                        echo "<td>
                    <form method='post' action='mark_order.php'>
                        <input type='hidden' name='order_id' value='" . htmlspecialchars($order['id']) . "'>
                        <button type='submit' name='mark_handled'>Afgehandeld</button>
                    </form>
                  </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Geen openstaande orders.</p>";
                }
            } catch (Exception $e) {
                echo "<p>Er is een fout opgetreden: " . $e->getMessage() . "</p>";
            }
            ?>
        <?php else: ?>
            <!-- KLANT WEERGAVE -->
            <?php if ($customer): ?>
                <h3>Uw Gegevens</h3>
                <p>
                    Naam: <?php echo htmlspecialchars($customer['naam']); ?><br>
                    Adres: <?php echo htmlspecialchars($customer['straat']); ?> <?php echo htmlspecialchars($customer['nummer']); ?><br>
                    Postcode: <?php echo htmlspecialchars($customer['postcode']); ?><br>
                    Plaats: <?php echo htmlspecialchars($customer['plaats']); ?><br>
                    Email: <?php echo htmlspecialchars($customer['algemene_email'] ?? ''); ?><br>
                    <a href="klantForm.php?edit=1&klant_id=<?php echo urlencode($_SESSION['klant_id']); ?>">Wijzig uw gegevens</a>
                </p>
            <?php endif; ?>

            <?php if (isset($_SESSION['klant_id'])): ?>

                <p>
                <h2><a href="/shop.php">Ga naar de Winkel</a></h2>
                </P>

                <!-- Oude Bestellingen -->
                <?php
                $stmt = $pdo->prepare("SELECT id, status, totaal_prijs, datum FROM orders WHERE klant_id = ? ORDER BY datum DESC");
                $stmt->execute([$_SESSION['klant_id']]);
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<h3>Uw Oude Bestellingen</h3>";
                if ($orders) {
                    echo "<table class='styled-table'>";
                    echo "<tr><th>Order ID</th><th>Status</th><th>Totaal Prijs</th><th>Datum</th></tr>";
                    foreach ($orders as $order) {
                        echo "<tr onclick=\"window.location.href='order_details.php?order_id=" . urlencode($order['id']) . "';\" style='cursor:pointer;'>";
                        echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['status']) . "</td>";
                        echo "<td>€" . number_format($order['totaal_prijs'], 2, ',', '.') . "</td>";
                        echo "<td>" . htmlspecialchars($order['datum']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Er zijn geen eerdere bestellingen.</p>";
                }
                ?>

                <!-- Winkelwagen -->
                <?php
                $stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE klant_id = ?");
                $stmt->execute([$_SESSION['klant_id']]);
                $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<h3>Uw Winkelwagen</h3>";
                if ($cartItems) {
                    echo "<table class='styled-table'>";
                    echo "<tr><th>TypeNummer</th><th>Aantal</th><th>Totaal Prijs</th></tr>";
                    foreach ($cartItems as $item) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['TypeNummer']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['aantal']) . "</td>";
                        echo "<td>€" . number_format($item['totaal_prijs'], 2, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<p><a href='/cart.php'>Ga naar de Winkelwagen</a></p>";
                } else {
                    echo "<p>Uw winkelwagen is leeg.</p>";
                }


                ?>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>

</html>