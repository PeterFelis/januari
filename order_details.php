<?php
// order_details.php
// weergave van oude bestellingen
// 05-03-2025
include_once __DIR__ . '/incs/sessie.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

$title = "Order Details -- ssstt!";
$menu = 'orders';
include_once __DIR__ . '/incs/top.php';

// Maak een PDO-verbinding (indien nog niet aanwezig in top.php)
include_once __DIR__ . '/incs/dbConnect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Zorg dat er een order_id is meegegeven
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "<p>Geen order geselecteerd.</p>";
    exit;
}
$order_id = $_GET['order_id'];

// Haal de ordergegevens op uit de tabel orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    echo "<p>Order niet gevonden.</p>";
    exit;
}

// Haal de order items op met de juiste kolomnamen en aliassen
$stmt = $pdo->prepare("
    SELECT 
        oi.aantal AS quantity, 
        oi.prijs_per_stuk AS price, 
        p.TypeNummer AS product_naam 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            text-align: left;
        }
        .styled-table tr {
            border-bottom: 1px solid #dddddd;
        }
        .styled-table tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
        .styled-table tr:last-of-type {
            border-bottom: 2px solid var(--heellichtpaars);
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <h2>Order Details voor Order #<?php echo htmlspecialchars($order['id']); ?></h2>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
        <p><strong>Totaal Prijs:</strong> €<?php echo number_format($order['totaal_prijs'], 2, ',', '.'); ?></p>
        <p><strong>Datum:</strong> <?php echo htmlspecialchars($order['datum']); ?></p>

        <h3>Artikelen in deze order</h3>
        <?php if ($orderItems): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Productnaam</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                        <th>Subtotaal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td>
                                <a href="/artikelen/<?php echo urlencode($item['product_naam']); ?>/index.php">
                                    <?php echo htmlspecialchars($item['product_naam']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>€<?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td>€<?php echo number_format($item['quantity'] * $item['price'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Geen artikelen gevonden voor deze order.</p>
        <?php endif; ?>

        <p><a href="dashboard.php">Terug naar Dashboard</a></p>
    </main>
</body>
</html>

