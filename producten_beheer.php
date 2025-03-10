<?php
// producten_beheer.php
ob_start();

$menu = "beheer";
include_once __DIR__ . '/incs/sessie.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    echo "Geen toegang tot deze pagina.";
    exit;
}

// Lees standaardwaarden via de URL
$defaultCategory = isset($_GET['selectedCategory']) ? $_GET['selectedCategory'] : "";
$defaultSubcategory = isset($_GET['selectedSubcategory']) ? $_GET['selectedSubcategory'] : "";

if (isset($_GET['action']) && $_GET['action'] === 'upload_image') {
    // Upload-afhandeling
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ob_clean(); // Verwijder alle eerder opgevangen output
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
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Product niet gespecificeerd.']);
        exit;
    }
    $product = $_GET['product'];
    $directory = __DIR__ . '/artikelen/' . $product;
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
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
        ob_clean();
        header('Content-Type: application/json');
        $imageUrl = 'artikelen/' . $product . '/Pfoto.' . $extension;
        echo json_encode(['success' => true, 'image_url' => $imageUrl]);
        exit;
    } else {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Fout bij het verkleinen van de afbeelding.']);
        exit;
    }
}

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

    .selection-btn {
        padding: 5px 10px;
        margin: 3px;
        border: 1px solid #ccc;
        background: #f9f9f9;
        cursor: pointer;
    }

    .selection-btn.selected {
        background-color: #ffcc66;
        color: #000;
        font-weight: bold;
    }

    main {
        margin-top: 10rem;
    }

    /* Snackbar CSS */
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
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <div id="snackbar"></div>
        <div class="container">
            <!-- Linkerpaneel: selectie-component -->
            <div id="left-pane">
                <h2>Producten</h2>
                <div id="selectionComponent"></div>
            </div>
            <!-- Rechterpaneel: bewerkformulier -->
            <div id="right-pane">
                <h2>Product Beheren</h2>
                <form>
                    <input type="hidden" id="product-id">
                    <div class="input-row">
                        <div class="form-group">
                            <label for="categorie">Categorie:</label>
                            <input type="text" id="categorie" value="<?php echo htmlspecialchars($defaultCategory); ?>">
                        </div>
                        <div class="form-group">
                            <label for="subcategorie">Subcategorie:</label>
                            <input type="text" id="subcategorie" value="<?php echo htmlspecialchars($defaultSubcategory); ?>">
                        </div>
                    </div>
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
                            <label for="avatar">Fotolink:</label>
                            <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg">
                        </div>
                    </div>
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
                    <!-- De "Bewaren" knop refresht de pagina -->
                    <button type="button" id="save-button">Bewaren</button>
                    <button type="button" id="new-button">Leegmaken</button>
                    <button type="button" id="copy-button">Kopieer</button>
                    <button type="button" id="delete-button">Verwijder</button>
                </form>
            </div>
        </div>
        <script src="/incs/selection_component.js"></script>
        <script>
            let isEditingNewProduct = false;

            function showSnackbar(message) {
                const snackbar = document.getElementById('snackbar');
                snackbar.textContent = message;
                snackbar.className = "show";
                setTimeout(() => {
                    snackbar.className = snackbar.className.replace("show", "");
                }, 3000);
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

            // resetForm behoudt categorie en subcategorie
            function resetForm() {
                document.getElementById('product-id').value = '';
                document.getElementById('TypeNummer').value = '';
                quill.root.innerHTML = '';
                quillSticker.root.innerHTML = '';
                document.getElementById('prijsstaffel').value = '';
                document.getElementById('aantal_per_doos').value = '';
                document.getElementById('USP').value = '';
                document.getElementById('leverbaar').checked = true;
                document.getElementById('hoofd_product').value = '';
                document.getElementById('foto_preview').style.display = 'none';
                document.getElementById('foto_filename').textContent = "";
                document.getElementById('save-button').classList.remove('hidden');
                isEditingNewProduct = true;
            }

            function wrapUSP(text) {
                return text.split('\n').map(line => line.trim()).filter(line => line !== "").map(line => `<p>${line}</p>`).join("");
            }

            function stripP(html) {
                return html.replace(/<\/p>\s*<p>/g, "\n").replace(/<\/?p>/g, "").trim();
            }

            async function autoSave() {
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
                    leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee',
                    hoofd_product: document.getElementById('hoofd_product').value
                };
                await saveProduct(data, false);
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

            var quill = new Quill('#omschrijving', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link', 'blockquote', 'code-block'],
                        [{ 'color': [] }, { 'background': [] }]
                    ]
                }
            });

            var quillSticker = new Quill('#sticker_text', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                var selection;
                try {
                    selection = new SelectionComponent({
                        container: document.getElementById('selectionComponent'),
                        showProducts: true,
                        checkProductPage: true,
                        onSelectionChange: async function(selectionData) {
                            if (selectionData.product) {
                                const productData = await fetchProductById(selectionData.product.id);
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
                                document.getElementById('hoofd_product').value = productData.hoofd_product || '';
                                document.getElementById('save-button').classList.add('hidden');
                            }
                        }
                    });
                } catch (error) {
                    console.error("Fout bij initialiseren van SelectionComponent:", error);
                    return;
                }

                function waitForSelectionComponent() {
                    return new Promise((resolve) => {
                        const checkInterval = setInterval(() => {
                            if (selection && selection.products && selection.products.length > 0) {
                                clearInterval(checkInterval);
                                resolve();
                            }
                        }, 100);
                    });
                }

                waitForSelectionComponent().then(() => {
                    const defaultCategory = "<?php echo htmlspecialchars($defaultCategory); ?>";
                    const defaultSubcategory = "<?php echo htmlspecialchars($defaultSubcategory); ?>";
                    if (defaultCategory && typeof selection.setSelected === 'function') {
                        selection.setSelected(defaultCategory, defaultSubcategory);
                    }
                }).catch(error => {
                    console.error("Fout bij wachten op SelectionComponent:", error);
                });

                document.querySelectorAll('#right-pane input, #right-pane textarea').forEach(element => {
                    element.addEventListener('input', () => {
                        detectNewProduct();
                        autoSave();
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
                        leverbaar: document.getElementById('leverbaar').checked ? 'ja' : 'nee',
                        hoofd_product: document.getElementById('hoofd_product').value
                    };
                    const newProduct = await saveProduct(data, true);
                    if (newProduct && newProduct.id) {
                        document.getElementById('product-id').value = newProduct.id;
                        isEditingNewProduct = true;
                    }
                    showSnackbar("Productgegevens gekopieerd en opgeslagen.");
                    saveCategorySelection();
                    setTimeout(() => {
                        window.location.href = "producten_beheer.php?selectedCategory=" + encodeURIComponent(data.categorie) + "&selectedSubcategory=" + encodeURIComponent(data.subcategorie);
                    }, 1500);
                });

                document.getElementById('delete-button').addEventListener('click', async () => {
                    const productId = document.getElementById('product-id').value;
                    if (!productId) {
                        showSnackbar("Selecteer eerst een product om te verwijderen.");
                        return;
                    }
                    if (!confirm("Weet je zeker dat je dit product wilt verwijderen?")) return;
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
                            const cat = document.getElementById('categorie').value;
                            const subcat = document.getElementById('subcategorie').value;
                            saveCategorySelection();
                            setTimeout(() => {
                                window.location.href = "producten_beheer.php?selectedCategory=" + encodeURIComponent(cat) + "&selectedSubcategory=" + encodeURIComponent(subcat);
                            }, 1500);
                        } else {
                            showSnackbar("Fout bij verwijderen: " + result.error);
                        }
                    } catch (error) {
                        showSnackbar("Er is een fout opgetreden bij het verwijderen.");
                    }
                });

                // Aanpassing: de save-knop refresht de pagina (geen extra save)
                document.getElementById('save-button').addEventListener('click', () => {
                    const cat = document.getElementById('categorie').value;
                    const subcat = document.getElementById('subcategorie').value;
                    window.location.href = "producten_beheer.php?selectedCategory=" + encodeURIComponent(cat) + "&selectedSubcategory=" + encodeURIComponent(subcat);
                });

                document.getElementById('new-button').addEventListener('click', resetForm);

                // Optioneel: bewaar en herstel keuze via sessionStorage
                function saveCategorySelection() {
                    sessionStorage.setItem('selectedCategory', document.getElementById('categorie').value);
                    sessionStorage.setItem('selectedSubcategory', document.getElementById('subcategorie').value);
                }

                function restoreCategorySelection() {
                    const category = sessionStorage.getItem('selectedCategory');
                    const subcategory = sessionStorage.getItem('selectedSubcategory');
                    if (category !== null) {
                        document.getElementById('categorie').value = category;
                    }
                    if (subcategory !== null) {
                        document.getElementById('subcategorie').value = subcategory;
                    }
                }

                restoreCategorySelection();
            });
        </script>
    </main>
    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>
