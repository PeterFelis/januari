<?php
// file: producten_beheer.php
$menu = "beheer";
session_start();

// 1) Is de gebruiker wel ingelogd?
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

// 2) Heeft de gebruiker de juiste rol?
if ($_SESSION['role'] !== 'admin') {
    echo "Geen toegang tot deze pagina.";
    exit;
}

// === Afhandelblok voor de upload van een afbeelding ===
if (isset($_GET['action']) && $_GET['action'] === 'upload_image') {
    // Zorg dat het een POST-request is
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        exit;
    }

    // Controleer of er een bestand is geüpload
    if (!isset($_FILES['avatar'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Geen bestand geüpload.']);
        exit;
    }

    // Verkrijg het product (TypeNummer) via queryparameter
    if (!isset($_GET['product']) || empty($_GET['product'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Product niet gespecificeerd.']);
        exit;
    }
    $product = $_GET['product'];

    // Bepaal de map voor dit product, bijvoorbeeld in "artikelen/{TypeNummer}"
    $directory = __DIR__ . '/artikelen/' . $product;
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $file = $_FILES['avatar'];

    // Controleer op uploadfouten
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Upload fout: ' . $file['error']]);
        exit;
    }

    // Bepaal de extensie (allemaal kleine letters)
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Alleen JPG, JPEG en PNG bestanden zijn toegestaan.']);
        exit;
    }

    // De bestemming: in de productmap met de naam "Pfoto.extension"
    $destPath = $directory . '/Pfoto.' . $extension;

    // Functie om de afbeelding te verkleinen
    function createResizedImage($srcPath, $destPath, $extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $srcImage = imagecreatefromjpeg($srcPath);
                break;
            case 'png':
                $srcImage = imagecreatefrompng($srcPath);
                break;
            default:
                return false;
        }
        if (!$srcImage) {
            return false;
        }
        list($width, $height) = getimagesize($srcPath);
        $newWidth = 300;
        $newHeight = intval(($height / $width) * $newWidth);
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        if ($extension === 'png') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }
        imagecopyresampled(
            $newImage,
            $srcImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($newImage, $destPath, 90);
                break;
            case 'png':
                imagepng($newImage, $destPath);
                break;
        }
        imagedestroy($srcImage);
        imagedestroy($newImage);
        return true;
    }

    $resizeSuccess = createResizedImage($file['tmp_name'], $destPath, $extension);
    if ($resizeSuccess) {
        header('Content-Type: application/json');
        // Geef de URL terug waarmee de shop later de afbeelding kan laden
        $imageUrl = 'artikelen/' . $product . '/Pfoto.' . $extension;
        echo json_encode(['success' => true, 'image_url' => $imageUrl]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Fout bij het verkleinen van de afbeelding.']);
        exit;
    }
}
// === Einde uploadafhandeling ===

$title = 'Product beheer';
include_once __DIR__ . '/incs/top.php';
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

    /* Styling voor de hiërarchische productselector links */
    #productSelectorContainer {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        max-height: 80vh;
        overflow-y: auto;
        border: 1px solid white;
        border-radius: 8px;
    }

    .selector-group {
        margin-bottom: 10px;
    }

    .selector-group>strong {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #333;
    }

    .button-row {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 5px;
    }

    /* Standaard worden subcategorie en productrijen verborgen */
    #selectorSubcategory,
    #selectorProduct {
        display: none;
    }

    #productSelectorContainer button {
        padding: 5px 10px;
        border: none;
        border-radius: 10px;
        background-color: #007BFF;
        color: #fff;
        font-size: 12px;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.2s;
    }

    #productSelectorContainer button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    #productSelectorContainer button:active {
        background-color: #003f7f;
        transform: translateY(0);
    }

    .selected {
        background-color: #ffcc66;
        color: #000;
        font-weight: bold;
    }

    /* Overige styling */
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
    .sticker-prijs-container {
        width: 100%;
        max-width: 800px;
        box-sizing: border-box;
    }

    #omschrijving {
        height: 18rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 0.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
    }

    /* Invoer-rij: Categorie, Subcategorie, TypeNummer en Aantal per doos */
    .input-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .input-row .form-group {
        flex: 1;
    }

    /* Extra styling voor de fotolink: een file-input wordt getoond */
    .foto-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }
</style>

