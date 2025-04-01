<?php
// file: product_sticker.php
include_once __DIR__ . '/incs/sessie.php';

// 1) Is de gebruiker wel ingelogd?
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

// 2) Heeft de gebruiker de juiste rol?
// Voorbeeld: alleen 'admin' mag bij beheer
if ($_SESSION['role'] !== 'admin') {
    // Doorsturen of melding tonen
    echo "Geen toegang tot deze pagina.";
    exit;
}


$title = "Product stikker maken";
$menu = 'beheer';
include_once __dir__ . '/incs/top.php';

?>

<body>
    <?php include_once __dir__ . '/incs/menu.php';

    include_once __DIR__ . '/incs/dbConnect.php';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Databaseverbinding mislukt: ' . $e->getMessage());
    }

    // Haal ook aantal_per_doos op
    $query = "SELECT id, categorie, subcategorie, TypeNummer, sticker_text, aantal_per_doos
    FROM products";
    $stmt = $pdo->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Zet alle data om naar JSON
    $jsonData = json_encode($result);


    $title = 'Stickers afdrukken';
    include_once __dir__ . '/incs/top.php';

    ?>

    <style>
        h1,
        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        h1 {
            font-weight: 500;
            line-height: 1.2;
            font-size: 6rem;

        }

        p {
            margin: 0;
            padding: 0;

        }

        .tekstUSPsticker {
            margin-top: 2rem;
        }

        .button-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 20px;
            /* afgeronde hoeken */
            background-color: #007BFF;
            /* blauw, pas aan naar wens */
            color: #fff;
            /* witte tekst */
            font-size: 14px;
            /* wat grotere tekst */
            cursor: pointer;
            transition: background-color 0.2s ease-in-out,
                transform 0.2s ease-in-out;
        }

        button:hover {
            background-color: #0056b3;
            /* iets donkerder blauw bij hover */
            transform: translateY(-2px);
            /* klein ‘opwaarts’ effect bij hover */
        }

        button:active {
            background-color: #003f7f;
            /* nog donkerder bij klikken */
            transform: translateY(0);
            /* klik-effect ‘terug naar plek’ */
        }

        /* Highlight voor de geselecteerde knop */
        .selected {
            background-color: #ffcc66;
            /* opvallende kleur voor de geselecteerde knop */
            color: #000;
            font-weight: bold;
        }

        /* Input en select fields wat mooier maken */
        input[type="text"],
        select {
            padding: 8px 12px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 20px;
            /* afgeronde hoeken */
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease-in-out;
            max-width: 200px;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #007BFF;
            /* randje in de hoofdkleur bij focus */
        }


        .sticker {
            border: 1px solid grey;
            padding: 2rem;
            border-radius: 8px;
            text-align: left;
        }

        .large {
            font-size: 24px;

        }

        .small {
            font-size: 20px;

        }

        .sticker-container {
            display: flex;
            flex-wrap: wrap;
        }


        .selected {
            background-color: #ffcc66;
            /* Kies een kleur naar wens */
            color: #000;
            /* Tekstkleur (optioneel) */
            font-weight: bold;
            /* Eventueel dikgedrukt */
        }

        @page {
            margin: 0;
            /* minimaliseer kop- en voetteksten */
        }

        @media print {

            /* Verberg alles behalve de stickerContainer bij print */
            body * {
                visibility: hidden;
            }

            #stickerContainer,
            #stickerContainer * {
                visibility: visible;
            }

            #stickerContainer {
                position: absolute;
                top: 0;
                left: 0;
            }

            .sticker {
                page-break-after: always;
                border: none;
                padding: 0;
            }

            .large {
                margin-left: 10cm;
                /* horizontaal 10 cm */
                margin-top: 13cm;
                /* verticaal  10 cm */

            }

            .small {
                margin-left: 12cm;
                /* horizontaal 10 cm */
                margin-top: 5cm;
                /* verticaal   3 cm */

            }

            div {
                margin-bottom: 2rem;
            }
        }

        main {
            margin-top: 10rem;
        }
    </style>

    <main>
        <h1>Stickers Afdrukken</h1>

        <!-- Rijen voor categorie, subcategorie, typeNummer -->
        <h2>Soort</h2>
        <div id="categoryRow" class="button-row"></div>

        <h2>Type</h2>
        <div id="subcategoryRow" class="button-row"></div>

        <h2>TypeNummers</h2>
        <div id="typeNumberRow" class="button-row"></div>

        <!-- Extra inputveld voor stickerformaat + "aantal per doos" + printknop -->
        <br>
        <label for="size">Kies stickerformaat:</label>
        <select name="size" id="size" onchange="generateSticker()">
            <option value="small">Klein</option>
            <option value="large">Groot</option>
        </select>

        &nbsp;&nbsp; <!-- wat spaties -->

        <label for="boxQuantityInput">Aantal per doos:</label>
        <input type="text" id="boxQuantityInput" oninput="generateSticker()" />

        <br><br>
        <button type="button" onclick="window.print()">Afdrukken</button>

        <!-- Container waar stickers in getoond worden -->
        <div id="stickerContainer" class="sticker-container"></div>

        <script>
            function highlightSelectedBtn(parentElement, clickedButton) {
                // Verwijder .selected op alle knoppen binnen de parent
                const allButtons = parentElement.querySelectorAll('button');
                allButtons.forEach(btn => btn.classList.remove('selected'));

                // Voeg .selected toe aan de knop waarop net is geklikt
                clickedButton.classList.add('selected');
            }



            // Alle producten uit PHP
            const allProducts = JSON.parse('<?php echo $jsonData; ?>');

            let selectedCategory = null;
            let selectedSubcategory = null;
            let selectedProduct = null; // Wordt gevuld met het gekozen product-object

            // Helper-functie om knoppen te highlighten
            function highlightSelectedBtn(parentElement, clickedButton) {
                const allButtons = parentElement.querySelectorAll('button');
                allButtons.forEach(btn => btn.classList.remove('selected'));
                clickedButton.classList.add('selected');
            }

            window.addEventListener('DOMContentLoaded', () => {
                showCategories();
            });

            // Toon alle unieke categorieën als knoppen
            function showCategories() {
                const categories = [...new Set(allProducts.map(p => p.categorie))];
                const categoryRow = document.getElementById('categoryRow');
                categoryRow.innerHTML = ''; // wis eerdere inhoud

                categories.forEach(cat => {
                    const btn = document.createElement('button');
                    btn.textContent = cat;
                    btn.onclick = () => {
                        selectedCategory = cat;
                        selectedSubcategory = null;
                        selectedProduct = null;

                        // Highlight deze knop
                        highlightSelectedBtn(categoryRow, btn);

                        showSubcategories(cat);
                    };
                    categoryRow.appendChild(btn);
                });

                // Wis subcategorie-, type-rij en stickers
                document.getElementById('subcategoryRow').innerHTML = '';
                document.getElementById('typeNumberRow').innerHTML = '';
                document.getElementById('stickerContainer').innerHTML = '';
            }

            // Toon subcategorieën van de gekozen categorie
            function showSubcategories(category) {
                const filtered = allProducts.filter(p => p.categorie === category);
                const subcategories = [...new Set(filtered.map(p => p.subcategorie))];

                const subcategoryRow = document.getElementById('subcategoryRow');
                subcategoryRow.innerHTML = '';

                subcategories.forEach(subcat => {
                    const btn = document.createElement('button');
                    btn.textContent = subcat;
                    btn.onclick = () => {
                        selectedSubcategory = subcat;
                        selectedProduct = null;

                        // Highlight deze knop
                        highlightSelectedBtn(subcategoryRow, btn);

                        showTypeNummers(category, subcat);
                    };
                    subcategoryRow.appendChild(btn);
                });

                document.getElementById('typeNumberRow').innerHTML = '';
                document.getElementById('stickerContainer').innerHTML = '';
            }

            // Toon alle TypeNummers van de gekozen subcategorie
            function showTypeNummers(category, subcategory) {
                const filtered = allProducts.filter(p =>
                    p.categorie === category && p.subcategorie === subcategory
                );

                const typeNumberRow = document.getElementById('typeNumberRow');
                typeNumberRow.innerHTML = '';

                filtered.forEach(product => {
                    const btn = document.createElement('button');
                    btn.textContent = product.TypeNummer;
                    btn.onclick = () => {
                        selectedProduct = product;
                        // Highlight deze knop
                        highlightSelectedBtn(typeNumberRow, btn);

                        // Vul het inputveld met 'aantal_per_doos' zodra een product is gekozen.
                        document.getElementById('boxQuantityInput').value = product.aantal_per_doos || '';

                        generateSticker();
                    };
                    typeNumberRow.appendChild(btn);
                });

                document.getElementById('stickerContainer').innerHTML = '';
            }

            // Sticker genereren en tonen
            function generateSticker() {
                // Als nog geen product is geselecteerd, doen we niets
                if (!selectedProduct) return;

                const size = document.getElementById('size').value;
                // Lees het (eventueel aangepaste) aantal per doos uit
                const boxQty = document.getElementById('boxQuantityInput').value;

                // container leegmaken
                const container = document.getElementById('stickerContainer');
                container.innerHTML = '';

                // In dit voorbeeld maken we maar 1 sticker
                const stickerDiv = document.createElement('div');
                stickerDiv.className = 'sticker ' + size;

                // Toon productnaam, aantal per doos en de stickertekst
                stickerDiv.innerHTML = `
        <div>
        <h1>${selectedProduct.TypeNummer}</h1>
        <h1>${boxQty} stuks</h1></div>
        <p class='tekstUSPsticker'>${selectedProduct.sticker_text || ''}</p>
    `;
                container.appendChild(stickerDiv);
            }
        </script>
    </main>
</body>

</html>