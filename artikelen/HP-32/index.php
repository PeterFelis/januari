<?php
$title = "HP-32 oortje in bewaardoosje met naamsticker";
$TypeNummer = "HP-32";
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
            "vier vier vijf vijf zes zes"
            "acht acht negen negen tien tien"
            "drie drie zeven zeven zeven zeven"
            "elf elf twaalf twaalf twaalf twaalf"
            "dertien dertien dertien dertien veertien veertien";

        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 2fr 1fr 3fr 1fr 1fr auto 2fr 2fr;
        height: 300vh;
    }
</style>
<script>
    window.isProductPage = true;
</script>

<body>
    <?php include_once __DIR__ . '/../../incs/product_selector.php'; ?>

    <article class='grid-container'>
        <div class="een">
            <img class='hoog' src="rood.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee">
            <img class='hoog' src="uitgeknipt links rects en stekker.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel">
            <h1> <?php echo htmlspecialchars($productType); ?></h1>
        </div>

        <div id="usp">
            <?php
            foreach (explode("\n", $USP) as $usp) : ?>
                <?php echo htmlspecialchars($usp); ?>
                <br>
            <?php endforeach; ?>
        </div>

        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie">
            <p>[bestel blok]
            <p> <br>
                <?php include '../prijs_component.php'; ?>
        </div>


        <div class="hero">
            <img class='breed' src="Hero.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="vier">
            <img class='hoog' src="blauw.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf"> <img class='hoog' src="geel.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
        <div class="zes"><img class='hoog' src="groen.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>

        <div class="zeven cols2">
            <?php echo $omschrijving; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="oranje.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="negen">
            <img class='hoog' src="wit.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="tien">
            <img class='hoog' src="zwart.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="elf">
            <img class='hoog' src="doos met groen.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twaalf">
            <img class='hoog' src="naamtags.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="dertien">
            <img class='breed' src="groene in zakje met rijtje stickers.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="veertien">
            <img class='hoog' src="uitgeknipt rood full view.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
    </article>

</body>
<?php
include dirname(__DIR__, 2) . "/incs/bottom.php";
