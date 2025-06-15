<?php
session_start();
$menu = "beheer";

$title = "HP-2710 budget hoofdtelefoon in vrolijke kleurtjes";
// Stel de typenummers in voor het hoofdproduct en de variant met zakjes
$TypeNummerHoofd = "HP-2710";
$TypeNummerZakjes = "HP-2710Z"; // Zorg dat dit typenummer in de database bestaat voor de variant

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

// Controleer of er een PDF in deze directory staat
$pdfBestanden = glob("*.pdf");
$pdfLink = '';

if (!empty($pdfBestanden)) {
    // Neem de eerste PDF die gevonden wordt
    $pdfLink = basename($pdfBestanden[0]);
}


?>
<link rel="stylesheet" href="../prod.css">
<link rel="stylesheet" href="../responsive.css">
<style>
    .grid-container {
        grid-template-areas:
            "een een titel titel titel titel"
            "een een twee twee twee twee"
            "usp usp twee twee twee twee"
            "video video video video video video"
            "vier vier vijf vijf zes zes"
            "drie drie zeven zeven zeven zeven"
            "acht acht acht acht negen negen"
            "tien tien tien tien tien tien"
            "twaalf twaalf elf elf elf elf"
            "dertien dertien elf elf elf elf";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 2fr 2fr 4fr 2fr auto 4fr 1fr 2fr 2fr;
        height: 3500px;
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
            <img class='hoog' src="blauw.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="twee">
            <img class='hoog' src="_DSC3430.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="titel oranje">
            <h1 style="display: inline-block;"><?php echo htmlspecialchars($mainProduct['TypeNummer']); ?></h1>

            <?php if ($pdfLink): ?>
                <span class="pdf-download">
                    <a href="<?= htmlspecialchars($pdfLink); ?>" target="_blank">
                        <img class="pdf-icon2" src="/afbeeldingen/pdf.svg" alt="PDF"> download de PDF
                    </a>
                </span>
            <?php endif; ?>
        </div>

        <div id="usp" class='oranje'>
            <?php echo $mainProduct['USP']; ?>
        </div>


        <!-- In vak drie (of een andere gewenste grid area) gebruik je nu de prijscomponent -->
        <div class="drie prijs">
            <?php
            renderPriceComponent($mainProduct['prijsstaffel'], $mainProduct['aantal_per_doos'], 'main', $mainProduct['TypeNummer']);
            ?>
        </div>


        <div class="video" id="player-container">
            <div id="player"></div>
        </div>
        <script>
            // 1. Laad de IFrame API
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            var player;

            function onYouTubeIframeAPIReady() {
                player = new YT.Player('player', {
                    videoId: 'fUiqolkanmw',
                    playerVars: {
                        rel: 0, // suppress “andere video’s” (zo veel als mogelijk)
                        modestbranding: 1, // minder YouTube-logo
                        controls: 1,
                        // autoplay: 1, // als je direct wilt laten starten; niet altijd gewenst
                    },
                    events: {
                        onReady: function(event) {
                            // Probeer direct hogere kwaliteit in te stellen
                            // Opties: 'hd1080', 'hd720', 'large', 'medium', etc.
                            // YouTube kiest wat mogelijk is.
                            event.target.setPlaybackQuality('hd1080');
                        },
                        onStateChange: function(event) {
                            if (event.data === YT.PlayerState.ENDED) {
                                // Zie punt 2: wat te doen aan eindscherm?
                                hideEndScreen();
                            }
                        }
                    }
                });
            }

            function hideEndScreen() {
                // Verberg iframe of toon iets anders zodra video klaar is:
                var container = document.getElementById('player-container');
                // Bijvoorbeeld: vervang door een poster-afbeelding of laat 'n “speel opnieuw” knop zien
                container.innerHTML = '<div class="video-finished"> <button onclick="replay()">Nogmaals afspelen</button></div>';
            }

            function replay() {
                player.seekTo(0);
                player.playVideo();
                // eventueel weer de oorspronkelijke embed tonen:
                document.getElementById('player-container').innerHTML = '<div id="player"></div>';
                // Re-initialiseer opnieuw via onYouTubeIframeAPIReady (of behoud referentie)
                onYouTubeIframeAPIReady();
            }
        </script>




        <div class="vier">
            <img class='hoog' src="groen.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="vijf"> <img class='hoog' src="rood.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>
        <div class="zes"><img class='hoog' src="zwart.png" alt='hp-136 hoofdtelefoon' loading="lazy"></div>

        <div class="zeven col2 omschrijving oranje">
            <?php echo $mainProduct['omschrijving']; ?>
        </div>

        <div class="acht">
            <img class='hoog' src="lightinzakje.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>
        <div class="negen">
            <img class='hoog' src="hangend.png" alt='hp-136 hoofdtelefoon' loading="lazy">
        </div>

        <div class="tien oranje">
            <h1> <?php echo htmlspecialchars($variantProduct['TypeNummer']); ?></h1>
        </div>

        <div class="elf">
            <img class='hoog' src="zakje.png" alt='HP-305 in een zakje' loading="lazy">
        </div>

        <div class="twaalf oranje omschrijving col1">
            <?php echo $variantProduct['omschrijving']; ?>
        </div>

        <div class="dertien prijs">
            <?php
            renderPriceComponent($variantProduct['prijsstaffel'], $variantProduct['aantal_per_doos'], 'variant', $variantProduct['TypeNummer']);
            ?>
        </div>


    </article>


    <div id="lightbox-overlay" class="lightbox-overlay">
        <img id="lightbox-image" class="lightbox-image" src="" alt="Uitvergrote afbeelding">
        <!-- Include de lightbox JavaScript -->
        <script src="../lightbox.js"></script>
    </div>


    <?php include dirname(__DIR__, 2) . "/incs/bottom.php"; ?>