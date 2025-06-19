<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/incs/sessie.php'; 
include_once __DIR__ . '/incs/dbConnect.php';

header('Content-Type: application/json');

function safe_json($data)
{
    return json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE);
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo safe_json(['error' => 'Databaseverbinding mislukt: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([intval($_GET['id'])]);
        echo safe_json($stmt->fetch(PDO::FETCH_ASSOC));
    } elseif (isset($_GET['TypeNummer'])) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE TypeNummer = ?");
        $stmt->execute([$_GET['TypeNummer']]);
        echo safe_json($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT id, categorie, subcategorie, TypeNummer, omschrijving, sticker_text, prijsstaffel, aantal_per_doos, USP, leverbaar, hoofd_product FROM products ORDER BY TypeNummer");
        echo safe_json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO products (categorie, subcategorie, TypeNummer, omschrijving, prijsstaffel, aantal_per_doos, USP, sticker_text, leverbaar, hoofd_product)
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
        $data['hoofd_product'] ?? ''
    ]);
    echo safe_json(['success' => true, 'id' => $pdo->lastInsertId()]);
} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("UPDATE products SET categorie = ?, subcategorie = ?, TypeNummer = ?, omschrijving = ?, prijsstaffel = ?, aantal_per_doos = ?, USP = ?, sticker_text = ?, leverbaar = ?, hoofd_product = ?
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
        $data['hoofd_product'] ?? '',
        intval($data['id'])
    ]);
    echo safe_json(['success' => true]);
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([intval($data['id'])]);
        echo safe_json(['success' => true]);
    } else {
        echo safe_json(['error' => 'Geen product id opgegeven']);
    }
} else {
    echo safe_json(['error' => 'Ongeldige methode']);
}
