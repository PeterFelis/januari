<?php
include_once __DIR__ . '/incs/sessie.php';
$title = 'wat kan er allemaal bij ons';
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>

<style>
    .vvv-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .vvv-table th,
    .vvv-table td {
        border: 2px dashed #f0bf70;
        padding: 8px;
        text-align: center;
    }

    .vvv-table th {
        background-color: transparent;
        color: #000;
    }
</style>


<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <section class="bovenlicht">
        <div class="bovenlicht__wrapper">
            <div class="bovenlicht__text">
                <h1>Webshop Info</h1>
                <h3>
                    Kopen, VVV cadeaukaart, klant worden.<br>Hier leggen we het duidelijk uit.
                </h3>
            </div>
            <div class="bovenlicht__image" style="background-image: url('afbeeldingen/hero.png');">
                <!-- Inhoud, indien nodig -->
            </div>
        </div>
    </section>



    <section class="full-sectiontransparent">
        <main>
            <section class="section reverse">
                <div class="text">
                    <h2>Onze Klanten</h2>
                    <p>Wij leveren uitsluitend in groothandelshoeveelheden – dus geen losse producten,
                        maar de aantallen die organisaties nodig hebben. Dit is kosteneffectief.
                        Onze klanten zijn divers: van scholen, schoolstichtingen, ROC's, hogescholen en universiteiten tot revalidatiecentra, ziekenhuizen, musea, bibliotheken en andere groothandels.

                        Of je nu in het onderwijs, de zorg of een andere sector zit: bij ons vind je altijd de kwaliteit, het product en service die je zoekt.
                        Wil je weten wat wij voor jou kunnen betekenen? Neem gerust contact met ons op!</p>
                    <h2>Betaling</h2>
                    <p>
                        Bij ons kun je meestal achteraf betalen.
                        Na je bestelling sturen we direct de goederen en ontvang je later de factuur. Deze dien je binnen 10 dagen
                        na ontvangst van de goederen te voldoen. In sommige gevallen behouden we ons het recht voor om vooraf betaling .
                        te vragen. Mocht dit het geval zijn, ontvang je eerst een proforma factuur, zodat je eventueel de bestelling
                        kunt annuleren. Zodra we de betaling ontvangen, verzenden we de goederen.
                    </p>


                    <h2>Offerte</h2>
                    <p>
                        Bij de webshop kunt u kiezen uit gelijk bestellen of een offerte krijgen. Druk op offerte en de offerte wordt als PDF gelijk aan u gemailed.
                        De bestelling blijft in de winkelmand staan en kan na akkoord alsnog snel besteld worden.
                    </p>
                    <h2>BTW</h2>
                    <p>
                        Al onze prijzen zijn exclusief BTW.
                        Voor Nederlandse klanten komt hier 21% BTW bij,
                        wat duidelijk op de factuur wordt vermeld. Kom je uit België,
                        dan kun je de BTW laten verleggen, mits je een geldig BTW-nummer aanlevert.
                    </p>
                    <h2>Leveringskosten</h2>
                    <p>
                        We rekenen een vaste bijdrage van € 7,35 (exclusief BTW) als bijdrage in de leveringskosten.
                        Bestellingen vanaf € 1000,00 (exclusief BTW) worden franco verzonden, zowel voor klanten in Nederland als België.
                    </p>
                </div>
                <div class="image">
                    <img src="afbeeldingen/hp-136-zijaanzicht.png" alt="Illustratie">
                </div>
            </section>
        </main>
    </section>



    <!-- Eerste sectie (witte achtergrond) nu onderaan -->
    <section class="full-sectioncolour">
        <main>
            <section class="section first-section">
                <div class="image">
                    <img src="afbeeldingen/vvv.jpg" alt="Illustratie">
                </div>
                <div class="text">
                    <h2>VVV cadeaukaart</h2>
                    <p>
                        Bij bestellingen via onze webshop ontvangt u een digitale vvv cadeaukaart waarmee u kunt winkelen bij veel Nederlandse winkels.<br>
                        U krijgt de VVV-code toegestuurd op het door u opgegeven e-mailadres, nadat de betaling is ontvangen.<br><br>
                        Let op: dit geldt niet voor bestellingen die buiten onze webshop worden geplaatst of Belgisiche klanten.
                        Tijdens het bestellen wordt u geinformeerd of u in aanmerking komt voor de VVV cadeaukaart en hoe hoog het bedrag is.

                      
                    <table class="vvv-table">
                        <thead>
                            <tr>
                                <th>Orderhoogte</th>
                                <th>VVV-bedrag</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>€ 400,00</td>
                                <td>€ 7,50</td>
                            </tr>
                            <tr>
                                <td>€ 700,00</td>
                                <td>€ 12,50</td>
                            </tr>
                            <tr>
                                <td>€ 1000,00</td>
                                <td>€ 17,50</td>
                            </tr>
                            <tr>
                                <td>€ 1500,00</td>
                                <td>€ 25,00</td>
                            </tr>
                        </tbody>
                    </table>

                    </p>
                    <a href="https://www.vvvcadeaukaarten.nl/" target="_blank">Meer informatie over de VVV cadeaukaart</a>
                </div>

            </section>
        </main>
    </section>


    <section class="full-sectiontransparent">
        <main>
            <section class="section reverse">
                <div class="text">
                    <h2>Bestellen</h2>
                    <p>
                        Bestellen
                        In onze webshop kunt u direct bestellen. <br>
                        U koopt per verpakkingseenheid. Wij hanteren staffelprijzen, deze staan bij de producten vermeld.
                        Het systeem laat u automatisch de staffelprijs zien bij het invoeren van het aantal. <br>

                    </p>
                    <p>Bestellen kan ook op deze manier:
                        via u eigen inkoopsysteem. <br>
                        via een email<br>
                        of gewoon even bellen<br>
                        Wij zorgen voor de rest<br>
                        U kunt de bestelling mailen naar verkoop@fetum.nl.
                    </p>

                    <h2>Bestelproces</h2>
                    <p>
                        Na het invoeren van een bestelling ontvangt u een orderbevestiging per e-mail.
                        Hierin staat een overzicht van de bestelde artikelen en de totale kosten. <br>
                        Dit is dus geen factuur, maar een bevestiging van uw bestelling. <br>
                    </p>
                </div>
                <div class="image">
                    <img src="afbeeldingen/hp-136-zijaanzicht.png" alt="Illustratie">
                </div>
            </section>
        </main>

    </section>

    <!-- Eerste sectie (witte achtergrond) nu onderaan -->
    <section class="full-sectioncolour">
        <main>
            <section class="section first-section">
                <div class="image">
                    <img src="afbeeldingen/hp-32.png" alt="Illustratie">
                </div>
                <div class="text">
                    <h2>Algemene voorwaarden</h2>
                    <p>
                        <a href="leveringsvoorwaarden.php">Hier vind u onze algemene voorwaarden.</a> Bij het bestellen vragen wij u dit akkoord te
                        bevinden voordat de bestelling wordt afgerond.
                    </p>
                    <h2>Leveringskosten</h2>
                    Wij vragen een bijdrage in de leveringskosten van € 7,35 (ex btw). Bestellingen boven de € 1000,00
                    (ex btw) worden franco geleverd. Dit geldt voor Nederlandse en Belgische klanten.
                </div>
            </section>
        </main>
    </section>

    </div>


    <?php
    include_once __DIR__ . '/incs/bottom.php';
    ?>