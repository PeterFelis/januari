<?php
// file: incs/menu.php
// $menu kan 'geen', 'normaal', 'beheer' of 'logo' zijn

// Zorg dat de sessie gestart is, indien nog niet gebeurd.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
                <img src="\afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </div>
            <?php if ($cartCount > 0): ?>
                <a href="cart.php" class="cart-indicator">
                    Winkelwagen: <?= $cartCount ?> producten
                </a>
            <?php endif; ?>
        </nav>
    </header>
<?php else: ?>
    <!-- Volledig responsive menu voor normaal/beheer/admin -->
    <header>
        <nav>
            <a class="logo" href="/">
                <img src="\afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </a>
            <button class="hamburger" onclick="document.body.classList.toggle('menu-open')">&#9776;</button>
            <div class="menu-items">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="/dashboard.php">Dashboard</a>
                    <a href="/producten_beheer.php">Productenbeheer</a>
                    <a href="/product_sticker.php">Sticker Afdrukken</a>
                    <a href="/klantForm.php">Registreren</a>
                    <a href="/logout.php">Uitloggen</a>
                <?php elseif (isset($menu) && ($menu === 'normaal' || $menu === 'beheer')): ?>
                    <a href="/">Onderwijs</a>
                    <a href="/">Zorg</a>
                    <a href="/news.php">NIEUWS</a>
                    <a href="/webshopinfo.php">Webshop Info</a>
                    <a href="/contact.php">Contact</a>
                    <a href="/shop.php">Webshop</a>
                    <a href="/timeline.php">Timeline</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard.php">Dashboard</a>
                        <a href="/logout.php">Uitloggen</a>
                    <?php else: ?>
                        <a href="/klantForm.php">Wordt klant</a>
                        <a href="/loginForm.php">Inloggen</a>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($cartCount > 0): ?>
                    <a href="cart.php" class="cart-indicator">Winkelwagen: <?= $cartCount ?> producten</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
<?php endif; ?>




<style>
    header {
        width: 100vw;
        margin: 0 auto;
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
    }

    .logo img {
        height: 100%;
        width: auto;
        z-index: 30;
        position: relative;
    }

    .logo::after {
        content: '';
        display: block;
        width: 120%;
        height: 250%;
        background: var(--accent);
        position: absolute;
        z-index: 15;
        top: -100%;
        left: -10%;
        transform: rotate(-15deg);
        border-radius: 0% 0% 20% 20%;
        pointer-events: none;
    }

    .hamburger {
        background: none;
        border: none;
        font-size: 2.5rem;
        color: white;
        cursor: pointer;
        display: none;
        z-index: 100;
    }

    .menu-items {
        display: flex;
        gap: 1rem;
    }


    /* Tablet (tot 1024px) → 2 kolommen, 3 items per rij */
    @media (max-width: 1024px) {
        .hamburger {
            display: block;
            position: fixed;
            top: 1rem;
            right: 1rem;
            color: black;
            background: white;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .menu-items {
            position: fixed;
            top: 0;
            left: -100%;
            height: 100vh;
            width: 70vw;
            max-width: 300px;
            background: black;
            /* zwart menu */
            flex-direction: column;
            padding: 4rem 1rem;
            gap: 1.5rem;
            transition: left 0.3s ease-in-out;
            z-index: 900;
        }

        .menu-items a {
            color: white;
            font-size: 1.2rem;
        }

        body.menu-open .menu-items {
            left: 0;
        }

        body.menu-open {
            overflow: hidden;
        }
    }


    @media (max-width: 768px) {
        .logo {
            height: 80%;
            top: -10%
        }

        nav {
            width: 100%;
            max-width: 100%;
            padding: 0 1rem;
        }

        .hamburger {
            display: block;
            position: absolute;
            top: 5rem;
            right: 2rem;
            color: white;
            mix-blend-mode: difference;
        }

        .menu-items {
            position: fixed;
            top: 0;
            left: -100%;
            height: 100vh;
            width: 70vw;
            max-width: 300px;
            background: var(--paars);
            flex-direction: column;
            padding: 4rem 1rem;
            gap: 1.5rem;
            transition: left 0.3s ease-in-out;
            z-index: 90;
        }

        body.menu-open .menu-items {
            left: 0;
        }

        body.menu-open {
            overflow: hidden;
        }
    }
</style>