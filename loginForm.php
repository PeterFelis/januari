<?php
//file: loginForm.php
include_once __DIR__ . '/incs/sessie.php';
$title = 'Login';
include_once __dir__ . '/incs/top.php';
?>
<style>
    .inlog {
        width: 50%;
        max-width: 400px;

    }

    .mar {
        margin-top: 10rem;
    }
</style>

<body>
    <?php
    $menu = 'normaal';
    include_once __DIR__ . '/incs/menu.php';
    ?>
    <main class="inlog">

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

        <!-- Link voor vergeten wachtwoord -->
        <p class="forgot-link mar">
        <h2>
            <a href="klantForm.php">Of wordt klant! ('t is gratis)</a>
        </h2>
        </p>

    </main>
</body>

</html>