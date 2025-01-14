<?php
session_start();

include_once  __DIR__ . '/incs/menutijd.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php"); // Terug naar login als niet ingelogd
    exit;
}
?>

<h2>dashboard</h2>
<?php
echo "Welkom, " . htmlspecialchars($_SESSION['user_name']) . "!";
echo "<br><a href='logout.php'>Uitloggen</a>";
