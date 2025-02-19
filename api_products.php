<?php
// file: api_products.php
include_once __DIR__ . '/incs/dbConnect.php';

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Databaseverbinding mislukt: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        // Haal één product op via ID
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([intval($_GET['id'])]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } elseif (isset($_GET['TypeNummer'])) {
        // Haal één product op via TypeNummer
        $stmt = $pdo->prepare("SELECT * FROM products WHERE TypeNummer = ?");
        $stmt->execute([$_GET['TypeNummer']]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        // Haal alle producten op (inclusief leverbaar en foto_link)
        $stmt = $pdo->query("SELECT id, categorie, subcategorie, TypeNummer, omschrijving, sticker_text, prijsstaffel, aantal_per_doos, USP, leverbaar, foto_link FROM products ORDER BY TypeNummer");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($method === 'POST') {
    // Voeg nieuw product toe
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO products (categorie, subcategorie, TypeNummer, omschrijving, prijsstaffel, aantal_per_doos, USP, sticker_text, leverbaar, foto_link)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['categorie'],
        $data['subcategorie'],
        $data['TypeNummer'],
        $data['omschrijving'],
        $data['prijsstaffel'],
        $data['aantal_per_doos'],
        $data['USP'],
        $data['sticker_text'] ?? '',
        $data['leverbaar'] ?? 'ja',
        $data['foto_link'] ?? ''
    ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} elseif ($method === 'PUT') {
    // Update bestaand product
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("UPDATE products SET categorie = ?, subcategorie = ?, TypeNummer = ?, omschrijving = ?, prijsstaffel = ?, aantal_per_doos = ?, USP = ?, sticker_text = ?, leverbaar = ?, foto_link = ?
                           WHERE id = ?");
    $stmt->execute([
        $data['categorie'],
        $data['subcategorie'],
        $data['TypeNummer'],
        $data['omschrijving'],
        $data['prijsstaffel'],
        $data['aantal_per_doos'],
        $data['USP'],
        $data['sticker_text'] ?? '',
        $data['leverbaar'] ?? 'ja',
        $data['foto_link'] ?? '',
        intval($data['id'])
    ]);
    echo json_encode(['success' => true]);
} elseif ($method === 'DELETE') {
    // Verwijder product
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([intval($data['id'])]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Geen product id opgegeven']);
    }
} else {
    echo json_encode(['error' => 'Ongeldige methode']);
}
