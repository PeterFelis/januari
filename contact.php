<?php
$title = 'contact ';
$statusbalk = "Even bellen? 0174 769132";
$menu = 'beheer';
include_once __dir__ . '/incs/top.php';
include_once __dir__ . '/incs/statusbalk.php';
?>

<style>
    .kaart {
        height: 100vh;
        width: 100vw;
    }

    .grid2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        /* Twee gelijke kolommen */
        gap: 5px;
        /* Ruimte tussen de cellen */
        padding: 20px;
        /* Binnenmarge voor extra ruimte */

        /* Lichte achtergrondkleur voor een net uiterlijk */
        border-radius: 8px;
        /* Afronden van de hoeken */
        max-width: 600px;
        /* Optionele maximale breedte */
        margin: 0 auto 10vh auto;
        /* Centreert de grid horizontaal */
    }

    .grid2col .tr {
        font-weight: bold;
        /* Labels vetgedrukt */
        text-align: right;
        /* Tekst rechts uitlijnen zodat de labels dicht bij de waarden staan */
        padding-right: 10px;
        /* Extra ruimte aan de rechterkant van de labels */
    }
</style>

<body class='indexPaginaKleur'>

    <?php include_once __dir__ . '/incs/menu.php'; ?>

    <div class="bovenlicht">
        <div class="bovenlicht__text tekst_rechts">
            <h1>contact info</h1>
            <h3>Altijd handig</h3>
        </div>
        <div class=" bovenlicht__image">
            <img src="afbeeldingen/2710-rood-geel-block.jpg" alt="budget hoofdtelefoon in 4 verschillende vrolijke kleuren" />
        </div>

    </div>

    <main>



        <div class="grid2col phl">
            <div class="tr">Naam</div>
            <div>Fetum</div>
            <div class="tr">Adres</div>
            <div>Grote waard 36</div>
            <div class="tr">Postcode</div>
            <div>2675 BX</div>
            <div class="tr">Plaats</div>
            <div>Honselersdijk</div>
            <div class="tr"></div>
            <div></div>
            <div class="tr">Telefoon</div>
            <div>+31 (0) 174 769132</div>
            <div class="tr">Mail</div>
            <div>info@fetum.nl</div>
            <div class="tr"></div>
            <div></div>
            <div class="tr">KVK</div>
            <div>Den Haag 28045481</div>
            <div class="tr">BTW nummer</div>
            <div>801462.265.B01</div>
            <div class="tr">Bank</div>
            <div>NL78 KNAB 0724 8909 47</div>
            <div class="tr">BIC</div>
            <div>KNABNL2H</div>
        </div>
    </main>


    <section class="kaart">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2455.82541316921!2d4.221463815671645!3d52.010059179720855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2snl!4v1680985867192!5m2!1sen!2snl" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>




    </main>
</body>

</html>