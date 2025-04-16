<?php
session_start();
$menu = "beheer";

$title = "HP-112 comfort hoofdtelefoon 1 meter snoer";
// Stel de typenummers in voor het hoofdproduct en de variant met zakjes
$TypeNummerHoofd = "HP-112";
$TypeNummerZakjes = "HP-112Z"; // Zorg dat dit typenummer in de database bestaat voor de variant

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
<style>
    .grid-container {
        grid-template-areas:
            "titel titel titel titel titel titel"
            "twee twee twee twee twee twee"
            "een een usp usp vijf vijf "
            "een een zeven zeven zeven zeven"
            "drie drie acht acht acht acht"
            "tien tien twaalf twaalf twaalf twaalf"
            "dertien dertien elf elf elf elf"
            "dertien dertien elf elf elf elf";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 3fr 2fr 1fr 3fr 1fr 1fr 1fr;
        height: 3000px;
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
            <img class='hoog' src="HP-112 een.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee geenpad">
            <img class='breed' src="houten pc met 112.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
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
            renderPriceComponent($mainProduct['prijsstaffel'], $mainProduct['aantal_per_doos'], 'main', $mainProduct['TypeNummer'], 'main');
            ?>
        </div>


        
        <div class="vijf"> <img class='hoog' src="hp-112 twee.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>


        <div class="zeven omschrijving oranje col2">
            <?php echo $mainProduct['omschrijving']; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="hp-112 vier.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        

        <div class="tien oranje">
            <h1> <?php echo htmlspecialchars($variantProduct['TypeNummer']); ?></h1>
        </div>

        <div class="elf">
            <img class='hoog' src="HP-112 in zakje.png" alt='HP-305 in een zakje' loading="lazy">
        </div>

        <div class="twaalf omschrijving oranje">
            <?php echo $variantProduct['omschrijving']; ?>
        </div>

        <div class="dertien oranje">
            <?php
         renderPriceComponent($variantProduct['prijsstaffel'], $variantProduct['aantal_per_doos'], 'variant', $variantProduct['TypeNummer'], 'variant');
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

</html