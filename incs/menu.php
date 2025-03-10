<?php
// file: incs/menu.php
// $menu kan 'geen', 'normaal', 'beheer' of 'logo' zijn

// Zorg dat de sessie gestart is, indien nog niet gebeurd.
//if (session_status() === PHP_SESSION_NONE) {
//    session_start();
//}

// Bepaal het aantal winkelwagen-items:
// Als de klant is ingelogd (klant_id aanwezig), halen we het aantal uit de database.
// Anders gebruiken we de sessie (bijv. voor gasten of niet-ingelogde gebruikers).
if (isset($_SESSION['klant_id'])) {
    include_once __DIR__ . '/dbConnect.php';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Databaseverbinding mislukt: ' . $e->getMessage());
    }


    $stmt = $pdo->prepare("SELECT COUNT(*) FROM shopping_cart WHERE klant_id = ?");
    $stmt->execute([$_SESSION['klant_id']]);
    $cartCount = $stmt->fetchColumn();
} else {
    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
}
?>

<?php if (isset($menu) && $menu == 'logo'): ?>
    <!-- Logo-menu: alleen het logo wordt getoond -->
    <header>
        <nav>
            <div class="logo">
                <img src="/afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </div>
            <?php if ($cartCount > 0): ?>
                <a href="/cart.php" class="cart-indicator">
                    Winkelwagen: <?php echo $cartCount; ?> soorten producten
                </a>
            <?php endif; ?>
        </nav>
    </header>
<?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <!-- Admin: toon beheermenu -->
    <header>
        <nav>
            <a class="logo" href="/">
                <img src="/afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </a>
            <?php
            // Toon altijd de uitlogknop bij een admin
            echo '<a href="/logout.php">Uitloggen</a>';
            ?>
            <?php if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'dashboard') echo '<a href="/dashboard.php">Dashboard</a>'; ?>
            <?php if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'producten_beheer') echo '<a href="/producten_beheer.php">Productenbeheer</a>'; ?>
            <?php if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'product_sticker') echo '<a href="/product_sticker.php">Sticker Afdrukken</a>'; ?>
            <a href="/klantForm.php">Registreren</a>
            <a href="/contact.php">Contact</a>
            <a href="/leveringsvoorwaarden.php">Leveringsvoorwaarden</a>
            <a href="/webshopinfo.php">Webshopinfo</a>
            <?php if ($cartCount > 0): ?>
                <a href="/cart.php" class="cart-indicator">
                    Winkelwagen: <?php echo $cartCount; ?> soorten producten
                </a>
            <?php endif; ?>
        </nav>
    </header>
<?php elseif (isset($menu) && ($menu == 'normaal' || $menu == 'beheer')): ?>
    <!-- Niet-admin: toon het normale menu -->
    <header>
        <nav>
            <a class="logo" href="/">
                <img src="/afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </a>
            <a href="/onderwijs.php">Onderwijs</a>
            <a href="/zorg.php">Zorg</a>
            <a href="/news.php">NIEUWS</a>
            <a href="/webshopinfo.php">Webshop Info</a>
            <a href="/contact.php">Contact</a>
            <a href="/shop.php">Webshop</a>
            <a href="/timeline.php">Timeline</a>
            <?php
            // Als de gebruiker ingelogd is, tonen we de uitlogknop.
            if (isset($_SESSION['user_id'])) {
                echo '<a href="/dashboard.php">Dashboard</a>';
                echo '<a href="/logout.php">Uitloggen</a>';
            } else {
                // Alleen tonen als we niet op het klantForm-pagina zitten.
                if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'klantForm') {
                    echo '<a href="/klantForm.php">Wordt klant</a>';
                }
                if (strtolower(basename($_SERVER['SCRIPT_NAME'], ".php")) !== 'loginform') {
                    echo '<a href="/loginForm.php">Inloggen</a>';
                }
            }
            ?>
            <?php if ($cartCount > 0): ?>
                <a href="/cart.php" class="cart-indicator">
                    Winkelwagen: <?php echo $cartCount; ?> producten
                </a>
            <?php endif; ?>
        </nav>
    </header>
<?php endif; ?>




<style>
    header {
        width: 100vw;
        margin: 0 auto;
        background-color: transparent;
        background-color: var(--paars);
        font-size: 1.4rem;
        position: relative;
        height: 4rem;
    }

    nav {
        max-width: 1024px;
        width: 1024px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        height: 100%;
        position: relative;
    }

    header nav a {
        color: white;
        transition: transform 0.2s ease-in-out;
    }

    header nav a:visited {
        color: white;
    }

    header nav a:hover {
        transform: scale(1.15);
    }

    .logo {
        height: 200%;
        position: relative;
        margin-top: 1rem;
        z-index: 20;
        /* Zorg ervoor dat het logo altijd boven het vlak staat */
    }

    .logo img {
        height: 100%;
        width: auto;
        z-index: 30;
        position: relative;
    }

    /* Gekanteld vlak */
    .logo::after {
        content: '';
        display: block;
        width: 120%;
        height: 250%;
        background: var(--accent);
        position: absolute;
        z-index: 15;
        /* Boven de hero, onder het logo */
        top: -100%;
        left: -10%;
        transform: rotate(-15deg);
        border-radius: 0% 0% 20% 20%;
        pointer-events: none;
        /* Voorkom flikkeren bij hover */
    }
</style>