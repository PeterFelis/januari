<?php
session_start();
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

<body>
    <?php include_once __DIR__ . '/../../incs/product_selector.php'; ?>

    <article class='grid-container'>
        <div class="een">
            <img class='hoog' src="136sfront.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee">

            <img src="hp-136S sfeerfoto.jpg" alt='hp-136 hoofdtelefoon' loading="lazy">
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

        <div class="drie">
            <?php include '../prijs_component.php'; ?>
        </div>

        <div class="vier">
            <img class='hoog' src="hp-136 zijaanzicht.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf"> <img src="_DSC0380-Edit.jpg" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
        <div class="zes"><img src="hp136sintasmetsticker (1).png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
        <div class="zeven">
            <?php echo $omschrijving; ?>
        </div>
    </article>
</body>
<?php
include dirname(__DIR__, 2) . "/incs/bottom.php";
