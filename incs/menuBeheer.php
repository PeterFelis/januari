<div class='menu'>
    <?php
    if (!isset($_SESSION['user_id'])) { ?>
        <a href="loginform.php">Inloggen</a>
    <?php } ?>

    <a href="logout.php">Logout</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="producten_beheer.php">Productenbeheer</a>
    <a href="product_sticker.php">Sticker Afdrukken</a>
    <a href="registratie.php">Registreren</a>

</div>

<style>
    .menu {
        display: flex;
        justify-content: space-around;
        background-color: #f1f1f1;
        padding: 10px;
    }
</style>