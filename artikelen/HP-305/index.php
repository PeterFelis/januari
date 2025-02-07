<?php
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
        grid-template-rows: 1fr 1fr 1fr 1fr 1fr 3fr;
        height: 200vh;
    }
</style>

<body>
    <?php include_once __DIR__ . '/../../incs/product_selector.php'; ?>

    <article class='grid-container'>
        <div class="een">
            <img class='hoog' src="3051.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee">
            <img class='breed' src="305 met beest samen.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel">
            <h1> <?php echo htmlspecialchars($productType); ?></h1>
        </div>

        <div class="usp">
            <?php
            foreach (explode("\n", $USP) as $usp) : ?>
                <?php echo htmlspecialchars($usp); ?>
                <br>
            <?php endforeach; ?>
        </div>

        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie">
            <?php include '../prijs_component.php'; ?>
        </div>


        <div class="vier">
            <img class='hoog' src="3052.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf"> <img class='hoog' src="3053.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
        <div class="zes"><img class='hoog' src="3054.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>

        <div class="zeven">
            <?php echo $omschrijving; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="3055.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="negen">
            <img class='hoog' src="hp-305 hangend uitgeknipt.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
    </article>

</body>
<?php
include dirname(__DIR__, 2) . "/incs/bottom.php";
