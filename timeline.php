<?php
//file: timeline.php
// timeline van het bedrijf
//05-03-2025
include_once __DIR__ . '/incs/sessie.php';
$title = 'Onze Historie';
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>

<style>
    /* =================================
       Basic Timeline Styling
    ================================== */
    .timeline-section {
        margin: 40px 0;
    }

    /* The vertical line */
    .timeline {
        position: relative;
        margin: 20px 0;
        padding: 20px 0;
        border-left: 2px dashed #f0bf70;
        /* Use your accent color */
    }

    /* Each timeline item */
    .timeline-item {
        position: relative;
        margin: 20px 0;
        padding-left: 40px;
        /* space for the line/dot */
    }

    /* The dot on the timeline */
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -7px;
        /* horizontally center the dot over the dashed line */
        top: 5px;
        width: 14px;
        height: 14px;
        background-color: #f0bf70;
        /* dot color */
        border: 2px solid #fff;
        /* small white border around the dot */
        border-radius: 50%;
    }

    /* Year or date styling */
    .timeline-year {
        font-weight: bold;
        font-size: 2.3rem;
        /* Increased font size */
        color: #333;
        margin-bottom: 8px;
    }

    /* Headings for each timeline item */
    .timeline-content h3 {
        margin: 5px 0;
        font-size: 2.2rem;
        /* Increased font size */
        font-weight: bold;
        color: #444;
    }

    .timeline-content p {
        margin: 0 0 10px;
        font-size: 2.1rem;
        /* Increased font size */
    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <!-- Hero/header section -->
    <section class="bovenlicht">
        <div class="bovenlicht__wrapper">
            <div class="bovenlicht__text">
                <h1>Onze Historie</h1>
                <h3>Hoe het allemaal begon en waar we nu staan</h3>
            </div>
            <!-- Updated hero image and text example -->
            <div class="bovenlicht__image" style="background-image: url('afbeeldingen/finlux.jpg');">
                <!-- Pas eventueel tekst of andere content hier aan -->
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="full-sectiontransparent">
        <main>
            <section class="section timeline-section">
                <div class="timeline">
                    <!-- 1985 -->
                    <div class="timeline-item">
                        <div class="timeline-year">1985</div>
                        <div class="timeline-content">
                            <h3>Start januari 1985 door A.J. Felis als Finland electronics</h3>
                            <p>Import <a href="https://en.wikipedia.org/wiki/Finlux" target="_blank" rel="noopener noreferrer">Finlux kleuren televisies voor Retail</a></p>
                        </div>
                    </div>
                    <!-- 1992 -->
                    <div class="timeline-item">
                        <div class="timeline-year">1992</div>
                        <div class="timeline-content">
                            <h3>Overgang naar Finlux hotel tv</h3>
                            <p>Import televisies voor detailhandel naar heijnen (ook importeur Revox)</p>
                        </div>
                    </div>
                    <!-- 1998 -->
                    <div class="timeline-item">
                        <div class="timeline-year">1998</div>
                        <div class="timeline-content">
                            <h3>Start import hoofdtelefoons voor ziekenhuizen</h3>
                            <p>Eerste school als klant</p>
                        </div>
                    </div>
                    <!-- 2000 -->
                    <div class="timeline-item">
                        <div class="timeline-year">2000</div>
                        <div class="timeline-content">
                            <h3>Overgang naar Thomson hotel tv's</h3>
                            <p>Levering o.a. aan Center Parcs en ziekenhuizen</p>
                        </div>
                    </div>
                    <!-- 2005 -->
                    <div class="timeline-item">
                        <div class="timeline-year">2005</div>
                        <div class="timeline-content">
                            <h3><a href="https://www.alto-products.de/" target="_blank" rel="noopener noreferrer">Officieel vertegenwoordiger AHS Alto in Nederland</a></h3>
                            <p>Hoofdtelefoons en patiëntenetuis</p>
                        </div>
                    </div>
                    <!-- 2007 -->
                    <div class="timeline-item">
                        <div class="timeline-year">2007</div>
                        <div class="timeline-content">
                            <h3><a href="https://www.thomson-multimedia.com/" target="_blank" rel="noopener noreferrer">Failliet Thomson</a>, overgang naar LG</h3>
                        </div>
                    </div>
                    <!-- 2015 -->
                    <div class="timeline-item">
                        <div class="timeline-year">2015</div>
                        <div class="timeline-content">
                            <h3><a href="https://nl.wikipedia.org/wiki/Honselersdijk" target="_blank" rel="noopener noreferrer">Verhuizing naar Honselersdijk</a></h3>
                        </div>
                    </div>
                    <!-- 2019 -->
                    <div class="timeline-item">
                        <div class="timeline-year">2019</div>
                        <div class="timeline-content">
                            <h3>Afbouwen hotel tv (LG samenwerking moeizaam)</h3>
                            <p>Overgang naar alleen hoofdtelefoons, muizen, accessoires etc.</p>
                        </div>
                    </div>
                    <!-- 2023 -->
                    <div class="timeline-item">
                        <div class="timeline-year">2023</div>
                        <div class="timeline-content">
                            <h3>Grote uitbreiding assortiment</h3>
                            <p>Hoofdtelefoons van diverse leveranciers + extras (stickers, opruimzakjes, etc.)</p>
                        </div>
                    </div>
                    <!-- 2025 -->
                    <div class="timeline-item">
                        <div class="timeline-year">2025</div>
                        <div class="timeline-content">
                            <h3>Start samenwerking met <a href="https://shop.educorner.be/" target="_blank" rel="noopener noreferrer">educorner Belgie</h3></a>
                            <p>Educorner vertegenwoordigt ons in Belgie, prijzen en condities gelijk aan Nederland</p>
                            <p>En een Totaal nieuwe Fetum website. Met o.a. opnieuw VVV checkes en mogelijkheid tot automatische offerte</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </section>

    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>

</html>