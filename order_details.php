<?php
session_start();
include_once __DIR__ . '/incs/dbConnect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . $e->getMessage());
}


if (!isset($_SESSION['klant_id'])) {
    header("Location: /login.php");
    exit();
}

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die("Geen order geselecteerd.");
}

// Haal de ordergegevens op, zorg dat de order ook bij de ingelogde klant hoort
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND klant_id = ?");
$stmt->execute([$order_id, $_SESSION['klant_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    die("Order niet gevonden.");
}

// Haal de order items op
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Details</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }

        th {
            background: #f4f4f4;
        }
    </style>
</head>

<body>
    <h1>Order Details voor Order #<?php echo htmlspecialchars($order['id']); ?></h1>
    <p>Datum: <?php echo htmlspecialchars($order['datum']); ?></p>
    <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
    <p>Totaal Prijs: <?php echo htmlspecialchars($order['totaal_prijs']); ?></p>

    <h2>Artikelen</h2>
    <?php if (empty($order_items)): ?>
        <p>Geen artikelen gevonden.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Aantal</th>
                    <th>Prijs per stuk</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['aantal']); ?></td>
                        <td><?php echo htmlspecialchars($item['prijs_per_stuk']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <p><a href="order_overview.php">Terug naar overzicht</a></p>
</body>

</html>