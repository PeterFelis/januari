<?php
session_start();
$menu = "beheer";

// Deze twee variabelen komen bv. uit artikelkop.php of direct uit de DB:
$omschrijving = /* HTML zoals Quill heeft opgeslagen */
    $USP = /* HTML zoals Quill heeft opgeslagen */

    $title = "HP-136 S degelijke hoofdtelefoon";
$TypeNummerHoofd = "HP-136S";
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
            "titel titel titel titel titel titel"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "drie drie vijf vijf vijf vijf"
            "zes zes zeven zeven zeven zeven"
            "vier vier vier vier vier vier";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 2fr 1fr 2fr 2fr 4fr;
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
            orientation: "horizontal",
            showProducts: true,
            onSelectionChange: function(selectionData) {
                console.log("Geselecteerd:", selectionData);
            }
        });
    </script>

    <article class='grid-container'>
        <div class="een">
            <img class='hoog' src="hp-136 zijaanzicht.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee geenpad">
            <img src="hp-136S sfeerfoto.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel oranje">
            <h1> <?php echo htmlspecialchars($mainProduct['TypeNummer']); ?></h1>
        </div>

        <div id="usp">
            <?php echo $mainProduct['USP']; ?>
        </div>

        <div class="drie prijs">
            <?php
            renderPriceComponent($mainProduct['prijsstaffel'], $mainProduct['aantal_per_doos'], 'main', $mainProduct['TypeNummer']);
            ?>
        </div>

        <div class="vier">
            <img class='hoog' src="136sfront.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf geenpad">
            <img src="_DSC0380-Edit.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="zes">
            <img src="hp136sintasmetsticker (1).png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="zeven omschrijving oranje col2">
            <?php echo $mainProduct['omschrijving']; ?>
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
?>