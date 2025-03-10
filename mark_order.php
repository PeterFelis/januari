<?php
// mark_order.php
include_once __DIR__ . '/incs/sessie.php';
include_once __DIR__ . '/incs/dbConnect.php';

if (isset($_POST['mark_handled']) && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Update de order: zet afgehandeld op 1
        $stmt = $pdo->prepare("UPDATE orders SET afgehandeld = 1 WHERE id = ?");
        $stmt->execute([$orderId]);
        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
    exit;
}
