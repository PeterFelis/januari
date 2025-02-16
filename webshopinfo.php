<?php
$title = 'wat kan er allemaal bij ons';
$statusbalk = "Iets bestellen? Gewoon even mailen of bellen!";
$menu = 'beheer';
include_once __DIR__ . '/incs/top.php';
include_once __DIR__ . '/incs/statusbalk.php';
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <style>
        /* Algemeen: main en container voor vaste breedte */
        main,
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Basis styling voor de secties */
        .section {
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .section .image,
        .section .text {
            flex: 1;
            padding: 20px;
        }

        .section img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Eerste sectie: witte achtergrond (in main) */
        .first-section {
            background-color: transparent;
        }

        /* Tweede sectie: full-width achtergrondkleur */
        .full-section {
            width: 100%;
            background-color: #f9f9f9;
            /* Pas deze kleur aan naar wens */
        }

        /* Binnen de container van de tweede sectie: kolommen omkeren (tekst links, foto rechts) */
        .reverse {
            flex-direction: row-reverse;
        }

        /* Responsive: op kleinere schermen stapelen de kolommen */
        @media (max-width: 768px) {
            .section {
                flex-direction: column;
            }
        }
    </style>
</head>

<body class="indexPaginaKleur">
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <div class="bovenlicht">
        <div class="bovenlicht__text tekst_rechts">
            <h1>Webshop Info</h1>
            <h3>
                Aankopen, VVV cheques, klant worden<br> en wat er allemaal mogelijk is.
            </h3>
        </div>
        <div class="bovenlicht__image">
            <img src="afbeeldingen/hp-32.png" alt="oortje met naamsticker" />
        </div>
    </div>

    <section class="full-section">
        <div class="container">
            <section class="section reverse">
                <div class="text">
                    <h2>Onze Klanten</h2>
                    <p>Wij leveren aan: Scholen, schoolstichtingen, ziekenhuizen, musea en bedrijven.</p>
                    <h2>Betaling</h2>
                    <p>
                        U kunt bij ons meestal achteraf betalen. Bij bestelling versturen wij de goederen en mailen
                        u later de factuur. De factuur dient binnen 10 dagen na ontvangst van de goederen voldaan te
                        worden. Wij houden ons het recht voor om eerst betaling te verzoeken. Wij laten dit weten en
                        sturen u een proforma factuur u kunt dan de bestelling annuleren. Na ontvangst van de
                        betaling verzenden wij de goederen
                    </p>
                    <h2>BTW</h2>
                    <p>
                        Alle geoffreerde prijzen zijn exclusief BTW. Het Nederlandse BTW percentage is 21%. Dit
                        wordt opgeteld bij de factuur. Bent u een Belgische klant dan kan de BTW verlegd worden mits
                        u een BTW nummer heeft. Wij dienen dit nummer van u te ontvangen
                    </p>
                    <h2>Leveringskosten</h2>
                    <p>
                        Wij vragen een bijdrage in de leveringskosten van € 5,95 (ex btw). Bestellingen boven de €
                        500,00 (ex btw) worden franco geleverd. Dit geldt voor Nederlandse en Belgische klanten.
                    </p>
                </div>
                <div class="image">
                    <img src="afbeeldingen/hp-136-zijaanzicht.png" alt="Illustratie">
                </div>
            </section>
        </div>
    </section>

    <!-- Eerste sectie (witte achtergrond) nu onderaan -->
    <main>
        <section class="section first-section">
            <div class="text">
                <h2>Irischeque</h2>
                <p>
                    Bij bestelling via onze webshop krijgt u een irischeque waarmee off-en -online gekocht kan
                    worden bij veel Nederlandse winkels. De cheque is digitaal, u ontvangt een code van VVV op
                    een door u op te geven email adres. Dit geldt niet voor bestellingen buiten onze Webshop om.
                    Orderhoogte VVVbedrag 300,00 7,50 500,00 10,00 700,00 15,00 900,00 20,00
                </p>
            </div>
            <div class="image">
                <img src="afbeeldingen/hp-32.png" alt="Illustratie">
            </div>
        </section>
    </main>

    <section class="full-section">
        <div class="container">
            <section class="section reverse">
                <div class="text">
                    <h2>Bestellen</h2>
                    <p>
                        In onze webshop kunt u direct bestellen. U koopt per verpakkingseenheid. De genoemde
                        aantallen is per verpakkingseenheid.
                    </p>
                    <h2>Klant</h2>
                    <p>U kunt:</p>
                    <ul>
                        <li>inloggen als bestaande klant</li>
                        <li>een account aanmaken</li>
                        <li>als gast bestellen</li>
                    </ul>
                    <p>
                        Deze optie is beschikbaar bij het afronden van uw bestelling Na het doen van uw bestellling
                        mailen wij u een orderbevestiging. Dit is dus geen factuur en is alleen voor uw informatie
                        en niet gebruiken om te betalen.
                    </p>
                </div>
                <div class="image">
                    <img src="afbeeldingen/hp-136-zijaanzicht.png" alt="Illustratie">
                </div>
            </section>
        </div>
    </section>

    <!-- Eerste sectie (witte achtergrond) nu onderaan -->
    <main>
        <section class="section first-section">
            <div class="text">
                <h2>Algemene voorwaarden</h2>
                <p>
                    hier vind u onze algemene voorwaarden. Bij het bestellen vragen wij u dit akkoord te
                    bevinden voordat de bestelling wordt afgerond.
                </p>
                <h2>Leveringskosten</h2>
                Wij vragen een bijdrage in de leveringskosten van € 5,95 (ex btw). Bestellingen boven de € 500,00
                (ex btw) worden franco geleverd. Dit geldt voor Nederlandse en Belgische klanten.
            </div>
            </div>
            <div class="image">
                <img src="afbeeldingen/hp-32.png" alt="Illustratie">
            </div>
        </section>
    </main>


</body>

</html>