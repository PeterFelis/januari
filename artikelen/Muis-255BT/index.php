<?php
session_start();
$menu = "beheer";

$title = "Muis-255 BT Bluetooth oplaadbare muis";
// Stel de typenummers in voor het hoofdproduct en de variant met zakjes
$TypeNummerHoofd = "Muis-255BT";
$TypeNummerZakjes = Null; // Zorg dat dit typenummer in de database bestaat voor de variant

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
    /* Desktop grid-indeling */
    .grid-container {
        grid-template-areas:
            "titel titel titel titel titel titel"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "drie drie zeven zeven zeven zeven"
            "vier vier vier vijf vijf vijf"
            "acht acht acht acht acht acht";
        grid-template-rows: 1fr 3fr 2fr auto 6fr 5fr;
        height: 3000px;
    }

    /* Responsive aanpassingen per pagina */
    @media only screen and (max-width: 1024px) {
        .grid-container {
            height: 3000px;
            grid-template-areas:
                "titel titel titel titel"
                "een een usp usp"
                "twee twee twee twee"
                "vier vier vijf vijf"
                "drie drie drie drie"
                "zeven zeven zeven zeven"
                "acht acht acht acht";
            grid-template-rows: 1fr 3fr 5fr 4fr auto auto 3fr 1fr;
        }
    }

    /* Responsive aanpassingen per pagina */
    @media only screen and (max-width: 600px) {
        .grid-container {
            height: 2000px;
            font-size: 1rem;
            grid-template-rows: 1fr 2fr 5fr 2fr 2fr auto auto 3fr 1fr 1fr auto 3fr;
            grid-template-areas:
                "titel titel"
                "een usp"
                "twee twee"
                "vier vier"
                "vijf vijf"
                "drie drie"
                "zeven zeven"
                "acht acht"
                "tien tien"


                "twaalf twaalf"
                "dertien dertien"
                "elf elf";
        }

        .grid-container .col2 {
            columns: 1;
        }


        h1 {
            font-size: 2.2em;
        }

        #usp {
            font-size: 1.3rem;
            line-height: 1.8rem;
        }

        p {
            font-size: 1.4rem;
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
            <img class='hoog' src="bovenaf uitgeknipt met kaartje bewerkt 15042025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee geenpad">
            <img class='breed' src="onderkant en dongle uitgeknipt en bewerkt 15042025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
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


        <div id="usp" class='oranje'>
            <?php echo $mainProduct['USP']; ?>
        </div>

        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie prijs">
            <?php
            renderPriceComponent(
                $mainProduct['prijsstaffel'],
                $mainProduct['aantal_per_doos'],
                'main',
                $mainProduct['TypeNummer'],
                'main'
            );
            ?>
        </div>

        <div class="vier nopad">
            <img class='breed' src="doosje.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf">
            <img class='breed' src="front.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="zeven omschrijving oranje col2">
            <?php echo $mainProduct['omschrijving']; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="bovenaf uitgeknipt bewerkt 15042025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>




    </article>

    <div id="lightbox-overlay" class="lightbox-overlay">
        <img id="lightbox-image" class="lightbox-image" src="" alt="Uitvergrote afbeelding">
        <!-- Include de lightbox JavaScript -->
        <script src="../lightbox.js"></script>
    </div>

    <?php include dirname(__DIR__, 2) . "/incs/bottom.php"; ?>
</body>

</html>