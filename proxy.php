<?php
// proxy.php
header('Content-Type: application/json');

$postcode = isset($_GET['postcode']) ? $_GET['postcode'] : '';
$number = isset($_GET['number']) ? $_GET['number'] : '';

$url = 'https://json.api-postcode.nl/?postcode=' . urlencode($postcode) . '&number=' . urlencode($number);

// Stel de HTTP-headers in, inclusief jouw API-token
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "token: dd2e4e4f-c54f-4297-ab85-80312b92cc65\r\n"
    ]
];

$context = stream_context_create($opts);
$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo json_encode(["error" => "Fout bij ophalen adresgegevens"]);
} else {
    echo $response;
}
?>
