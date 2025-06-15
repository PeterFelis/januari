<?php
$title = "Fetum - Since 1985";
$statusbalk = "Iets bestellen? Gewoon even mailen of bellen!";
$menu = 'geen';
$menu = 'logo';
include_once __DIR__ . '/incs/top.php';
include_once __DIR__ . '/incs/statusbalk.php';

// Kies een willekeurige afbeelding uit de frontHeros map
$imagesDir = __DIR__ . '/frontHeros/';
$images = glob($imagesDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

if (!empty($images)) {
    $randomImage = basename($images[array_rand($images)]);
    $imageExtension = pathinfo($randomImage, PATHINFO_EXTENSION);

    // Check of er een .txt bestand is met dezelfde naam als de afbeelding
    $textFile = $imagesDir . pathinfo($randomImage, PATHINFO_FILENAME) . '.txt';
    $imageText = '';
    if (file_exists($textFile)) {
        // Echo de inhoud als HTML zodat je h2, br, p etc kunt gebruiken
        $imageText = file_get_contents($textFile);
    }
} else {
    $randomImage = 'placeholder.png';
    $imageExtension = 'png';
    $imageText = 'Geen tekst beschikbaar';
}
?>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <!-- Hero sectie met willekeurige afbeelding en organische tekstanimatie -->
    <div class="hero <?php echo $imageExtension; ?>"
        style="background-image: url('frontHeros/<?php echo $randomImage; ?>');">
        <?php if (!empty($imageText)) { ?>
            <div class="floating-text"><?php echo $imageText; ?></div>
        <?php } ?>
    </div>

    <!-- Content sectie met adres en PDF links -->
    <div class="content-container">
        <!-- Adres sectie -->
        <div class="adres">
            <p>
                Grote waard 36<br>
                2675 BX Honselersdijk<br>
                Tel: 0174 769 132<br>
                Verkoop@fetum.nl
            </p>
        </div>

        <!-- PDF links sectie -->
        <div class="pdf-links">
            <a href="pdf/oort.pdf" target="_blank">
                <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> oortjes
            </a>
            <a href="pdf/hoofd.pdf" target="_blank">
                <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> hoofdtelefoons
            </a>
            <a href="pdf/muis.pdf" target="_blank">
                <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> muizen
            </a>
            <a href="pdf/bt.pdf" target="_blank">
                <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> bluetooth hoofdtelefoon
            </a>
            <a href="pdf/pat.pdf" target="_blank">
                <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> toilet (patienten) etui
            </a>
        </div>
    </div>

    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>

<style>
    /* Algemene pagina-instellingen */
    html,
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        /* Voorkom horizontale scrollbalk */
        /* Zorg dat we de volledige viewport gebruiken */
        box-sizing: border-box;
        height: 100vh;
        max-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Hero sectie */
    .hero {
        width: 100%;
        /* We houden de hero op 80vh, zodat de content-container de resterende 20vh vult */
        height: 80vh;
        position: relative;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #f1f1f1;
        /* Zorg dat overloop zichtbaar is zodat de floating-text eroverheen kan liggen */
        overflow: visible;
        z-index: 1;
    }

    /* Afbeeldingen */
    .hero.jpg,
    .hero.jpeg {
        background-size: cover;
    }

    .hero.png {
        background-size: contain;
    }

    .floating-text {
        position: absolute;
        bottom: -2.5rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 2rem;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        animation: floatText 10s ease-in-out infinite;
        white-space: nowrap;
        padding: 10px 20px;
        background: var(--lichtpaars);
        border-radius: 8px;
        text-align: center;
    }

    @keyframes floatText {
        0% {
            transform: translateX(-50%) translateY(0) scale(1);
            opacity: 0.9;
        }

        25% {
            transform: translateX(-49%) translateY(1px) scale(1.01);
            opacity: 1;
        }

        50% {
            transform: translateX(-50%) translateY(0) scale(.99);
            opacity: 0.8;
        }

        75% {
            transform: translateX(-51%) translateY(-1px) scale(1);
            opacity: 0.9;
        }
    }

    /* Content container */
    .content-container {
        display: flex;
        height: 20vh;
        padding: 20px;
        background-color: #f1f1f1;
        align-items: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    /* Adres sectie */
    .adres {
        flex: 1;
        padding: 10px;
        font-size: 1.6rem;
        line-height: 1.8;
        color: #333;
    }

    /* PDF links sectie */
    .pdf-links {
        flex: 2;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        justify-content: flex-start;
        overflow-y: auto;
        padding: 10px;
        color: var(--lpaars);
    }

    .pdf-links a {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--lpaars);
        text-decoration: none;
        white-space: nowrap;
        transition: color 0.2s;
    }

    .pdf-links a:hover {
        color: black;
    }

    .pdf-icon {
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }

    /* Responsive gedrag */
    @media (max-width: 768px) {
        .content-container {
            flex-direction: column;
            height: auto;
        }

        .adres {
            text-align: center;
        }

        .pdf-links {
            justify-content: center;
        }
    }
</style>