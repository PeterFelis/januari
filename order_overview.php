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

$klant_id = $_SESSION['klant_id'];

// Haal de orders op voor de huidige klant
$stmt = $pdo->prepare("SELECT * FROM orders WHERE klant_id = ? ORDER BY datum DESC");
$stmt->execute([$klant_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Overzicht</title>
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
    <h1>Overzicht van Bestellingen</h1>
    <?php if (empty($orders)): ?>
        <p>Geen bestellingen gevonden.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th>Totaal Prijs</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['datum']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['totaal_prijs']); ?></td>
                        <td><a href="order_details.php?order_id=<?php echo htmlspecialchars($order['id']); ?>">Bekijk</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>