<?php
//file: klanten_overzicht.php
include_once __DIR__ . '/incs/sessie.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

// Maak een PDO-verbinding
include_once __DIR__ . '/incs/dbConnect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Zoekterm uit GET-parameter
$search = "";
if (isset($_GET['q'])) {
    $search = trim($_GET['q']);
}

// Haal klanten op, eventueel gefilterd op naam
if ($search !== "") {
    $stmt = $pdo->prepare("SELECT id, naam FROM klanten WHERE naam LIKE ? ORDER BY naam ASC");
    $stmt->execute(["%" . $search . "%"]);
} else {
    $stmt = $pdo->query("SELECT id, naam FROM klanten ORDER BY naam ASC");
}
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = "Klant Overzicht";
$menu = 'beheer';
include_once __DIR__ . '/incs/top.php';
?>

<!DOCTYPE html>
<html>
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
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-form button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            background-color: var(--heellichtpaars);
            color: #fff;
            cursor: pointer;
        }
        .search-form button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <h2><?php echo htmlspecialchars($title); ?></h2>

        <!-- Zoekformulier -->
        <form method="GET" action="klanten_overzicht.php" class="search-form">
            <input type="text" name="q" placeholder="Zoek klantnaam..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Zoeken</button>
        </form>

        <?php if ($customers): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Klantnaam</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr onclick="window.location.href='klantForm.php?edit=1&klant_id=<?php echo urlencode($customer['id']); ?>';" style="cursor:pointer;">
                            <td><?php echo htmlspecialchars($customer['naam']); ?></td>
                            <td>
                                <a href="klantForm.php?edit=1&klant_id=<?php echo urlencode($customer['id']); ?>">Bewerken</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Geen klanten gevonden.</p>
        <?php endif; ?>
    </main>
</body>
</html>
