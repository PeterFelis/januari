<?php
// producten_beheer.php
ob_start();

$menu = "beheer";
include_once __DIR__ . '/incs/sessie.php'; // Zorg dat dit pad correct is

if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php"); // Zorg dat dit pad correct is
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    echo "Geen toegang tot deze pagina.";
    exit;
}

// Lees standaardwaarden via de URL
$defaultCategory = isset($_GET['selectedCategory']) ? htmlspecialchars($_GET['selectedCategory'], ENT_QUOTES, 'UTF-8') : "";
$defaultSubcategory = isset($_GET['selectedSubcategory']) ? htmlspecialchars($_GET['selectedSubcategory'], ENT_QUOTES, 'UTF-8') : "";

// Afhandeling van afbeelding upload
if (isset($_GET['action']) && $_GET['action'] === 'upload_image') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        exit;
    }
    if (!isset($_FILES['avatar'])) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Geen bestand geüpload.']);
        exit;
    }
    if (!isset($_GET['product']) || empty($_GET['product'])) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Product niet gespecificeerd.']);
        exit;
    }
    $product = $_GET['product']; // TypeNummer
    $directory = __DIR__ . '/artikelen/' . $product; // Zorg dat dit pad correct is

    if (!is_dir($directory)) {
        if (!mkdir($directory, 0777, true)) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Kon map niet aanmaken: ' . $directory]);
            exit;
        }
    }

    $file = $_FILES['avatar'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Upload fout: ' . $file['error']]);
        exit;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Alleen JPG, JPEG en PNG bestanden zijn toegestaan.']);
        exit;
    }

    $destPath = $directory . '/Pfoto.' . $extension;

    function createResizedImage($srcPath, $destPath, $extension, $newWidth = 300)
    {
        $sourceImage = null;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $sourceImage = @imagecreatefromjpeg($srcPath);
                break;
            case 'png':
                $sourceImage = @imagecreatefrompng($srcPath);
                break;
            default:
                return false;
        }

        if (!$sourceImage) return false;

        list($width, $height) = @getimagesize($srcPath);
        if ($width == 0 || $height == 0) { // Voorkom division by zero
            imagedestroy($sourceImage);
            return false;
        }
        $newHeight = intval(($height / $width) * $newWidth);
        $newImage = @imagecreatetruecolor($newWidth, $newHeight);

        if (!$newImage) {
            imagedestroy($sourceImage);
            return false;
        }

        if ($extension === 'png') {
            @imagealphablending($newImage, false);
            @imagesavealpha($newImage, true);
        }

        @imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $saveSuccess = false;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $saveSuccess = @imagejpeg($newImage, $destPath, 90);
                break;
            case 'png':
                $saveSuccess = @imagepng($newImage, $destPath); // Compressie optioneel: , 9
                break;
        }

        @imagedestroy($sourceImage);
        @imagedestroy($newImage);
        return $saveSuccess;
    }

    if (createResizedImage($file['tmp_name'], $destPath, $extension)) {
        ob_clean();
        header('Content-Type: application/json');
        // Stuur de URL relatief aan de webroot
        $webRootRelativePath = 'artikelen/' . rawurlencode($product) . '/Pfoto.' . $extension;
        echo json_encode(['success' => true, 'image_url' => $webRootRelativePath]);
        exit;
    } else {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Fout bij het verkleinen van de afbeelding.']);
        exit;
    }
}

