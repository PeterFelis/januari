<?php
//file: incs/menu.php
// maakt of het normale menu of het beheermenu
// $menu = 'geen', 'normaal' of 'beheer', moet meegegeven worden bij aanroep

if ($menu == 'normaal') { ?>

    <header>
        <nav>
            <a class="logo" href=" /">
                <img src="/afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </a>
            <a href="/onderwijs.php">Onderwijs</a>
            <a href="/zorg.php">Zorg</a>
            <a href="webshopinfo.php">Webshop Info</a>
            <a href="contact.php">Contact</a>
            <a href="shop.php">Webshop</a>
            <?php if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'klantForm') echo '<a href="klantForm.php">Wordt klant</a>';

            if (!isset($_SESSION['user_id']) && (strtolower(basename($_SERVER['SCRIPT_NAME'], ".php")) !== 'loginform')) { ?>
                <a href="loginForm.php">Inloggen</a>
            <?php } ?>

        </nav>
    </header>

<?php } else if ($menu == 'beheer') { ?>

    <header>
        <nav>
            <a class="logo" href=" /">
                <img src="/afbeeldingen/fetumlogo.png" alt="Fetum logo" />
            </a>
            <?php
            if (!isset($_SESSION['user_id']) && (strtolower(basename($_SERVER['SCRIPT_NAME'], ".php")) !== 'loginform')) { ?>
                <a href="loginForm.php">Inloggen</a>
            <?php } ?>
            <a href="logout.php">Logout</a>
            <?php if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'dashboard') echo '<a href="dashboard.php">Dashboard</a>'; ?>
            <?php if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'producten_beheer') echo '<a href="producten_beheer.php">Productenbeheer</a>'; ?>
            <?php if (basename($_SERVER['SCRIPT_NAME'], ".php") !== 'product_sticker') echo '<a href="product_sticker.php">Sticker Afdrukken</a>'; ?>
            <a href="klantForm.php">Registreren</a>
            <a href="contact.php">Contact</a>
            <a href="leveringsvoorwaarden.php">Leveringsvoorwaarden</a>
            <a href="webshopinfo.php">Webshopinfo</a>
        </nav>
    </header>

<?php } ?>

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
    }

    .logo img {
        height: 100%;
        width: auto;
        z-index: 10;
        position: relative;
    }

    .logo::after {
        content: '';
        display: block;
        width: 120%;
        height: 250%;
        background: var(--accent);
        position: absolute;
        z-index: 5;
        top: -100%;
        left: -10%;
        transform: rotate(-15deg);
        border-radius: 0% 0% 20% 20%;
    }
</style>