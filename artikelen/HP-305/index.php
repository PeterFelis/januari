<?php

session_start();
$menu = "beheer";

$title = "HP-305 comfort hoofdtelefoon";
$TypeNummer = "HP-305";
include "../artikelkop.php";
?>

<link rel="stylesheet" href="../prod.css">
<style>
    .grid-container {
        grid-template-areas:
            "titel titel twee twee twee twee"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "vier vier vijf vijf zes zes"
            "drie drie zeven zeven zeven zeven"
            "acht acht acht acht negen negen";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 2fr 1fr 1fr auto 3fr;
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
            orientation: "horizontal", // horizontale layout: bij productselectie redirect
            showProducts: true, // zorg dat producten worden getoond
            onSelectionChange: function(selectionData) {
                console.log("Geselecteerd:", selectionData);
            }
        });
    </script>
    <article class='grid-container'>
        <div class="een">
            <img class='hoog' src="3051.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee">
            <img class='hoog' src="305 met beest samen.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel">
            <h1> <?php echo htmlspecialchars($productType); ?></h1>
        </div>

        <div id="usp">
            <?php echo $USP; ?>
        </div>


        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie">
            <p>[bestel blok]
            <p> <br>
                <?php include '../prijs_component.php'; ?>
        </div>


        <div class="vier">
            <img class='hoog' src="3052.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf"> <img class='hoog' src="3053.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
        <div class="zes"><img class='hoog' src="3054.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>

        <div class="zeven cols2 omschrijving">
            <?php echo $omschrijving; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="3055.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="negen">
            <img class='hoog' src="hp-305 hangend uitgeknipt.png" alt='hp-136 hoofdtelefoon' loading="lazy">
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