$title = 'Product beheer';
include_once __DIR__ . '/incs/top.php'; // Zorg dat dit pad correct is
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

    .selection-category,
    .selection-subcategory,
    .selection-products {
        margin-bottom: 20px;
    }

    .selection-category h2,
    .selection-subcategory h3,
    .selection-products h3 {
        margin: 0 0 10px;
    }

    /* Styling voor Categorie/Subcategorie knoppen */
    .selection-btn {
        padding: 5px 10px;
        margin: 3px;
        border: 1px solid #ccc;
        background: #fff;
        cursor: pointer;
        color: #333;
        border-radius: 3px;
        font-size: 13px;
    }

    .selection-btn.selected {
        background-color: #ffcc66;
        color: #000;
        font-weight: bold;
    }

    /* Styling voor Product lijst items */
    .product-list-entry {
        padding: 8px 12px;
        margin: 4px;
        border: 1px solid #ccc;
        background-color: #fff;
        border-radius: 3px;
        text-align: left;
        cursor: pointer;
        font-family: inherit;
        line-height: 1.4;
        box-sizing: border-box;
        color: #333;
    }

    .product-list-entry:hover {
        background-color: #f5f5f5;
    }

    .product-item-name {
        display: block;
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
        pointer-events: none;
    }

    .product-item-view-action {
        display: block;
        font-size: 12px;
        color: #0066cc;
        text-decoration: none;
    }

    .product-item-view-action:hover {
        text-decoration: underline;
    }

    /* Stijl voor formulierelementen (voorbeeld, pas aan naar je thema) */
    #right-pane form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    #right-pane form input[type="text"],
    #right-pane form input[type="number"],
    #right-pane form textarea,
    .ql-toolbar,
    .ql-container {
        margin-bottom: 15px;
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    #right-pane form input[type="checkbox"] {
        margin-right: 5px;
    }

    #right-pane form button {
        padding: 10px 15px;
        margin-right: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    #right-pane form button:hover {
        background-color: #0056b3;
    }

    #right-pane form #delete-button {
        background-color: #dc3545;
    }

    #right-pane form #delete-button:hover {
        background-color: #c82333;
    }

    .input-row {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .input-row .form-group {
        flex: 1;
    }

    .sticker-prijs-container {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .sticker-prijs-container>div {
        flex: 1;
    }


    main {
        margin-top: 10rem;
        /* Pas aan als je menu een andere hoogte heeft */
    }

    #snackbar {
        visibility: hidden;
        min-width: 250px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1000;
        left: 50%;
        bottom: 30px;
        font-size: 17px;
        transform: translateX(-50%);
    }

    #snackbar.show {
        visibility: visible;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }

    @keyframes fadein {
        from {
            bottom: 0;
            opacity: 0;
        }

        to {
            bottom: 30px;
            opacity: 1;
        }
    }

    @keyframes fadeout {
        from {
            bottom: 30px;
            opacity: 1;
        }

        to {
            bottom: 0;
            opacity: 0;
        }
    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; // Zorg dat dit pad correct is 
    ?>
    <main>
        <div id="snackbar"></div>
        <div class="container">
            <div id="left-pane">
                <h2>Producten</h2>
                <div id="cats"></div>
                <div id="subs"></div>
                <div id="prods"></div>
            </div>
            <div id="right-pane">
                <h2>Product Beheren</h2>
                <form onsubmit="return false;"> {/* Voorkom default form submit */}
                    <input type="hidden" id="product-id">
                    <div class="input-row">
                        <div class="form-group">
                            <label for="categorie">Categorie:</label>
                            <input type="text" id="categorie" value="<?php echo $defaultCategory; ?>">
                        </div>
                        <div class="form-group">
                            <label for="subcategorie">Subcategorie:</label>
                            <input type="text" id="subcategorie" value="<?php echo $defaultSubcategory; ?>">
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="TypeNummer">TypeNummer:</label>
                            <input type="text" id="TypeNummer" required>
                        </div>
                        <div class="form-group">
                            <label for="aantal_per_doos">Aantal per doos:</label>
                            <input type="number" id="aantal_per_doos">
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="leverbaar">Leverbaar:</label>
                            <input type="checkbox" id="leverbaar" checked>
                        </div>
                        <div class="form-group">
                            <label for="hoofd_product">Hoofd Product:</label>
                            <input type="text" id="hoofd_product">
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="avatar">Productfoto:</label>
                            <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="form-group">
                            <div id="foto_preview_container">
                                <img id="foto_preview" style="max-width: 100px; max-height:100px; display: none; border:1px solid #ccc; margin-top:5px;" alt="Foto preview" />
                                <span id="foto_filename" style="display:block; margin-top:5px; font-size:0.9em;"></span>
                            </div>
                        </div>
                    </div>
                    <div id="omschrijvingveld" class="form-group">
                        <label for="omschrijving">Omschrijving:</label>
                        <div id="omschrijving"></div> {/* Quill editor */}
                    </div>
                    <div class="sticker-prijs-container">
                        <div class="form-group">
                            <label for="sticker_text">Sticker Tekst:</label>
                            <div id="sticker_text"></div> {/* Quill editor */}
                        </div>
                        <div class="form-group">
                            <label for="prijsstaffel">Prijsstaffel:</label>
                            <textarea id="prijsstaffel" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="USP">USP's (elke regel een nieuwe USP):</label>
                        <textarea rows="6" id="USP" placeholder="Elke regel wordt opgeslagen als een apart <p> element"></textarea>
                    </div>
                    <br>
                    <button type="button" id="save-button">Bewaren & Vernieuw</button>
                    <button type="button" id="new-button">Leegmaken</button>
                    <button type="button" id="copy-button">Kopieer</button>
                    <button type="button" id="delete-button">Verwijder</button>
                </form>
            </div>
        </div>

        <script>
            let allProducts = [];
            let selCat = "<?php echo $defaultCategory; ?>";
            let selSub = "<?php echo $defaultSubcategory; ?>";
            let isEditingNewProduct = false;
            let productPageExistence = {}; // Voor status van productpagina's

            // Quill editors
            var quillOmschrijving, quillSticker;

            function showSnackbar(message) {
                const snackbar = document.getElementById('snackbar');
                snackbar.textContent = message;
                snackbar.className = "show";
                setTimeout(() => {
                    snackbar.className = snackbar.className.replace("show", "");
                }, 3000);
            }

            async function fetchProductById(id) {
                const response = await fetch(`api_products.php?id=${id}`); // Zorg dat dit pad correct is
                if (!response.ok) throw new Error(`API product details faalde: ${response.status}`);
                return await response.json();
            }

            async function saveProduct(data, isNew) {
                const method = isNew ? 'POST' : 'PUT';
                const response = await fetch('api_products.php', { // Zorg dat dit pad correct is
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                if (!response.ok) throw new Error(`API save product faalde: ${response.status}`);
                return await response.json();
            }

            function resetForm() {
                document.getElementById('product-id').value = '';
                document.getElementById('TypeNummer').value = '';
                if (quillOmschrijving) quillOmschrijving.root.innerHTML = '';
                if (quillSticker) quillSticker.root.innerHTML = '';
                document.getElementById('prijsstaffel').value = '';
                document.getElementById('aantal_per_doos').value = '';
                document.getElementById('USP').value = '';
                document.getElementById('leverbaar').checked = true;
                document.getElementById('hoofd_product').value = '';
                document.getElementById('foto_preview').style.display = 'none';
                document.getElementById('foto_preview').src = '#';
                document.getElementById('foto_filename').textContent = "";
                document.getElementById('avatar').value = ''; // Reset file input
                document.getElementById('save-button').classList.remove('hidden');
                isEditingNewProduct = true;
                // Categorie en subcategorie blijven behouden zoals in originele code
            }

            function wrapUSP(text) {
                return text.split('\n').map(line => line.trim()).filter(line => line !== "").map(line => `<p>${line}</p>`).join("");
            }

            function stripP(html) {
                if (!html) return "";
                return html.replace(/<\/p>\s*<p>/g, "\n").replace(/<\/?p>/g, "").trim();
            }

            let autoSaveTimeout;

            function triggerAutoSave() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(async () => {
                    const id = document.getElementById('product-id').value;
                    if (!id || isEditingNewProduct) return;

                    console.log("Autosaving product ID:", id);
                    const data = {
                        id: id,
                        categorie: document.getElementById('categorie').value,
                        subcategorie: document.getElementById('subcategorie').value,
                        TypeNummer: document.getElementById('TypeNummer').value,
                        omschrijving: quillOmschrijving ? quillOmschrijving.root.innerHTML : '',
                        sticker_text: quillSticker ? quillSticker.root.innerHTML : '',
                        prijsstaffel: document.getElementById('prijsstaffel').value,
                        aantal_per_doos: document.getElementById('aantal_per_doos').value,
                        USP: wrapUSP(document.getElementById('USP').value),
                        leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee',
                        hoofd_product: document.getElementById('hoofd_product').value
                        // Fotolink wordt niet meegestuurd bij autosave data; die wordt apart beheerd.
                    };
                    try {
                        await saveProduct(data, false);
                        // Optioneel: showSnackbar("Wijzigingen automatisch opgeslagen.");
                    } catch (error) {
                        console.error("Autosave error:", error);
                        showSnackbar("Fout bij automatisch opslaan.");
                    }
                }, 1000); // Wacht 1 seconde na laatste wijziging
            }


            function detectNewProduct() {
                if (!document.getElementById('product-id').value && !isEditingNewProduct) {
                    isEditingNewProduct = true;
                    document.getElementById('save-button').classList.remove('hidden');
                }
            }

            document.getElementById('avatar').addEventListener('change', async (event) => {
                const file = event.target.files[0];
                if (!file) return;

                const typeNummer = document.getElementById('TypeNummer').value;
                if (!typeNummer) {
                    showSnackbar("Vul eerst een TypeNummer in of selecteer een bestaand product.");
                    event.target.value = ''; // Reset file input
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
                    if (result.success && result.image_url) {
                        showSnackbar("Afbeelding succesvol geüpload.");
                        document.getElementById('foto_preview').src = result.image_url + '?t=' + new Date().getTime(); // Cache buster
                        document.getElementById('foto_preview').style.display = 'block';
                        document.getElementById('foto_filename').textContent = file.name;
                        // Trigger autosave als een bestaand product bewerkt wordt om de (impliciete) link op te slaan
                        if (document.getElementById('product-id').value && !isEditingNewProduct) {
                            triggerAutoSave();
                        }
                    } else {
                        showSnackbar("Fout bij upload: " + (result.error || "Onbekende fout"));
                    }
                } catch (error) {
                    console.error("Upload error:", error);
                    showSnackbar("Netwerkfout bij uploaden afbeelding.");
                }
            });

            async function initSelection() {
                try {
                    const resp = await fetch('api_products.php'); // Zorg dat dit pad correct is
                    if (!resp.ok) throw new Error(`API producten faalde: ${resp.status}`);
                    allProducts = await resp.json();

                    if (allProducts && allProducts.length > 0) {
                        const typeNummers = allProducts.map(p => p.TypeNummer).filter(tn => tn && typeof tn === 'string' && tn.trim() !== '');
                        if (typeNummers.length > 0) {
                            try {
                                const pageStatusResp = await fetch('check_pagina_status.php', { // Zorg dat dit pad correct is
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        typeNummers: typeNummers
                                    })
                                });
                                if (!pageStatusResp.ok) throw new Error(`Check pagina status faalde: ${pageStatusResp.status}`);
                                productPageExistence = await pageStatusResp.json();
                                if (productPageExistence.error) {
                                    console.error("Fout van check_pagina_status.php:", productPageExistence.error);
                                    productPageExistence = {};
                                }
                            } catch (pageStatusError) {
                                console.error("Fout bij ophalen paginastatus:", pageStatusError);
                                productPageExistence = {}; // Ga door zonder deze info bij fout
                            }
                        }
                    }
                    renderCats();
                } catch (e) {
                    console.error('Fout bij laden producten of paginastatus:', e);
                    showSnackbar('Fout bij laden producten.');
                }
            }

            function renderCats() {
                const c = document.getElementById('cats');
                c.innerHTML = '<h3>Categorieën</h3>';
                if (!allProducts || allProducts.length === 0) return;
                const cats = [...new Set(allProducts.map(p => p.categorie).filter(cat => cat))];
                cats.sort().forEach(cat => {
                    const btn = document.createElement('button');
                    btn.textContent = cat;
                    btn.className = selCat === cat ? 'selection-btn selected' : 'selection-btn';
                    btn.onclick = () => {
                        selCat = cat;
                        selSub = null;
                        renderCats();
                        renderSubs();
                        clearProdsAndForm();
                    };
                    c.appendChild(btn);
                });
                if (selCat) renderSubs();
                else clearProdsAndForm();
            }

            function renderSubs() {
                const s = document.getElementById('subs');
                s.innerHTML = '';
                if (!selCat || !allProducts || allProducts.length === 0) return;
                s.innerHTML = '<h4>Subcategorieën</h4>';
                const subs = [...new Set(
                    allProducts.filter(p => p.categorie === selCat).map(p => p.subcategorie).filter(sub => sub)
                )];
                subs.sort().forEach(sub => {
                    const btn = document.createElement('button');
                    btn.textContent = sub;
                    btn.className = selSub === sub ? 'selection-btn selected' : 'selection-btn';
                    btn.onclick = () => {
                        selSub = sub;
                        renderSubs();
                        renderProds();
                        resetFormFieldsOnly();
                    };
                    s.appendChild(btn);
                });
                if (selSub) renderProds();
                else {
                    clearProds();
                    resetFormFieldsOnly();
                }
            }

            function clearProdsAndForm() {
                clearProds();
                resetForm(); // Volledige reset als geen categorie/sub geselecteerd
            }

            function resetFormFieldsOnly() { // Reset alleen de product specifieke velden, niet categorie/subcategorie
                document.getElementById('product-id').value = '';
                document.getElementById('TypeNummer').value = '';
                if (quillOmschrijving) quillOmschrijving.root.innerHTML = '';
                if (quillSticker) quillSticker.root.innerHTML = '';
                document.getElementById('prijsstaffel').value = '';
                document.getElementById('aantal_per_doos').value = '';
                document.getElementById('USP').value = '';
                document.getElementById('leverbaar').checked = true;
                document.getElementById('hoofd_product').value = '';
                document.getElementById('foto_preview').style.display = 'none';
                document.getElementById('foto_preview').src = '#';
                document.getElementById('foto_filename').textContent = "";
                document.getElementById('avatar').value = '';
                document.getElementById('save-button').classList.remove('hidden');
                isEditingNewProduct = true;
            }


            function renderProds() {
                const p = document.getElementById('prods');
                p.innerHTML = '';
                if (!selCat || !selSub || !allProducts || allProducts.length === 0) return;

                p.innerHTML = '<h4>Producten</h4>';
                const prodsInSelection = allProducts
                    .filter(prod => prod.categorie === selCat && prod.subcategorie === selSub)
                    .sort((a, b) => (a.TypeNummer || "").localeCompare(b.TypeNummer || ""));

                prodsInSelection.forEach(prod => {
                    const productEntryButton = document.createElement('button');
                    productEntryButton.className = 'product-list-entry';
                    productEntryButton.type = 'button';
                    productEntryButton.title = `Bewerk product: ${prod.TypeNummer}`;
                    productEntryButton.onclick = async () => {
                        try {
                            const data = await fetchProductById(prod.id);
                            document.getElementById('product-id').value = data.id || '';
                            document.getElementById('categorie').value = data.categorie || selCat;
                            document.getElementById('subcategorie').value = data.subcategorie || selSub;
                            document.getElementById('TypeNummer').value = data.TypeNummer || '';
                            if (quillOmschrijving) quillOmschrijving.root.innerHTML = data.omschrijving || '';
                            if (quillSticker) quillSticker.root.innerHTML = data.sticker_text || '';
                            document.getElementById('prijsstaffel').value = data.prijsstaffel || '';
                            document.getElementById('aantal_per_doos').value = data.aantal_per_doos || '';
                            document.getElementById('USP').value = stripP(data.USP || '');
                            document.getElementById('leverbaar').checked = (data.leverbaar === 'ja');
                            document.getElementById('hoofd_product').value = data.hoofd_product || '';

                            const fotoPreview = document.getElementById('foto_preview');
                            const fotoFilename = document.getElementById('foto_filename');
                            if (data.image_url) { // Dit veld moet door je API voor productdetails worden geleverd
                                fotoPreview.src = data.image_url + '?t=' + new Date().getTime();
                                fotoPreview.style.display = 'block';
                                fotoFilename.textContent = data.image_url.split('/').pop();
                            } else {
                                // Probeer een standaard pad als de API geen image_url geeft
                                const defaultImgPath = `artikelen/${encodeURIComponent(data.TypeNummer)}/Pfoto.jpg`; // Aanname: JPG
                                // Hier zou je kunnen proberen of dit bestand bestaat via een extra check,
                                // of een placeholder tonen. Voor nu, leeg laten.
                                fotoPreview.style.display = 'none';
                                fotoPreview.src = '#';
                                fotoFilename.textContent = '';
                            }
                            document.getElementById('avatar').value = '';
                            document.getElementById('save-button').classList.add('hidden');
                            isEditingNewProduct = false;
                        } catch (e) {
                            console.error('Fout bij ophalen productdetails:', e);
                            showSnackbar('Fout bij ophalen productdetails.');
                        }
                    };

                    const productNameDisplay = document.createElement('span');
                    productNameDisplay.className = 'product-item-name';
                    productNameDisplay.textContent = prod.TypeNummer || "Onbekend TypeNummer";
                    productEntryButton.appendChild(productNameDisplay);

                    if (prod.TypeNummer && productPageExistence[prod.TypeNummer] === true) {
                        const productViewAction = document.createElement('span');
                        productViewAction.className = 'product-item-view-action';
                        productViewAction.innerHTML = 'Bekijk ↗';
                        productViewAction.title = `Open productpagina: ${prod.TypeNummer}`;
                        productViewAction.onclick = (event) => {
                            event.stopPropagation();
                            const productPageUrl = `artikelen/${encodeURIComponent(prod.TypeNummer)}/index.php`;
                            window.open(productPageUrl, '_blank');
                        };
                        productEntryButton.appendChild(productViewAction);
                    }
                    p.appendChild(productEntryButton);
                });
            }

            function clearProds() {
                document.getElementById('prods').innerHTML = '';
            }

            document.addEventListener('DOMContentLoaded', () => {
                quillOmschrijving = new Quill('#omschrijving', {
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
                quillSticker = new Quill('#sticker_text', {
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

                document.querySelectorAll('#right-pane input, #right-pane textarea').forEach(element => {
                    if (element.id === 'avatar') return; // Sla file input over voor autosave trigger
                    element.addEventListener('input', () => {
                        detectNewProduct();
                        triggerAutoSave();
                    });
                });
                quillOmschrijving.on('text-change', () => {
                    detectNewProduct();
                    triggerAutoSave();
                });
                quillSticker.on('text-change', () => {
                    detectNewProduct();
                    triggerAutoSave();
                });
                document.getElementById('leverbaar').addEventListener('change', () => {
                    detectNewProduct();
                    triggerAutoSave();
                });


                document.getElementById('copy-button').addEventListener('click', async () => {
                    const productIdField = document.getElementById('product-id');
                    const typeNummerInput = document.getElementById('TypeNummer');
                    if (!typeNummerInput.value) { // Check TypeNummer ipv product-id, want dat is er misschien nog niet
                        showSnackbar("Vul/selecteer een product om te kopiëren.");
                        return;
                    }
                    let newTypeNummer = typeNummerInput.value;
                    if (!newTypeNummer.endsWith("-KOPIE")) {
                        newTypeNummer = newTypeNummer + "-KOPIE";
                    }

                    const data = { // Geen ID hier, want het is een nieuw product
                        categorie: document.getElementById('categorie').value,
                        subcategorie: document.getElementById('subcategorie').value,
                        TypeNummer: newTypeNummer,
                        omschrijving: quillOmschrijving.root.innerHTML,
                        sticker_text: quillSticker.root.innerHTML,
                        prijsstaffel: document.getElementById('prijsstaffel').value,
                        aantal_per_doos: document.getElementById('aantal_per_doos').value,
                        USP: wrapUSP(document.getElementById('USP').value),
                        leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee',
                        hoofd_product: document.getElementById('hoofd_product').value
                    };
                    try {
                        const newProduct = await saveProduct(data, true); // isNew = true
                        if (newProduct && newProduct.id) {
                            showSnackbar(`Product gekopieerd als ${newTypeNummer} en opgeslagen.`);
                            // Update de UI direct met de gekopieerde data
                            document.getElementById('product-id').value = newProduct.id;
                            document.getElementById('TypeNummer').value = newTypeNummer;
                            isEditingNewProduct = false; // Je bewerkt nu dit "nieuwe" gekopieerde product
                            document.getElementById('save-button').classList.add('hidden');

                            // Voeg toe aan allProducts en productPageExistence (aanname: nieuwe kopie heeft nog geen pagina)
                            allProducts.push({
                                ...data,
                                id: newProduct.id
                            });
                            productPageExistence[newTypeNummer] = false; // Nieuwe kopie heeft nog geen pagina
                            renderProds(); // Her-render de productlijst
                        } else {
                            showSnackbar("Fout bij kopiëren: " + (newProduct.error || "Onbekende serverfout"));
                        }
                    } catch (e) {
                        showSnackbar("Fout bij kopiëren: " + e.message);
                    }
                });

                document.getElementById('delete-button').addEventListener('click', async () => {
                    const productId = document.getElementById('product-id').value;
                    if (!productId) {
                        showSnackbar("Selecteer eerst een product om te verwijderen.");
                        return;
                    }
                    if (!confirm("Weet je zeker dat je dit product wilt verwijderen? Dit kan niet ongedaan worden gemaakt.")) return;
                    try {
                        const response = await fetch('api_products.php', { // Zorg dat dit pad correct is
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
                            // Verwijder uit allProducts en productPageExistence
                            const deletedTypeNummer = document.getElementById('TypeNummer').value;
                            allProducts = allProducts.filter(p => p.id !== parseInt(productId));
                            delete productPageExistence[deletedTypeNummer];

                            resetForm();
                            renderProds(); // Her-render de productlijst
                            // Optioneel: herlaad categorie/subcategorie
                            // window.location.href = `producten_beheer.php?selectedCategory=${encodeURIComponent(selCat)}&selectedSubcategory=${encodeURIComponent(selSub)}`;
                        } else {
                            showSnackbar("Fout bij verwijderen: " + (result.error || "Onbekende serverfout"));
                        }
                    } catch (e) {
                        showSnackbar("Fout bij verwijderen: " + e.message);
                    }
                });

                document.getElementById('save-button').addEventListener('click', () => {
                    // Deze knop slaat op en herlaadt de pagina om selecties te behouden
                    // De autosave zou de data al moeten hebben opgeslagen als het een bestaand product is.
                    // Als het een NIEUW product is (isEditingNewProduct = true), moeten we expliciet opslaan.
                    if (isEditingNewProduct && document.getElementById('TypeNummer').value) {
                        const data = {
                            categorie: document.getElementById('categorie').value,
                            subcategorie: document.getElementById('subcategorie').value,
                            TypeNummer: document.getElementById('TypeNummer').value,
                            omschrijving: quillOmschrijving.root.innerHTML,
                            sticker_text: quillSticker.root.innerHTML,
                            prijsstaffel: document.getElementById('prijsstaffel').value,
                            aantal_per_doos: document.getElementById('aantal_per_doos').value,
                            USP: wrapUSP(document.getElementById('USP').value),
                            leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee',
                            hoofd_product: document.getElementById('hoofd_product').value
                        };
                        saveProduct(data, true).then(newProd => {
                            if (newProd && newProd.id) {
                                showSnackbar("Nieuw product opgeslagen.");
                                window.location.href = `producten_beheer.php?selectedCategory=${encodeURIComponent(selCat)}&selectedSubcategory=${encodeURIComponent(selSub)}`;
                            } else {
                                showSnackbar("Fout bij opslaan nieuw product: " + (newProd.error || "Onbekend"));
                            }
                        }).catch(err => showSnackbar("Fout: " + err.message));
                    } else {
                        window.location.href = `producten_beheer.php?selectedCategory=${encodeURIComponent(selCat)}&selectedSubcategory=${encodeURIComponent(selSub)}`;
                    }
                });

                document.getElementById('new-button').addEventListener('click', resetForm);

                function saveCategorySelectionToSession() {
                    if (document.getElementById('categorie').value) sessionStorage.setItem('selectedCategory', document.getElementById('categorie').value);
                    if (document.getElementById('subcategorie').value) sessionStorage.setItem('selectedSubcategory', document.getElementById('subcategorie').value);
                }

                function restoreCategorySelectionFromSession() {
                    const storedCategory = sessionStorage.getItem('selectedCategory');
                    const storedSubcategory = sessionStorage.getItem('selectedSubcategory');
                    if (storedCategory) selCat = storedCategory;
                    if (storedSubcategory) selSub = storedSubcategory;
                    // De inputvelden worden gevuld door de PHP $defaultCategory/$defaultSubcategory,
                    // maar selCat/selSub moeten overeenkomen voor de JS logica.
                    document.getElementById('categorie').value = selCat;
                    document.getElementById('subcategorie').value = selSub;
                }

                window.addEventListener('beforeunload', saveCategorySelectionToSession);
                restoreCategorySelectionFromSession(); // Bij laden
                initSelection(); // Start het laden van data
            });
        </script>
    </main>
    <?php include_once __DIR__ . '/incs/bottom.php'; // Zorg dat dit pad correct is 
    ?>
</body>

</html>
<?php
ob_end_flush();
?>