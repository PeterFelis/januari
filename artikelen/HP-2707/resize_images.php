<?php
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

// Bepaal het pad naar het logbestand
$logFile = __DIR__ . DIRECTORY_SEPARATOR . "resize_log.txt";

// Als het logbestand aanwezig is, stoppen we met uitvoeren (beveiliging)
if (file_exists($logFile)) {
    echo "Script is al uitgevoerd. Verwijder het logbestand om het opnieuw uit te voeren.";
    exit;
}

/**
 * Script: resize_to_1200.php
 * Doel:   Doorloopt alle .jpg/.png-afbeeldingen in deze map.
 *         Als een afbeelding > 1200px breed is, wordt hij verkleind naar 1200px breed.
 *         De hoogte wordt automatisch mee geschaald.
 *         Het origineel wordt OVERSCHREVEN!
 *         Na uitvoering wordt een logbestand aangemaakt met de datum en een lijst van de aangepaste bestanden.
 *
 * Gebruik: php resize_to_1200.php
 */

// Instellingen
$directory  = __DIR__;   // De map waarin je wilt zoeken naar afbeeldingen
$maxWidth   = 1200;      // Maximale breedte
$quality    = 80;        // JPEG-kwaliteit (0-100)

// Bestandsextensies die we willen bewerken
$extensions = ['jpg', 'jpeg', 'png'];

// Array om logboekvermeldingen van aangepaste bestanden op te slaan
$logEntries = [];

/**
 * Functie die een bronafbeelding verkleint naar $maxWidth (indien breder),
 * en het resultaat terugschrijft naar hetzelfde pad.
 *
 * @param string $filePath   - pad naar het originele bestand (wordt overschreven)
 * @param resource $srcImage - GD-resource van het origineel
 * @param int $origWidth     - originele breedte
 * @param int $origHeight    - originele hoogte
 * @param int $maxWidth      - gewenste maximale breedte
 * @param string $ext        - extensie ('jpg', 'png')
 * @param int $quality       - jpeg-kwaliteit (0-100)
 *
 * @return bool True als er geresized is, anders false.
 */
function resizeIfNeeded($filePath, $srcImage, $origWidth, $origHeight, $maxWidth, $ext, $quality)
{
    // Alleen resizen als de originele breedte groter is dan de maximale breedte
    if ($origWidth <= $maxWidth) {
        return false;
    }

    // Bereken de nieuwe hoogte op basis van de verhouding
    $newWidth  = $maxWidth;
    $newHeight = (int)(($maxWidth / $origWidth) * $origHeight);

    // Maak een nieuw "canvas" voor de afbeelding
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Zorg dat PNG-afbeeldingen hun transparantie behouden
    if ($ext === 'png') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
    }

    // Schaal de originele afbeelding naar de nieuwe grootte
    imagecopyresampled(
        $newImage,    // bestemming
        $srcImage,    // bron
        0,
        0,            // startpunt bestemming
        0,
        0,            // startpunt bron
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

// Verkrijg de lijst met bestanden in de map
$files = scandir($directory);

foreach ($files as $file) {
    // Filter op de gewenste bestandsextensies
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($ext, $extensions)) {
        continue;
    }

    // Volledig pad naar het bestand
    $filePath = $directory . DIRECTORY_SEPARATOR . $file;

    // Laad de afbeelding in afhankelijk van de extensie
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

    // Als de afbeelding niet ingelezen kon worden, ga verder met het volgende bestand
    if (!$srcImage) {
        continue;
    }

    // Verkrijg de originele afmetingen
    $origWidth  = imagesx($srcImage);
    $origHeight = imagesy($srcImage);

    // Probeer de afbeelding te resizen (indien breder dan $maxWidth)
    $resized = resizeIfNeeded($filePath, $srcImage, $origWidth, $origHeight, $maxWidth, $ext, $quality);

    // Opruimen van de originele GD-resource
    imagedestroy($srcImage);

    if ($resized) {
        $message = "Gerescaleerd: $file (was {$origWidth}px breed, nu {$maxWidth}px breed)";
        echo $message . "\n";
        $logEntries[] = $message;
    } else {
        echo "Geen scaling nodig: $file ({$origWidth}px)\n";
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

// Schrijf de log naar het bestand (in dezelfde map als dit script)
file_put_contents($logFile, $logContent);
