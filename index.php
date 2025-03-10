<?php
// file: index.php
// startpagina website Fetum
// 05-03-2025

include_once __DIR__ . '/incs/sessie.php';
$title = "Fetum - Since 1985";
$statusbalk = "Iets bestellen? Gewoon even mailen of bellen!";
$menu = 'geen';
$menu = 'logo;';

$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
include_once __DIR__ . '/incs/statusbalk.php';

// Kies een willekeurige afbeelding uit de frontHeros map en toon deze
$imagesDir = __DIR__ . '/frontHeros/';
$images = glob($imagesDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

if (!empty($images)) {
    $randomImage = basename($images[array_rand($images)]);
    $imageExtension = strtolower(pathinfo($randomImage, PATHINFO_EXTENSION));

    // Check of er een .txt bestand is met dezelfde naam als de afbeelding
    $textFile = $imagesDir . pathinfo($randomImage, PATHINFO_FILENAME) . '.txt';
    $imageText = '';
    if (file_exists($textFile)) {
        $imageText = file_get_contents($textFile);
    }
} else {
    $randomImage = 'placeholder.png';
    $imageExtension = 'png';
    $imageText = 'Geen tekst beschikbaar';
}

// Stel de achtergrondgrootte van de foto in, afhankelijk van de extensie
if ($imageExtension === 'png') {
    $bgSize = "background-size: auto 100%;";
} else {
    $bgSize = "background-size: 100% auto;";
}
?>



<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <!-- Hero Section: Volledige breedte en 75% hoogte -->
    <section class="hero-section">
        <div class="hero-image" style="background-image: url('frontHeros/<?php echo $randomImage; ?>'); <?php echo $bgSize; ?> "></div>
        <?php if (!empty($imageText)) { ?>
            <div class="floating-text"><?php echo $imageText; ?></div>
        <?php } ?>
    </section>

    <main>
        <!-- PDF Downloads Strip: Horizontale layout -->
        <section class="pdf-strip">
            <ul>
                <li>
                    <a href="pdf/oort.pdf" target="_blank">
                        <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> oortjes
                    </a>
                </li>
                <li>
                    <a href="pdf/hoofd.pdf" target="_blank">
                        <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> hoofdtelefoons
                    </a>
                </li>
                <li>
                    <a href="pdf/muis.pdf" target="_blank">
                        <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> muizen
                    </a>
                </li>
                <li>
                    <a href="pdf/bt.pdf" target="_blank">
                        <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> bluetooth hoofdtelefoon
                    </a>
                </li>
                <li>
                    <a href="pdf/pat.pdf" target="_blank">
                        <img class="pdf-icon" src="afbeeldingen/pdf.svg" alt="PDF"> toilet (patienten) etui
                    </a>
                </li>
            </ul>
        </section>
    </main>

    <section>
        <?php include __DIR__ . '/incs/random_products.php'; ?>

        <!-- Onderste balk met adres -->
        <div class="bottom-bar">
            <div class="adres">
                <p>
                    Grote waard 36<br>
                    2675 BX Honselersdijk<br>
                    Tel: 0174 769 132<br>
                    Verkoop@fetum.nl
                </p>
            </div>
        </div>
    </section>
    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>

<style>
    /* Algemene instellingen */
    body {
        display: flex;
        flex-direction: column;
        width: 100vw;
    }

    /* Hero Section: Volledige breedte en 75% van de viewporthoogte */
    .hero-section {
        width: 100vw;
        min-height: 90vh;
        position: relative;
    }

    .hero-section .hero-image {
        width: 100%;
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
    }

    .floating-text {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 60%;
        font-size: 2rem;
        background-color: rgba(255, 255, 255, 0.8);
        color: var(--paars);
        padding: 10px 20px;
        text-align: center;
        white-space: nowrap;
        border-radius: 16px;
    }

    /* PDF Downloads Strip: Horizontale layout */
    .pdf-strip {
        background-color: var(--superlichtpaars);
        padding: 10px 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pdf-strip ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pdf-strip li {
        margin: 0 15px;
    }

    .pdf-strip a {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: var(--paars);
        font-size: 1.8rem;
        transition: color 0.2s;
    }

    .pdf-strip a:hover {
        color: black;
    }

    .pdf-icon {
        width: 20px;
        height: 20px;
    }

    /* Onderste balk met adres */
    .bottom-bar {
        padding: 20px;
        text-align: center;
    }

    .adres {
        font-size: 1.8rem;
        color: #333;
    }

    /* Responsive gedrag voor kleinere schermen */
    @media (max-width: 768px) {
        .hero-section {
            height: 50vh;
        }
    }
</style>