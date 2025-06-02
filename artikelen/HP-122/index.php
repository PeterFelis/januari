<?php
session_start();
$menu = "beheer";

$title = "Nekband hoofdtelefoon met verstevigd snoer";
$TypeNummerHoofd = "HP-122";
$TypeNummerZakjes = null;
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
            "titel titel twee twee twee twee"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "elf elf elf elf elf elf"
            "acht acht acht acht acht acht"
            "zes zes zes zes zes zes"
            "drie drie zeven zeven zeven zeven"
            "twaalf twaalf twaalf twaalf twaalf twaalf";

        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 3fr 2fr 3fr 5fr 2fr auto 4fr;
        height: 300vh;
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
            <img class='hoog' src="hp-122.png" alt='hp-122.png' loading="lazy">
        </div>
        <div class="twee geenpad">
            <img class='breed' src="hp-122 liggend.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel oranje">
            <h1> <?php echo htmlspecialchars($mainProduct['TypeNummer']); ?></h1>
        </div>

        <div id="usp" class="oranje">
            <?php echo ($mainProduct['USP']); ?>
        </div>

        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie prijs">
            <?php
            renderPriceComponent($mainProduct['prijsstaffel'], $mainProduct['aantal_per_doos'], 'main', $mainProduct['TypeNummer']);
            ?>
        </div>


        <div class="acht geenpad">
            <img class='breed' src="front_met_kleurtjes.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="elf geenpad">
            <img class='hoog' src="122 met hoofd in oranje.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="zes"><img class='hoog' src="hp-122 staand.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>

        <div class="zeven omschrijving oranje col2">
            <?php echo ($mainProduct['omschrijving']); ?>
        </div>

        <div class="twaalf geenpad">
            <img class='breed' src="frontnieuwe122.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

    </article>


    <div id="lightbox-overlay" class="lightbox-overlay">
        <img id="lightbox-image" class="lightbox-image" src="" alt="Uitvergrote afbeelding">
        <!-- Include de lightbox JavaScript -->
        <script src="../lightbox.js"></script>
    </div>

</body>
<?php
include dirname(__DIR__, 2) . "/incs/bottom.php";
