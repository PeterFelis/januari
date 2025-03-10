<?php
//file: createAdmin.php

include_once __DIR__ . '/incs/sessie.php';
include_once __DIR__ . '/incs/dbConnect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Eerst een bedrijf (klanten record) aanmaken
$company_name = "Fetum Company";
$straat = "Hoofdstraat";
$nummer = "1";
$postcode = "1234AB";
$plaats = "Amsterdam";
$extra_veld = NULL;
$algemeen_telefoonnummer = NULL;
$algemene_email = NULL;
$website = "http://fetum.nl";
$factuur_email = NULL;
$factuur_extra_info = NULL;
$factuur_straat = NULL;
$factuur_nummer = NULL;
$factuur_postcode = NULL;
$factuur_plaats = NULL;
$aflever_straat = NULL;
$aflever_nummer = NULL;
$aflever_postcode = NULL;
$aflever_plaats = NULL;

$sql = "INSERT INTO klanten 
    (naam, straat, nummer, postcode, plaats, extra_veld, algemeen_telefoonnummer, algemene_email, website, 
     factuur_email, factuur_extra_info, factuur_straat, factuur_nummer, factuur_postcode, factuur_plaats, 
     aflever_straat, aflever_nummer, aflever_postcode, aflever_plaats)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $company_name,
    $straat,
    $nummer,
    $postcode,
    $plaats,
    $extra_veld,
    $algemeen_telefoonnummer,
    $algemene_email,
    $website,
    $factuur_email,
    $factuur_extra_info,
    $factuur_straat,
    $factuur_nummer,
    $factuur_postcode,
    $factuur_plaats,
    $aflever_straat,
    $aflever_nummer,
    $aflever_postcode,
    $aflever_plaats
]);
$klant_id = $pdo->lastInsertId();

// Vervolgens de admin user aanmaken, gekoppeld aan het bedrijf
$voornaam = "Peter";
$achternaam = "Felis";
$geslacht = "M"; // Aangenomen dat de admin mannelijk is
$email = "peter@felis.nl";
$plaintext_password = "Joop";
$hashed_password = password_hash($plaintext_password, PASSWORD_BCRYPT);
$rol = "admin";
$email_confirmed = 1;
$confirmation_token = NULL;

$sql_user = "INSERT INTO users 
    (voornaam, achternaam, geslacht, email, wachtwoord, rol, email_confirmed, confirmation_token, klant_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute([
    $voornaam,
    $achternaam,
    $geslacht,
    $email,
    $hashed_password,
    $rol,
    $email_confirmed,
    $confirmation_token,
    $klant_id
]);

echo "Admin user and company created successfully.";
