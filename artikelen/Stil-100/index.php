<?php
session_start();
$menu = "beheer";

$title = "Stilte koptelefoon";
// Stel de typenummers in voor het hoofdproduct en de variant met zakjes
$TypeNummerHoofd = "Stil-100";
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

?>
<link rel="stylesheet" href="../prod.css">
<link rel="stylesheet" href="../responsive.css">
<style>
    .grid-container {
        grid-template-areas:
            "titel titel titel titel titel titel"
            "twee twee twee twee twee twee"
            "usp usp usp usp usp usp"
            "vier vier vier vier vijf vijf"
            "drie drie zeven zeven zeven zeven";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 3fr 1fr 2fr auto;
        height: 2000px;
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

        <div class="twee">
            <img class='hoog' src="demper zijkant op hoofd bewerkt uitgeknipt en achtergrond 16032025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel oranje">
            <h1> <?php echo htmlspecialchars($mainProduct['TypeNummer']); ?></h1>
        </div>

        <div id="usp" class='oranje'>
            <?php echo $mainProduct['USP']; ?>
        </div>


        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie prijs">
            <?php
            renderPriceComponent($mainProduct['prijsstaffel'], $mainProduct['aantal_per_doos'], 'main', $mainProduct['TypeNummer']);
            ?>
        </div>


        <div class="vier">
            <img class='hoog' src="demper groen liggen bewerkt 16032025.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf"> <img class='hoog' src="driekwart op hoofd bewerkt uitgeknipt 16032025.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>


        <div class="zeven omschrijving oranje col2">
            <?php echo $mainProduct['omschrijving']; ?>
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