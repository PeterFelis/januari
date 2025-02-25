<?php
// file: incs/menu.php
// $menu kan 'geen', 'normaal', 'beheer' of 'logo' zijn

if (isset($menu) && $menu == 'logo') {
    // Logo-menu: alleen het logo wordt getoond
?>
    <header>
        <nav>
            <div class="logo">
                <img src="/afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </div>
        </nav>
    </header>
<?php
} else if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Admin: toon beheermenu
?>
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
        </nav>
    </header>
<?php
} else if (isset($menu) && ($menu == 'normaal' || $menu == 'beheer')) {
    // Niet-admin: toon het normale menu
?>
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
            <?php
            if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'klantForm') {
                echo '<a href="/klantForm.php">Wordt klant</a>';
            }
            // Alleen de inlogknop tonen als de gebruiker niet ingelogd is
            if (!isset($_SESSION['user_id']) && (strtolower(basename($_SERVER['SCRIPT_NAME'], ".php")) !== 'loginform')) {
                echo '<a href="/loginForm.php">Inloggen</a>';
            }
            ?>
        </nav>
    </header>
<?php
}
?>


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