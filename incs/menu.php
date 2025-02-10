<?php
//file: incs/menu.php
// maakt een menu aan met rechts de verschillende pagina's en links het logo
?>

<header>
    <nav>
        <a href="/">Home</a>
        <a href="/onderwijs.php">Onderwijs</a>
        <a href="/zorg.php">Zorg</a>
        <a href="webshopinfo.php">Webshop Info</a>
        <a href="contact.php">Contact</a>
        <a href="shop.php">Webshop</a>
        <a href="registratie.php">Wordt klant</a>

    </nav>
    <?php
    $logo = 'logo';
    $kleur = "paars";
    include_once 'logo.php';
    ?>

</header>

<style>
    header {
        width: 100vw;
        background-color: var(--heellichtpaars);
        font-size: 1.6rem;
        position: relative;
    }

    header nav {
        position: relative;
    }
</style>