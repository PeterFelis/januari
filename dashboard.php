<?php
//file: dashboard.php
session_start();


$title = 'Le Dashboard';
include_once __dir__ . '/incs/top.php';

include_once  __DIR__ . '/incs/menuBeheer.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php"); // Terug naar login als niet ingelogd
    exit;
}
?>

<h2>dashboard</h2>
<?php
echo "Welkom, " . htmlspecialchars($_SESSION['user_name']) . "!";
echo "<br><a href='logout.php'>Uitloggen</a>";
