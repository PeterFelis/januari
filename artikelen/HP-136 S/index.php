<?php
session_start();
$title = "HP-136 S degelijke hoofdtelefoon";
$TypeNummer = "HP-136 S";
include dirname(__DIR__, 2) . "/incs/top.php";
include dirname(__DIR__, 2) . "/incs/dbConnect.php";

// Database query om producttype op te halen
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Haal het producttype op aan de hand van de productnaam
    $stmt = $pdo->prepare("SELECT TypeNummer, USP, omschrijving, prijsstaffel FROM products WHERE TypeNummer = ?");
    $stmt->execute([$TypeNummer]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $productType = $product ? $product['TypeNummer'] : 'Onbekend type';
    $USP = $product ? $product['USP'] : 'Onbekende USP';
    $omschrijving = $product ? $product['omschrijving'] : 'Onbekende omschrijving';
    $prijsstaffel = $product ? $product['prijsstaffel'] : 'Onbekende prijsstaffel';
} catch (PDOException $e) {
    $productType = 'Fout bij ophalen producttype';
}
?>
<link rel="stylesheet" href="../prod.css">
<style>
    .grid-container {
        padding: 4rem;
        display: grid;
        grid-template-areas:
            "titel titel twee twee twee twee"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "drie drie vier vijf vijf vijf"
            "zes zes zeven zeven zeven zeven";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr 1fr 1fr 1fr;
        height: 200vh;
        gap: 20px;
    }

    .grid-container>div {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        color: white;
        border-radius: 12px;
        overflow: hidden;
    }

    .grid-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .grid-container .hoog {
        width: auto;
        max-height: 100%;
    }

    .een {
        grid-area: een;
        background-color: white;
    }

    .twee {
        grid-area: twee;
        background-color: white;
    }

    .titel {
        grid-area: titel;
        background-color: #EC7D10;

    }

    .usp {
        grid-area: usp;
        background-color: #2E5266;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        text-align: left;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }


    .drie {
        grid-area: drie;
        background-color: #879DAA;
    }

    .vier {
        grid-area: vier;
        background-color: white
    }

    .vijf {
        grid-area: vijf;
        background-color: #9FB1BC;
    }

    .zes {
        grid-area: zes;
        background-color: #D3D0CB;
    }

    .grid-container>.zeven {
        grid-area: zeven;
        background-color: #E2C044;
        font-size: 18;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Zorgt dat de inhoud bovenaan blijft */
        align-items: flex-start;
        /* Zorgt dat de inhoud links uitgelijnd wordt */
        text-align: left;
        /* Zorgt dat de tekst links uitgelijnd is */
        line-height: 1.2;
    }
</style>


<?php include_once __DIR__ . '/../../incs/product_selector.php'; ?>

<body class='grid-container'>
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
        <?php
        foreach (explode("\n", $prijsstaffel) as $prijsstaffel) : ?>
            <?php echo htmlspecialchars($prijsstaffel); ?>
            <br>
        <?php endforeach; ?>
    </div>
    <div class="vier">
        <img class='hoog' src="hp-136 zijaanzicht.png" alt='hp-136 hoofdtelefoon' loading="lazy">
    </div>
    <div class="vijf"> <img src="_DSC0380-Edit.jpg" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
    <div class="zes"><img src="hp136sintasmetsticker (1).png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
    <div class="zeven">
        <?php echo $omschrijving; ?>
    </div>

</body>
<?php
include dirname(__DIR__, 2) . "/incs/bottom.php";
