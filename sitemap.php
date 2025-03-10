<?php
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Bepaal de basis-URL op basis van de huidige host en protocol
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

// Haal het pad op naar de huidige directory (bijvoorbeeld de root van je website)
$directory = realpath('.');

// Functie om de directory recursief te scannen
function scanDirectory($dir, $base_url, $ignore = array('.', '..', 'sitemap.xml', 'confirm.php')) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    $urls = array();
    foreach ($files as $file) {
        if ($file->isFile()) {
            $filename = $file->getFilename();
            // Sla bestanden over die je niet in de sitemap wilt opnemen
            if (in_array($filename, $ignore)) {
                continue;
            }
            // Filter op bestandsextensies (aanpassen indien nodig)
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, array('php', 'html', 'htm'))) {
                $filePath = $file->getRealPath();
                // Bepaal het relatieve pad ten opzichte van de root-directory
                $relativePath = str_replace($dir, '', $filePath);
                $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
                // Bouw de volledige URL op
                $urls[] = $base_url . $relativePath;
            }
        }
    }
    return $urls;
}

// Verkrijg alle URL's uit de huidige directory
$allUrls = scanDirectory($directory, $base_url);

// Genereer de XML-output voor elke URL
foreach ($allUrls as $url) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars($url) . '</loc>';
    echo '<changefreq>monthly</changefreq>'; // Pas dit eventueel aan per pagina
    echo '<priority>0.8</priority>';         // Pas dit eventueel aan per pagina
    echo '</url>';
}

echo '</urlset>';
?>
