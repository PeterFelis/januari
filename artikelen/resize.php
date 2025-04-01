<?php
// resize.php
session_start();

// 1) Is de gebruiker wel ingelogd?
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

// 2) Heeft de gebruiker de juiste rol? Alleen 'admin' mag bij beheer.
if ($_SESSION['role'] !== 'admin') {
    echo "Geen toegang tot deze pagina.";
    exit;
}

// De hoofdmap waarin de submappen met producten staan (dit is de huidige map, bv. "artikelen")
$parentDir = __DIR__;

// Instellingen voor het resizen
$maxWidth   = 1200;  // Maximale breedte
$quality    = 80;    // JPEG-kwaliteit (0-100)
$extensions = ['jpg', 'jpeg', 'png'];

// Functie die een afbeelding verkleint als deze breder is dan $maxWidth
function resizeIfNeeded($filePath, $srcImage, $origWidth, $origHeight, $maxWidth, $ext, $quality)
{
    // Alleen resizen als de originele breedte groter is dan de maximale breedte
    if ($origWidth <= $maxWidth) {
        return false;
    }

    // Bereken de nieuwe hoogte op basis van de verhouding
    $newWidth  = $maxWidth;
    $newHeight = (int)(($maxWidth / $origWidth) * $origHeight);

    // Maak een nieuw canvas voor de afbeelding
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Zorg dat PNG-afbeeldingen hun transparantie behouden
    if ($ext === 'png') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
    }

    // Schaal de originele afbeelding naar de nieuwe grootte
    imagecopyresampled(
        $newImage,   // bestemming
        $srcImage,   // bron
        0,
        0,        // startpunt bestemming
        0,
        0,        // startpunt bron
        $newWidth,
        $newHeight,
        $origWidth,
        $origHeight
    );

    // Overschrijf het origineel met de nieuwe afbeelding
    if ($ext === 'png') {
        // PNG-compressie: 0 = geen compressie, 9 = max compressie
        imagepng($newImage, $filePath, 9);
    } else {
        imagejpeg($newImage, $filePath, $quality);
    }

    // Ruim op
    imagedestroy($newImage);

    return true;
}

// Verkrijg de lijst met items in de hoofdmap
$items = scandir($parentDir);

foreach ($items as $item) {
    // Sla '.' en '..' over en zorg dat het een directory is
    if ($item === '.' || $item === '..') {
        continue;
    }
    $subDir = $parentDir . DIRECTORY_SEPARATOR . $item;
    if (!is_dir($subDir)) {
        continue;
    }

    // Controleer of in deze submap al een logbestand aanwezig is (dus script al uitgevoerd)
    $logFile = $subDir . DIRECTORY_SEPARATOR . "resize_log.txt";
    if (file_exists($logFile)) {
        echo "Script is al uitgevoerd in directory '$item'. Verwijder het logbestand om opnieuw uit te voeren.<br>";
        continue;
    }

    // Array om logboekvermeldingen op te slaan voor deze submap
    $logEntries = [];

    // Verkrijg de lijst met bestanden in de submap
    $files = scandir($subDir);
    foreach ($files as $file) {
        // Filter op de gewenste bestandsextensies
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, $extensions)) {
            continue;
        }

        // Volledig pad naar het bestand
        $filePath = $subDir . DIRECTORY_SEPARATOR . $file;

        // Laad de afbeelding in, afhankelijk van de extensie
        switch ($ext) {
            case 'png':
                $srcImage = @imagecreatefrompng($filePath);
                break;
            case 'jpg':
            case 'jpeg':
                $srcImage = @imagecreatefromjpeg($filePath);
                break;
            default:
                $srcImage = false;
                break;
        }

        // Als de afbeelding niet ingelezen kon worden, sla dan over
        if (!$srcImage) {
            continue;
        }

        // Verkrijg de originele afmetingen
        $origWidth  = imagesx($srcImage);
        $origHeight = imagesy($srcImage);

        // Probeer de afbeelding te resizen indien nodig
        $resized = resizeIfNeeded($filePath, $srcImage, $origWidth, $origHeight, $maxWidth, $ext, $quality);

        // Opruimen van de originele GD-resource
        imagedestroy($srcImage);

        if ($resized) {
            $message = "Gerescaleerd: $file (was {$origWidth}px breed, nu {$maxWidth}px breed)";
            echo $message . "<br>";
            $logEntries[] = $message;
        } else {
            echo "Geen scaling nodig: $file ({$origWidth}px)<br>";
        }
    }

    // Maak het logbestand met de datum en de lijst van aangepaste bestanden
    $logContent = "Uitgevoerd op: " . date("Y-m-d H:i:s") . "\n";
    $logContent .= "Aangepaste bestanden:\n";
    if (!empty($logEntries)) {
        $logContent .= implode("\n", $logEntries);
    } else {
        $logContent .= "Geen wijzigingen uitgevoerd.";
    }

    // Schrijf de log naar het bestand in de submap
    file_put_contents($logFile, $logContent);
    echo "Afbeeldingen in directory '$item' verwerkt.<br><br>";
}
