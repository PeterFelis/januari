<?php
// file: producten_beheer.php
session_start();

// 1) Is de gebruiker wel ingelogd?
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

// 2) Heeft de gebruiker de juiste rol?
// Voorbeeld: alleen 'admin' mag bij beheer
if ($_SESSION['user_role'] !== 'admin') {
    // Doorsturen of melding tonen
    echo "Geen toegang tot deze pagina.";
    exit;
}


$title = 'Product beheer';
include_once __dir__ . '/incs/top.php';
?>

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>


<style>
    .container {
        display: flex;
        font-family: Arial, sans-serif;
    }

    #left-pane,
    #right-pane {
        width: 50%;
        padding: 20px;
        box-sizing: border-box;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        margin: 5px 0;
        cursor: pointer;
    }

    .hidden {
        display: none;
    }

    .ql-toolbar,
    #omschrijving,
    #sticker_text {
        width: 80%;
        max-width: 800px;
        box-sizing: border-box;
    }

    #omschrijving,
    #sticker_text {
        height: 12rem;
        margin-bottom: 2rem;
    }
</style>
<script>
    let isEditingNewProduct = false; // Bepaalt of we een nieuw product maken

    async function fetchProducts() {
        const response = await fetch('api_products.php');
        return await response.json();
    }

    async function fetchProductById(id) {
        const response = await fetch(`api_products.php?id=${id}`);
        return await response.json();
    }

    async function saveProduct(data, isNew) {
        const method = isNew ? 'POST' : 'PUT';
        const response = await fetch('api_products.php', {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return await response.json();
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadProductList();
        // Voeg event listeners toe voor formuliervelden
        document.querySelectorAll('#right-pane input, #right-pane textarea').forEach(element => {
            element.addEventListener('input', (event) => {
                if (!document.getElementById('product-id').value && !isEditingNewProduct) {
                    isEditingNewProduct = true;
                    document.getElementById('save-button').classList.remove('hidden'); // Toon "Bewaren"
                }
            });
        });
    });

    function resetForm() {
        document.getElementById('product-id').value = '';
        document.getElementById('categorie').value = '';
        document.getElementById('subcategorie').value = '';
        document.getElementById('TypeNummer').value = '';
        quill.root.innerHTML = '';
        quillSticker.root.innerHTML = '';
        document.getElementById('prijsstaffel').value = '';
        document.getElementById('aantal_per_doos').value = '';
        document.getElementById('USP').value = '';
        document.getElementById('save-button').classList.remove('hidden');
        isEditingNewProduct = false; // Schakel naar "Nieuw Product"-modus
    }

    async function loadProductList() {
        const products = await fetchProducts();
        const list = document.getElementById('product-list');
        list.innerHTML = '';
        if (products.length > 0) {
            products.forEach(product => {
                const item = document.createElement('li');
                item.innerHTML = `${product.TypeNummer} 
                    <a href="artikelen/${product.TypeNummer}/" target="_blank">(bekijken)</a>`;
                item.dataset.id = product.id;
                item.onclick = async () => {
                    isEditingNewProduct = false; // Schakel naar "Bestaand Product"-modus
                    const productData = await fetchProductById(product.id);
                    document.getElementById('product-id').value = productData.id;
                    document.getElementById('categorie').value = productData.categorie;
                    document.getElementById('subcategorie').value = productData.subcategorie;
                    document.getElementById('TypeNummer').value = productData.TypeNummer;
                    quill.root.innerHTML = productData.omschrijving;
                    quillSticker.root.innerHTML = productData.sticker_text || ''; // Laad stickertekst
                    document.getElementById('prijsstaffel').value = productData.prijsstaffel;
                    document.getElementById('aantal_per_doos').value = productData.aantal_per_doos;
                    document.getElementById('USP').value = productData.USP;
                    document.getElementById('save-button').classList.add('hidden'); // Verberg opslaan-knop
                };
                list.appendChild(item);
            });
        }
    }

    async function autoSave(event) {
        const id = document.getElementById('product-id').value;
        if (!id || isEditingNewProduct) return; // Auto-save alleen voor bestaande producten

        const data = {
            id: id,
            categorie: document.getElementById('categorie').value,
            subcategorie: document.getElementById('subcategorie').value,
            TypeNummer: document.getElementById('TypeNummer').value,
            omschrijving: quill.root.innerHTML,
            sticker_text: quillSticker.root.innerHTML, // Sla stickertekst op
            prijsstaffel: document.getElementById('prijsstaffel').value,
            aantal_per_doos: document.getElementById('aantal_per_doos').value,
            USP: document.getElementById('USP').value
        };

        await saveProduct(data, false); // Bewaar wijzigingen
        loadProductList();
    }

    function detectNewProduct(event) {
        if (!document.getElementById('product-id').value && !isEditingNewProduct) {
            isEditingNewProduct = true;
            document.getElementById('save-button').classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Voeg auto-save toe aan invoervelden
        document.querySelectorAll('#right-pane input, #right-pane textarea').forEach(element => {
            element.addEventListener('input', (event) => {
                detectNewProduct(event); // Controleer of dit een nieuw product is
                autoSave(event);
            });
        });

        quill.on('text-change', () => {
            detectNewProduct();
            autoSave();
        });

        quillSticker.on('text-change', () => {
            detectNewProduct();
            autoSave();
        });

        document.getElementById('save-button').addEventListener('click', async () => {
            const data = {
                categorie: document.getElementById('categorie').value,
                subcategorie: document.getElementById('subcategorie').value,
                TypeNummer: document.getElementById('TypeNummer').value,
                omschrijving: quill.root.innerHTML,
                sticker_text: quillSticker.root.innerHTML, // Sla stickertekst op
                prijsstaffel: document.getElementById('prijsstaffel').value,
                aantal_per_doos: document.getElementById('aantal_per_doos').value,
                USP: document.getElementById('USP').value
            };
            await saveProduct(data, true); // Nieuw product opslaan
            resetForm();
            document.getElementById('save-button').classList.add('hidden'); // Verberg opslaan-knop
            loadProductList();
        });

        document.getElementById('new-button').addEventListener('click', resetForm);
    });
</script>
</head>

<body>
    <?php
    include_once  __DIR__ . '/incs/menuBeheer.php';
    ?>

    <div class="container">
        <div id="left-pane">
            <h2>Producten</h2>
            <ul id="product-list"></ul>
        </div>
        <div id="right-pane">
            <h2>Product Beheren</h2>
            <form>
                <input type="hidden" id="product-id">
                <label for="categorie">Categorie:</label>
                <input type="text" id="categorie"><br><br>
                <label for="subcategorie">Subcategorie:</label>
                <input type="text" id="subcategorie"><br><br>
                <label for="TypeNummer">TypeNummer:</label>
                <input type="text" id="TypeNummer" required><br><br>
                <label for="omschrijving">Omschrijving:</label>
                <div id="omschrijving"></div>
                <label for="sticker_text">Sticker Tekst:</label>
                <div id="sticker_text"></div>
                <label for="prijsstaffel">Prijsstaffel:</label>
                <textarea rows="4" cols="30" id="prijsstaffel"></textarea><br><br>
                <label for="aantal_per_doos">Aantal per doos:</label>
                <input type="number" id="aantal_per_doos" required><br><br>
                <label for="USP">USP:</label>
                <textarea rows="6" cols="30" id="USP"></textarea><br><br>
                <button class="hidden" type="button" id="save-button">Bewaren</button>
                <button type="button" id="new-button">Leegmaken</button>
            </form>
        </div>
    </div>

    <script>
        var quill = new Quill('#omschrijving', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link', 'blockquote', 'code-block'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }]
                ]
            }
        });

        var quillSticker = new Quill('#sticker_text', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link']
                ]
            }
        });

        document.getElementById('save-button').addEventListener('click', async () => {
            const omschrijvingHTML = quill.root.innerHTML; // Haal HTML-opgemaakte tekst op
            const stickerTextHTML = quillSticker.root.innerHTML; // Haal stickertekst op
            const data = {
                categorie: document.getElementById('categorie').value,
                subcategorie: document.getElementById('subcategorie').value,
                TypeNummer: document.getElementById('TypeNummer').value,
                omschrijving: omschrijvingHTML,
                sticker_text: stickerTextHTML, // Sla stickertekst op
                prijsstaffel: document.getElementById('prijsstaffel').value,
                aantal_per_doos: document.getElementById('aantal_per_doos').value,
                USP: document.getElementById('USP').value
            };

            await saveProduct(data, true); // Nieuw product opslaan
            resetForm();
            loadProductList();
        });
    </script>
</body>

</html>