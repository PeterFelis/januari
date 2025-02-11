<?php
//file: incs/menuBeheer.php
// maakt een menu aan voor de beheer pagina met rechts de verschillende pagina's en links het logo
// logo is hier kleiner
?>


<header>
    <?php
    $logo = 'logoklein';
    $kleur = "paars";
    include_once 'logo.php';
    ?>
    <nav>
        <?php
        if (!isset($_SESSION['user_id'])) { ?>
            <a href="loginform.php">Inloggen</a>
        <?php } ?>

        <a href="logout.php">Logout</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="producten_beheer.php">Productenbeheer</a>
        <a href="product_sticker.php">Sticker Afdrukken</a>
        <a href="registratie.php">Registreren</a>
        <a href="contact.php">Contact</a>
        <a href="leveringsvoorwaarden.php">Leveringsvoorwaarden</a>
        <a href="webshopinfo.php">Webshopinfo</a>
    </nav>



</header>

<style>
    header {
        display: flex;
        justify-content: space-around;
        background-color: var(--heellichtpaars);
        padding: 10px;
        height: 6vh;
        position: relative;
    }
</style>