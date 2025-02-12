<?php
//file: dashboard.php
session_start();


$title = "Le Dashboard -- ssstt!";

$menu = 'beheer';
include_once __dir__ . '/incs/top.php';

?>

<body class='indexPaginaKleur'>
    <?php include_once __dir__ . '/incs/menu.php'; ?>

    <main>

        <?php
        if (!isset($_SESSION['user_id'])) {
            header("Location: loginForm.php"); // Terug naar login als niet ingelogd
            exit;
        }
        ?>

        <h2>dashboard</h2>
        <?php
        echo "Welkom, " . htmlspecialchars($_SESSION['user_name']) . "!";
        ?>
    </main>
</body>

</html>