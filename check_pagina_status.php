<?php
// check_pagina_status.php
header('Content-Type: application/json');

// Verwacht een JSON object met een array 'typeNummers' in de POST body
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (!isset($input['typeNummers']) || !is_array($input['typeNummers'])) {
    echo json_encode(['error' => 'Ongeldige input. Array met "typeNummers" verwacht.']);
    exit;
}

$typeNummersToCheck = $input['typeNummers'];
$results = [];

foreach ($typeNummersToCheck as $typeNummer) {
    // Zorg ervoor dat typeNummer een string is en niet leeg, voor de zekerheid
    if (is_string($typeNummer) && !empty($typeNummer)) {
        // Pad naar de index.php van het product
        // __DIR__ verwijst naar de map waarin check_pagina_status.php staat
        $productIndexPath = __DIR__ . '/artikelen/' . $typeNummer . '/index.php';
        $results[$typeNummer] = file_exists($productIndexPath);
    } else {
        // Als typeNummer ongeldig is, markeer het als niet bestaand
        if (is_string($typeNummer)) {
            $results[$typeNummer] = false;
        }
        // Optioneel: log ongeldige typenummers
    }
}

echo json_encode($results);
?>