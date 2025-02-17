<?php
//file: loginForm.php

$title = 'Login';
include_once __dir__ . '/incs/top.php';
?>
<style>
    .inlog {
        width: 50%;
        max-width: 400px;
        margin: 0 auto;
        padding-top: 10vh;
    }
</style>

<body class='indexPaginaKleur'>
    <?php
    $menu = 'normaal';
    include_once __DIR__ . '/incs/menu.php';
    ?>
    <div class="inlog">
        <h2>Inloggen</h2>
        <form action="login.php" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="wachtwoord">Wachtwoord:</label>
            <input type="password" id="wachtwoord" name="wachtwoord" required><br><br>

            <button type="submit">Inloggen</button>
        </form>

        <!-- Link voor vergeten wachtwoord -->
        <p class="forgot-link">
            <a href="forgot_password.php">Wachtwoord vergeten?</a>
        </p>
        </divs
            </body>

        </html>