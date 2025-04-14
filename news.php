<?php
include_once __DIR__ . '/incs/sessie.php';
$directory = 'nieuws/'; // map waar de nieuwsbestanden staan
$files = [];

// Functie om de datum uit een bestand te halen
function extractDateFromFile($filepath)
{
    $content = file_get_contents($filepath);
    // Zoek naar de datum in de vorm 'Datum: dd-mm-jjjj'
    if (preg_match('/Datum:\s*(\d{2}-\d{2}-\d{4})/', $content, $matches)) {
        $date = DateTime::createFromFormat('d-m-Y', $matches[1]);
        if ($date) {
            return $date->getTimestamp();
        }
    }
    // Als er geen datum gevonden wordt, gebruik dan de file modification time als fallback
    return filemtime($filepath);
}

// Controleer of de directory bestaat
if (is_dir($directory)) {
    // Lees alle bestanden in de map
    foreach (scandir($directory) as $file) {
        // Filter enkel .html-bestanden en negeer '.' en '..'
        if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'html') {
            $files[] = $file;
        }
    }
    // Sorteer de bestanden op basis van de datum in het bestand (nieuwste eerst)
    usort($files, function ($a, $b) use ($directory) {
        $timestampA = extractDateFromFile($directory . $a);
        $timestampB = extractDateFromFile($directory . $b);
        return $timestampB - $timestampA;
    });
} else {
    echo "Nieuws directory bestaat niet.";
    exit;
}

$title = "Nieuws - Fetum";
$menu = 'beheer';
include_once __DIR__ . '/incs/top.php';
?>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>



    <section class="bovenlicht">
        <div class="bovenlicht__wrapper">
            <div class="bovenlicht__text">
                <h1>Nieuws</h1>
                <h3>
                    Blijf geïnformeerd </h3>
            </div>
            <div class="bovenlicht__image" style="background-image: url('afbeeldingen/muis.png');">
                <!-- Inhoud, indien nodig -->
            </div>
        </div>
    </section>




    <!-- Moderne styling met CSS Columns -->
    <style>
        /* CSS Columns voor nieuwsitems (masonry-effect) */
        .news-container {
            column-count: 3;
            column-gap: 20px;
            padding: 20px 0;
            max-width: 1200px;
            margin: 10rem auto;
        }

        /* Responsieve aanpassingen voor nieuws */
        @media (max-width: 768px) {
            .news-container {
                column-count: 2;
                padding: 15px;
                margin: 8rem auto;
            }
        }

        @media (max-width: 480px) {
            .news-container {
                column-count: 1;
                padding: 10px;
                margin: 6rem auto;
            }
        }

        /* Nieuwsitem styling, zodat items niet opgesplitst worden tussen kolommen */
        .news-item {
            break-inside: avoid;
            -webkit-column-break-inside: avoid;
            margin-bottom: 20px;
        }

        /* Nieuwsbericht styling met flexbox */
        .news-post {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            min-height: 250px;
        }

        /* Hover- en focus-effecten */
        .news-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .news-post:focus {
            outline: 2px solid var(--paars);
            outline-offset: 2px;
        }

        /* Titel binnen het nieuwsbericht */
        .news-post h2 {
            margin-top: 0;
            font-size: 2rem;
            color: var(--paars);
        }

        /* Responsieve titelgrootte */
        @media (max-width: 480px) {
            .news-post h2 {
                font-size: 1.5rem;
            }
        }

        /* Inhoud neemt zoveel ruimte als mogelijk in */
        .news-content {
            flex-grow: 1;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* Datum styling: staat aan de onderkant van de kaart */
        .news-date {
            font-size: 0.9rem;
            color: #555;
            text-align: right;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        @media (max-width: 480px) {
            .news-date {
                font-size: 0.85rem;
            }
        }
    </style>
    </head>



    <!-- Container voor nieuwsitems met CSS Columns -->
    <div class="news-container">
        <?php foreach ($files as $file): ?>
            <div class="news-item">
                <?php include($directory . $file); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include_once __DIR__ . '/incs/bottom.php'; ?>