<?php
session_start();
$menu = "beheer";

$title = "HP-30 titanium oorjte";
// Stel de typenummers in voor het hoofdproduct en de variant met zakjes
$TypeNummerHoofd = "HP-30";
$TypeNummerZakjes = "HP-30 Pouch"; // Zorg dat dit typenummer in de database bestaat voor de variant

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
            "vier vier vier vijf vijf vijf"
            "drie drie drie zeven zeven zeven"
            "acht acht acht acht negen negen"
            "tien tien tien tien tien tien"
            "zes zes zes zes veertien veertien"
            "twaalf twaalf twaalf elf elf elf"
            "dertien dertien dertien elf elf elf";
        grid-template-rows: 1fr 2fr 2fr 4fr auto 3fr 1fr 5fr 2fr;
        height: 3000px;
    }

    /* Responsive aanpassingen per pagina */
    @media only screen and (max-width: 1024px) {
        .grid-container {
            height: 3000px;
            grid-template-areas:
                "titel titel titel titel"
                "een twee twee twee"
                "usp twee twee twee"
                "vier vier vijf vijf"
                "drie drie drie drie"
                "zeven zeven zeven zeven"
                "acht acht negen negen"
                "tien tien tien tien"
                "zes zes veertien veertien" 
                "elf elf elf elf"
                "twaalf twaalf twaalf twaalf"
                "dertien dertien dertien dertien";
            grid-template-rows: 1fr 2fr 2fr 3fr auto auto 3fr 1fr 5fr 4fr auto auto;
        }
    }

    /* Responsive aanpassingen per pagina */
    @media only screen and (max-width: 600px) {
        .grid-container {
            height: 3000px;
            font-size: 1rem;
            grid-template-rows: 1fr 1fr 1fr 1fr 1fr auto auto 1fr 1fr 1fr 1fr auto auto 1fr;
            grid-template-areas:
                "titel titel"
                "een usp "
                "twee twee"
                "vier vier"
                "vijf vijf"
                "drie drie"
                "zeven zeven"
                "acht negen"
                "tien tien "
                "zes zes"
                "veertien veertien"
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
            <img class='hoog' src="hp-30 uitgeknipt een.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee geenpad">
            <img class='breed' src="sfeer.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel oranje">
            <h1> <?php echo htmlspecialchars($mainProduct['TypeNummer']); ?></h1>
        </div>

        <div id="usp" class='oranje'>
            <?php echo $mainProduct['USP']; ?>
        </div>

        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie oranje">
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

        <div class="vier">
            <img class='hoog' src="HP-30 wit uitgeknipt.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf">
            <img class='hoog' src="hp-30 uitgeknipt twee.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="zeven omschrijving oranje col2">
            <?php echo $mainProduct['omschrijving']; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="HP-30 oorschelpen.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="negen">
            <img class='hoog' src="zakje.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="tien oranje">
            <h1> <?php echo htmlspecialchars($variantProduct['TypeNummer']); ?></h1>
        </div>

        <div class="zes geenpad">
            <img class='breed' src="hp-30 met pouch sfeerphoto.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="veertien">
            <img class='hoog' src="pouch.png" alt='HP-305 in een zakje' loading="lazy">
        </div>

        <div class="elf">
            <img class='hoog' src="hp-30 half in pouch.png" alt='HP-305 in een zakje' loading="lazy">
        </div>

        <div class="twaalf oranje omschrijving col2">
            <?php echo $variantProduct['omschrijving']; ?>
        </div>

        <div class="dertien oranje">
            <?php
            // Voor het variantproduct:
            renderPriceComponent(
                $variantProduct['prijsstaffel'],
                $variantProduct['aantal_per_doos'],
                'variant',
                $variantProduct['TypeNummer'],
                'variant'
            );
            ?>
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