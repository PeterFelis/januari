<?php
// file: index.php
// startpagina website Fetum
// 05-03-2025

include_once __DIR__ . '/incs/sessie.php';
$title = "Fetum - Since 1985";
$statusbalk = "Iets bestellen? Gewoon even mailen of bellen!";
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
include_once __DIR__ . '/incs/statusbalk.php';

// Kies een willekeurige afbeelding uit de frontHeros map en toon deze
$imagesDir = __DIR__ . '/frontHeros/';
$images = glob($imagesDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

$imageText = '';
if (!empty($images)) {
    $randomImage = basename($images[array_rand($images)]);
    $imageExtension = strtolower(pathinfo($randomImage, PATHINFO_EXTENSION));

    // Check of er een .txt bestand is met dezelfde naam als de afbeelding
    $textFile = $imagesDir . pathinfo($randomImage, PATHINFO_FILENAME) . '.txt';
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

// Check of $imageText begint met 'over'
$overLayout = false;
if (!empty($imageText)) {
    // Splits op spaties
    $words = explode(' ', trim($imageText));
    // Check eerste woord
    if (isset($words[0]) && strtolower($words[0]) === 'over') {
        $overLayout = true;
        // Verwijder het eerste woord "over" uit de tekst
        array_shift($words);
        $imageText = implode(' ', $words);
    }
}
?>

<title><?php echo $title; ?></title>
<style>
    /* Algemene instellingen */
    body {
        display: flex;
        flex-direction: column;
        width: 100vw;
        margin: 0;
        padding: 0;
    }

    /* Hero Section vult de volledige breedte, position: relative voor absolute elementen */
    .hero-section {
        width: 100%;
        min-height: 90vh;
        position: relative;
        overflow: hidden;
    }

    /* Hero-image: standaard 100% breed, tenzij .has-text (2/3 breed) */
    .hero-image {
        width: 100%;
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        /* of contain, naar smaak */
    }

    .hero-image.has-text {
        width: 66.66%;
    }

    /* Standaard arrow-box: positie rechts, pijl wijst naar links */
    .arrow-box {
        position: absolute;
        bottom: 25%;
        /* ongeveer 1/4 vanaf de onderkant */
        right: 10%;
        width: auto;
        background: rgba(33, 150, 243, 0.6);
        color: #fff;
        padding: 20px;
        font-size: 1.6rem;
        text-align: left;
        border-radius: 8px;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
        z-index: 2;
    }

    .arrow-box::after {
        content: "";
        position: absolute;
        left: -20px;
        /* pijl aan de linkerkant */
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-top: 20px solid transparent;
        border-bottom: 20px solid transparent;
        border-right: 20px solid rgba(33, 150, 243, 0.6);
    }

    /* Voor over-layout: geen pijl en andere positie/breedte */
    .arrow-box.over-layout {
        bottom: 40%;
        right: 20%;
        width: 25%;
    }

    .arrow-box.over-layout::after {
        content: none;
    }

    /* PDF Downloads Strip: Horizontale layout */


    .pdf-strip {
        padding: 10px 0;
        display: flex;
        justify-content: center;
        align-items: space-between;
        width: 90%;
        margin: 0 auto;
    }

    .pdf-strip ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .pdf-strip li {
        margin: 0 15px;
        /* event. text-align: center; */
    }

    /* Tablet (tot 1024px) → 2 kolommen, 3 items per rij */
    @media (max-width: 1024px) {
        
        .pdf-strip ul {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(1, 1fr);
            justify-items: left;
        }

        .pdf-strip li {
            margin: 0;
        }
    }

    .pdf-strip a {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: var(--paars);
        font-size: 1.6rem;
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
            min-height: 50vh;
        }

        .arrow-box,
        .arrow-box.over-layout {
            right: 5%;
            bottom: 10%;
            font-size: 1.2rem;
        }


        

    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <?php
        // Bepaal extra class voor de hero-image
        // Als er tekst is en NIET 'over' → class .has-text (2/3 breed)
        // Anders volledige breedte
        $heroClass = 'hero-image';
        if (!empty($imageText) && !$overLayout) {
            $heroClass .= ' has-text';
        }
        ?>
        <div class="<?php echo $heroClass; ?>"
            style="background-image: url('frontHeros/<?php echo $randomImage; ?>'); <?php echo $bgSize; ?>">
        </div>

        <?php if (!empty($imageText)) : ?>
            <?php if ($overLayout): ?>
                <!-- Over-layout: box zonder pijl -->
                <div class="arrow-box over-layout">
                    <?php echo $imageText; ?>
                </div>
            <?php else: ?>
                <!-- Standaard layout: box met pijl -->
                <div class="arrow-box">
                    <?php echo $imageText; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    
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
    <section>
        <?php include __DIR__ . '/incs/random_products.php'; ?>

        <!-- Onderste balk met adres -->
        <div class="bottom-bar">
            <div class="adres">
                <p>
                    Fetum<br>
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

</html>