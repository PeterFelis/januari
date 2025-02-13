<?php
//file: loginForm.php



$title = 'Login';
include_once __dir__ . '/incs/top.php';
?>

<body class='indexPaginaKleur'>
    <main>
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
    </main>
</body>

</html>