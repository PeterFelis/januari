<?php
session_start();
$menu = "beheer";

// Deze twee variabelen komen bv. uit artikelkop.php of direct uit de DB:
$omschrijving = /* HTML zoals Quill heeft opgeslagen */
    $USP = /* HTML zoals Quill heeft opgeslagen */

    $title = "HP-136 S degelijke hoofdtelefoon";
$TypeNummer = "HP-136 S";
include "../artikelkop.php";
?>

<style>
    .grid-container {
        grid-template-areas:
            "titel titel twee twee twee twee"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "drie drie vier vijf vijf vijf"
            "zes zes zeven zeven zeven zeven";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr 1fr 1fr 1fr;
        height: 200vh;
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
            <img class='hoog' src="136sfront.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee">
            <img src="hp-136S sfeerfoto.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel">
            <h1><?php echo htmlspecialchars($productType); ?></h1>
        </div>

        <div id="usp">
            <?php echo $USP; ?>
        </div>

        <div class="drie">
            <p>[bestel blok]</p>
            <p><br>
                <?php include '../prijs_component.php'; ?>
            </p>
        </div>

        <div class="vier">
            <img class='hoog' src="hp-136 zijaanzicht.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf">
            <img src="_DSC0380-Edit.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="zes">
            <img src="hp136sintasmetsticker (1).png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="zeven omschrijving">
            <?php echo $omschrijving; ?>
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