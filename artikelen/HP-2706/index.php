<?php

session_start();
$menu = "beheer";

$title = "Kunststof budget koptelefoon";
$TypeNummer = "HP-2706";
include "../artikelkop.php";
?>

<link rel="stylesheet" href="../prod.css">
<style>
    .grid-container {
        grid-template-areas:
            "titel titel twee twee twee twee"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "hero hero hero hero hero hero"
            "vier vier zes zes zes zes"
            "acht acht negen negen tien tien"
            "drie drie zeven zeven zeven zeven"
            "twaalf twaalf twaalf twaalf twaalf twaalf";

        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 2fr 1fr 3fr 2fr auto 2fr 4fr;
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
        <div class="twee">
            <img class='breed' src="hp-122 liggend.png" alt='hp-136 hoofdtelefoon' loading="lazy">
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


        <div class="hero">
            <img class='breed' src="front_met_kleurtjes.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="vier">
            <img class='hoog' src="122 met hoofd in oranje.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="zes"><img class='hoog' src="hp-122 staand.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>

        <div class="zeven cols2 omschrijving">
            <?php echo $omschrijving; ?>
        </div>




        <div class="twaalf">
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