<body class='indexPaginaKleur'>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <main>
        <!-- Snackbar element -->
        <div id="snackbar"></div>

        <div class="container">
            <!-- Linkerpaneel: productselector -->
            <div id="left-pane">
                <h2>Producten</h2>
                <div id="productSelectorContainer">
                    <div class="selector-group">
                        <strong>Categorie</strong>
                        <div id="selectorCategory" class="button-row"></div>
                    </div>
                    <div class="selector-group">
                        <strong>Subcategorie</strong>
                        <div id="selectorSubcategory" class="button-row"></div>
                    </div>
                    <div class="selector-group">
                        <strong>Product</strong>
                        <div id="selectorProduct" class="button-row"></div>
                    </div>
                </div>
            </div>

            <!-- Rechterpaneel: bewerkformulier -->
            <div id="right-pane">
                <h2>Product Beheren</h2>
                <form>
                    <input type="hidden" id="product-id">
                    <!-- Eerste regel: Categorie en Subcategorie -->
                    <div class="input-row">
                        <div class="form-group">
                            <label for="categorie">Categorie:</label>
                            <input type="text" id="categorie">
                        </div>
                        <div class="form-group">
                            <label for="subcategorie">Subcategorie:</label>
                            <input type="text" id="subcategorie">
                        </div>
                    </div>
                    <!-- Tweede regel: TypeNummer en Aantal per doos -->
                    <div class="input-row">
                        <div class="form-group">
                            <label for="TypeNummer">TypeNummer:</label>
                            <input type="text" id="TypeNummer" required>
                        </div>
                        <div class="form-group">
                            <label for="aantal_per_doos">Aantal per doos:</label>
                            <input type="number" id="aantal_per_doos" required>
                        </div>
                    </div>
                    <!-- Nieuwe rij: Leverbaar en Fotolink (file input) -->
                    <div class="input-row">
                        <div class="form-group">
                            <label for="leverbaar">Leverbaar:</label>
                            <input type="checkbox" id="leverbaar" checked>
                        </div>
                        <div class="form-group">
                            <label for="avatar">Fotolink:</label>
                            <!-- Visible file input -->
                            <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg">
                        </div>
                    </div>
                    <!-- Foto preview container -->
                    <div class="input-row">
                        <div class="form-group">
                            <div id="foto_preview_container">
                                <img id="foto_preview" style="max-width: 100px; display: none;" alt="Foto preview" />
                                <span id="foto_filename"></span>
                            </div>
                        </div>
                    </div>
                    <div id="omschrijvingveld">
                        <label for="omschrijving">Omschrijving:</label>
                        <div id="omschrijving"></div>
                    </div>
                    <div class="sticker-prijs-container">
                        <div>
                            <label for="sticker_text">Sticker Tekst:</label>
                            <div id="sticker_text"></div>
                        </div>
                        <div>
                            <label for="prijsstaffel">Prijsstaffel:</label>
                            <textarea id="prijsstaffel" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="USP">USP</label>
                        <textarea rows="6" cols="30" id="USP" placeholder="Elke regel wordt opgeslagen als een apart <p> element"></textarea>
                    </div>
                    <br>
                    <button class="hidden" type="button" id="save-button">Bewaren</button>
                    <button type="button" id="new-button">Leegmaken</button>
                    <button type="button" id="copy-button">Kopieer</button>
                    <button type="button" id="delete-button">Verwijder</button>
                </form>
            </div>
        </div>

        <script>
            // Globale variabelen
            let allProducts = [];
            let selectedCategory = null;
            let selectedSubcategory = null;
            let selectedProduct = null;
            let isEditingNewProduct = false;

            // Snackbar functie
            function showSnackbar(message) {
                const snackbar = document.getElementById('snackbar');
                snackbar.textContent = message;
                snackbar.className = "show";
                setTimeout(() => {
                    snackbar.className = snackbar.className.replace("show", "");
                }, 3000);
            }

            // Custom confirm functie
            function customConfirm(message) {
                return new Promise((resolve) => {
                    const modal = document.getElementById('confirmModal');
                    const confirmMessage = document.getElementById('confirmMessage');
                    const yesBtn = document.getElementById('confirmYes');
                    const noBtn = document.getElementById('confirmNo');
                    confirmMessage.textContent = message;
                    modal.style.display = 'block';
                    yesBtn.onclick = () => {
                        modal.style.display = 'none';
                        resolve(true);
                    };
                    noBtn.onclick = () => {
                        modal.style.display = 'none';
                        resolve(false);
                    };
                });
            }

            // Update URL met selectie
            function updateURLWithSelection(category, subcategory = '') {
                const params = new URLSearchParams(window.location.search);
                if (category) {
                    params.set('category', category);
                } else {
                    params.delete('category');
                }
                if (subcategory) {
                    params.set('subcategory', subcategory);
                } else {
                    params.delete('subcategory');
                }
                history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
            }

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

            // Controleer of de productpagina bestaat en voeg een linkicoon toe
            async function checkProductPage(productName, button) {
                try {
                    let response = await fetch('artikelen/' + encodeURIComponent(productName) + '/index.php', {
                        method: 'HEAD'
                    });
                    if (response.ok) {
                        const linkIcon = document.createElement('span');
                        linkIcon.textContent = " 🔗";
                        linkIcon.style.cursor = 'pointer';
                        linkIcon.style.color = '#007BFF';
                        linkIcon.title = "Bekijk productpagina";
                        linkIcon.onclick = (e) => {
                            e.stopPropagation();
                            window.open('artikelen/' + encodeURIComponent(productName) + '/index.php', '_blank');
                        };
                        button.appendChild(linkIcon);
                    }
                } catch (error) {
                    // Doe niets bij error.
                }
            }

            // INITIALISATIE VAN DE PRODUCTSELECTOR
            async function initProductSelector() {
                allProducts = await fetchProducts();
                showCategories();

                // Lees de URL-parameters en simuleer de juiste selecties
                const params = new URLSearchParams(window.location.search);
                const categoryParam = params.get('category');
                const subcategoryParam = params.get('subcategory');

                if (categoryParam) {
                    const categoryRow = document.getElementById('selectorCategory');
                    const catBtn = Array.from(categoryRow.children).find(btn => btn.textContent === categoryParam);
                    if (catBtn) {
                        catBtn.click();
                    }
                    if (subcategoryParam) {
                        setTimeout(() => {
                            const subcatRow = document.getElementById('selectorSubcategory');
                            const subcatBtn = Array.from(subcatRow.children).find(btn => btn.textContent === subcategoryParam);
                            if (subcatBtn) {
                                subcatBtn.click();
                            }
                        }, 100);
                    }
                }
            }

            // Markeer de geselecteerde knop
            function highlightSelectedBtn(parentElement, clickedButton) {
                const allButtons = parentElement.querySelectorAll('button');
                allButtons.forEach(btn => btn.classList.remove('selected'));
                clickedButton.classList.add('selected');
            }

            // Toon unieke categorieën en update de URL
            function showCategories() {
                const categories = [...new Set(allProducts.map(p => p.categorie))];
                const categoryRow = document.getElementById('selectorCategory');
                categoryRow.innerHTML = '';
                categories.forEach(cat => {
                    const btn = document.createElement('button');
                    btn.textContent = cat;
                    btn.onclick = () => {
                        selectedCategory = cat;
                        selectedSubcategory = null;
                        selectedProduct = null;
                        highlightSelectedBtn(categoryRow, btn);
                        updateURLWithSelection(cat);
                        showSubcategories(cat);
                    };
                    categoryRow.appendChild(btn);
                });
                // Reset subcategorie- en productrijen
                const subcatRow = document.getElementById('selectorSubcategory');
                subcatRow.innerHTML = '';
                subcatRow.style.display = 'none';
                const prodRow = document.getElementById('selectorProduct');
                prodRow.innerHTML = '';
                prodRow.style.display = 'none';
            }

            // Toon subcategorieën en update de URL
            function showSubcategories(category) {
                const filtered = allProducts.filter(p => p.categorie === category);
                const subcategories = [...new Set(filtered.map(p => p.subcategorie))];
                const subcatRow = document.getElementById('selectorSubcategory');
                subcatRow.innerHTML = '';
                subcategories.forEach(subcat => {
                    const btn = document.createElement('button');
                    btn.textContent = subcat;
                    btn.onclick = () => {
                        selectedSubcategory = subcat;
                        selectedProduct = null;
                        highlightSelectedBtn(subcatRow, btn);
                        updateURLWithSelection(selectedCategory, subcat);
                        showProducts(category, subcat);
                    };
                    subcatRow.appendChild(btn);
                });
                document.getElementById('selectorProduct').innerHTML = '';
                document.getElementById('selectorProduct').style.display = 'none';
                subcatRow.style.display = 'flex';
            }

            // Toon de producten voor de gekozen categorie en subcategorie
            function showProducts(category, subcategory) {
                const filtered = allProducts.filter(p => p.categorie === category && p.subcategorie === subcategory);
                const prodRow = document.getElementById('selectorProduct');
                prodRow.innerHTML = '';
                filtered.forEach(product => {
                    const btn = document.createElement('button');
                    btn.textContent = product.TypeNummer;
                    checkProductPage(product.TypeNummer, btn);
                    btn.onclick = async () => {
                        // Reset de file-input en gerelateerde preview elementen
                        document.getElementById('avatar').value = '';
                        document.getElementById('foto_preview').style.display = 'none';
                        document.getElementById('foto_filename').textContent = "";
                        selectedProduct = product;
                        highlightSelectedBtn(prodRow, btn);
                        const productData = await fetchProductById(product.id);
                        document.getElementById('product-id').value = productData.id;
                        document.getElementById('categorie').value = productData.categorie;
                        document.getElementById('subcategorie').value = productData.subcategorie;
                        document.getElementById('TypeNummer').value = productData.TypeNummer;
                        quill.root.innerHTML = productData.omschrijving;
                        quillSticker.root.innerHTML = productData.sticker_text || '';
                        document.getElementById('prijsstaffel').value = productData.prijsstaffel;
                        document.getElementById('aantal_per_doos').value = productData.aantal_per_doos;
                        document.getElementById('USP').value = stripP(productData.USP);
                        document.getElementById('leverbaar').checked = (productData.leverbaar === 'ja');



                        document.getElementById('save-button').classList.add('hidden');
                    };
                    prodRow.appendChild(btn);
                });
                prodRow.style.display = 'flex';
            }

            // Reset formulier
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
                document.getElementById('leverbaar').checked = true;
                // Reset foto preview
                document.getElementById('foto_preview').style.display = 'none';
                document.getElementById('foto_filename').textContent = "";
                document.getElementById('save-button').classList.remove('hidden');
                isEditingNewProduct = false;
            }

            // Functie om USP per regel tussen <p> tags op te slaan
            function wrapUSP(text) {
                let lines = text.split('\n').map(line => line.trim()).filter(line => line !== "");
                return lines.map(line => `<p>${line}</p>`).join("");
            }

            // Functie om de <p> tags te verwijderen voor weergave in de textarea
            function stripP(html) {
                return html.replace(/<\/p>\s*<p>/g, "\n").replace(/<\/?p>/g, "").trim();
            }

            async function autoSave(event) {
                const id = document.getElementById('product-id').value;
                if (!id || isEditingNewProduct) return;
                const data = {
                    id: id,
                    categorie: document.getElementById('categorie').value,
                    subcategorie: document.getElementById('subcategorie').value,
                    TypeNummer: document.getElementById('TypeNummer').value,
                    omschrijving: quill.root.innerHTML,
                    sticker_text: quillSticker.root.innerHTML,
                    prijsstaffel: document.getElementById('prijsstaffel').value,
                    aantal_per_doos: document.getElementById('aantal_per_doos').value,
                    USP: wrapUSP(document.getElementById('USP').value),
                    leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee'
                };
                await saveProduct(data, false);
            }

            function detectNewProduct(event) {
                if (!document.getElementById('product-id').value && !isEditingNewProduct) {
                    isEditingNewProduct = true;
                    document.getElementById('save-button').classList.remove('hidden');
                }
            }

            // Nieuwe eventlistener voor de file-upload: verstuurt het bestand naar de server, waar deze wordt verkleind en opgeslagen.
            document.getElementById('avatar').addEventListener('change', async (event) => {
                const file = event.target.files[0];
                if (!file) return;
                // Zorg dat er een product (TypeNummer) geselecteerd is
                const typeNummer = document.getElementById('TypeNummer').value;
                if (!typeNummer) {
                    showSnackbar("Selecteer eerst een product.");
                    return;
                }
                const formData = new FormData();
                formData.append('avatar', file);
                try {
                    const response = await fetch(`producten_beheer.php?action=upload_image&product=${encodeURIComponent(typeNummer)}`, {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        showSnackbar("Afbeelding succesvol geüpload en verkleind.");
                        document.getElementById('foto_preview').src = result.image_url;
                        document.getElementById('foto_preview').style.display = 'block';
                        document.getElementById('foto_filename').textContent = file.name;
                    } else {
                        showSnackbar("Fout bij het uploaden van de afbeelding: " + result.error);
                    }
                } catch (error) {
                    showSnackbar("Fout bij het uploaden van de afbeelding.");
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                initProductSelector();

                // Input events voor autosave
                document.querySelectorAll('#right-pane input, #right-pane textarea').forEach(element => {
                    element.addEventListener('input', (event) => {
                        detectNewProduct(event);
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

                // Kopieer-knop: maak een nieuw product, sla op en selecteer het direct
                document.getElementById('copy-button').addEventListener('click', async () => {
                    const productIdField = document.getElementById('product-id');
                    const typeNummerInput = document.getElementById('TypeNummer');
                    if (!productIdField.value) {
                        showSnackbar("Selecteer eerst een product om te kopiëren.");
                        return;
                    }
                    if (!typeNummerInput.value.endsWith("-KOPIE")) {
                        typeNummerInput.value = typeNummerInput.value + "-KOPIE";
                    }
                    const data = {
                        categorie: document.getElementById('categorie').value,
                        subcategorie: document.getElementById('subcategorie').value,
                        TypeNummer: document.getElementById('TypeNummer').value,
                        omschrijving: quill.root.innerHTML,
                        sticker_text: quillSticker.root.innerHTML,
                        prijsstaffel: document.getElementById('prijsstaffel').value,
                        aantal_per_doos: document.getElementById('aantal_per_doos').value,
                        USP: wrapUSP(document.getElementById('USP').value),
                        leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee'
                    };
                    const newProduct = await saveProduct(data, true);
                    showSnackbar("Productgegevens gekopieerd en opgeslagen.");
                    updateURLWithSelection(data.categorie, data.subcategorie);
                    await initProductSelector();
                    setTimeout(() => {
                        const prodRow = document.getElementById('selectorProduct');
                        const newBtn = Array.from(prodRow.children).find(btn => btn.textContent === newProduct.TypeNummer);
                        if (newBtn) {
                            newBtn.click();
                        }
                    }, 200);
                });

                // Verwijder-knop
                document.getElementById('delete-button').addEventListener('click', async () => {
                    const productId = document.getElementById('product-id').value;
                    if (!productId) {
                        showSnackbar("Selecteer eerst een product om te verwijderen.");
                        return;
                    }
                    const userConfirmed = await customConfirm("Weet je zeker dat je dit product wilt verwijderen?");
                    if (!userConfirmed) return;
                    try {
                        const response = await fetch('api_products.php', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: productId
                            })
                        });
                        const result = await response.json();
                        if (result.success) {
                            showSnackbar("Product succesvol verwijderd.");
                            resetForm();
                            initProductSelector();
                        } else {
                            showSnackbar("Fout bij verwijderen: " + result.error);
                        }
                    } catch (error) {
                        showSnackbar("Er is een fout opgetreden bij het verwijderen.");
                    }
                });

                // Bewaren-knop
                document.getElementById('save-button').addEventListener('click', async () => {
                    const data = {
                        categorie: document.getElementById('categorie').value,
                        subcategorie: document.getElementById('subcategorie').value,
                        TypeNummer: document.getElementById('TypeNummer').value,
                        omschrijving: quill.root.innerHTML,
                        sticker_text: quillSticker.root.innerHTML,
                        prijsstaffel: document.getElementById('prijsstaffel').value,
                        aantal_per_doos: document.getElementById('aantal_per_doos').value,
                        USP: wrapUSP(document.getElementById('USP').value),
                        leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee'
                    };
                    await saveProduct(data, true);
                    resetForm();
                    document.getElementById('save-button').classList.add('hidden');
                });

                document.getElementById('new-button').addEventListener('click', resetForm);
            });
        </script>

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
        </script>
    </main>
    <?php
    include_once __DIR__ . '/incs/bottom.php';
    ?>