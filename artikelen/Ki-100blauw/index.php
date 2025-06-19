<?php
session_start();
$menu = "beheer";

$title = "Ki 100 blauw kinder hoofdtelefoon";
// Stel de typenummers in voor het hoofdproduct en de variant met zakjes
$TypeNummerHoofd = "Ki-100blauw";
$TypeNummerZakjes = null; // Zorg dat dit typenummer in de database bestaat voor de variant

// Include het artikelkop.php script voor databaseverbinding en de getProductData functie
include_once __DIR__ . '/../../incs/artikelkop.php';


// Haal de productdata op
$mainProduct = getProductData($TypeNummerHoofd, $pdo);
$variantProduct = getProductData($TypeNummerZakjes, $pdo);

// Zorg dat er altijd een array met data beschikbaar is
if (!$mainProduct) {
    $mainProduct = [
        'TypeNummer'    => 'Onbekend type',
        'USP'           => 'Onbekende USP',
        'omschrijving'  => 'Onbekende omschrijving',
        'prijsstaffel'  => 'Onbekende prijsstaffel',
        'aantal_per_doos' => 0
    ];
}
if (!$variantProduct) {
    $variantProduct = [
        'TypeNummer'    => 'Onbekend type',
        'USP'           => 'Onbekende USP',
        'omschrijving'  => 'Onbekende omschrijving',
        'prijsstaffel'  => 'Onbekende prijsstaffel',
        'aantal_per_doos' => 0
    ];
}
// Zorg ervoor dat de renderPriceComponent functie beschikbaar is:
include '../prijs_component.php';  // pas het pad aan als dat nodig is

// Controleer of er een PDF in deze directory staat
$pdfBestanden = glob("*.pdf");
$pdfLink = '';

if (!empty($pdfBestanden)) {
    // Neem de eerste PDF die gevonden wordt
    $pdfLink = basename($pdfBestanden[0]);
}

?>


<link rel="stylesheet" href="../prod.css">
<link rel="stylesheet" href="../responsive.css">
<style>
    .grid-container {
        grid-template-areas:
            "titel titel titel titel titel titel"
            "een een twee twee twee twee"
            "een een twee twee twee twee"
            "een een twee twee twee twee"
            "drie drie zeven zeven zeven zeven"
            "acht acht acht acht acht acht"
            "negen negen negen negen negen negen"
            "usp usp usp usp usp usp";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 2fr 2fr 2fr auto 3fr 3fr;
        height: 2500px;
    }

    /* ==================== TABLET  (max-width 1024 px) ==================== */
    @media only screen and (max-width:1024px) {
        .grid-container {
            /* 4 kolommen, iets minder hoogte */
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 1fr 3fr auto auto 2fr 1fr auto;
            max-height: 2500px;

            /*
      Titel bovenaan, daaronder twee afbeeldingen naast elkaar (3 rijen hoog),
      prijs + omschrijving op één rij, grote liggende foto full-width,
      USP-blok onderaan.
    */
            grid-template-areas:
                "titel titel titel titel"
                "een twee twee twee"
                "drie drie drie drie"
                "zeven zeven zeven zeven"
                "acht acht acht acht"
                "usp usp usp usp"
                "negen negen negen negen";

            /* laat de content de hoogte bepalen */
        }
    }

    /* ==================== MOBIEL  (max-width 600 px) ==================== */
    @media only screen and (max-width:600px) {
        .grid-container {
            /* 2 kolommen, alles vrijwel full-width gestapeld */
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: 1fr 3fr 3fr auto auto 4fr 2fr 1fr;
            max-height: 2000px;
            /*
      Eerst titel + USP (dicht bij elkaar),
      dan afbeeldingen, prijs, tekst, liggende foto.
      Elk blok vult de volle breedte (2 kolommen).
    */
            grid-template-areas:
                "titel titel"
                "een  een"
                "twee twee"
                "drie drie"
                "zeven zeven"
                "acht  acht"
                "negen negen"
                "usp  usp";
            gap: 0.8rem;
            /* wat compacter op mobiel */
            height: auto;
        }

        /* Één tekstkolom op mobiel-screens */
        .col2 {
            column-count: 1 !important;
        }
    }
</style>

<script>
    window.isProductPage = true;
</script>

<body>
    <?php include_once __DIR__ . '/../../incs/menu.php'; ?>

    <div id="selectionComponent"></div>
    <script src="/incs/selection_component.js"></script>

    <script>
        var selection = new SelectionComponent({
            container: document.getElementById('selectionComponent'),
            orientation: "horizontal", // horizontale layout: bij productselectie redirect
            showProducts: true, // zorg dat producten worden getoond
            onSelectionChange: function(selectionData) {
                console.log("Geselecteerd:", selectionData);
            }
        });
    </script>


    <article class='grid-container'>
        <div class="een">
            <img class='hoog' src="blauw linkekant hangend uitgeknipt bewerkt 16032025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee">
            <img class='hoog' src="blauw front uitgeknipt bewerkt 13032025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel oranje">
            <h1 style="display: inline-block;"><?php echo htmlspecialchars($mainProduct['TypeNummer']); ?></h1>

            <?php if ($pdfLink): ?>
                <span class="pdf-download">
                    <a href="<?= htmlspecialchars($pdfLink); ?>" target="_blank">
                        <img class="pdf-icon2" src="/afbeeldingen/pdf.svg" alt="PDF"> download de PDF
                    </a>
                </span>
            <?php endif; ?>
        </div>





        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie prijs">
            <?php
            renderPriceComponent($mainProduct['prijsstaffel'], $mainProduct['aantal_per_doos'], 'main', $mainProduct['TypeNummer']);
            ?>
        </div>





        <div class="zeven omschrijving oranje col2">
            <?php echo $mainProduct['omschrijving']; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="front met doosje uitgeknipt bewerkt 13032025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>


        <div class="negen geenpad">
            <img class='breed' src="liggend uitgeknipt groot.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div id="usp" class='oranje'>
            <?php echo $mainProduct['USP']; ?>
        </div>


    </article>


    <div id="lightbox-overlay" class="lightbox-overlay">
        <img id="lightbox-image" class="lightbox-image" src="" alt="Uitvergrote afbeelding">
        <!-- Include de lightbox JavaScript -->
        <script src="../lightbox.js"></script>
    </div>


    <?php include dirname(__DIR__, 2) . "/incs/bottom.php"; ?>
</body>

</html