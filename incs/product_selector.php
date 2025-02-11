<?php
// product_selector.php
// Zorg dat sessie en eventuele toegangscontrole al zijn afgehandeld in de hoofdapplicatie
include_once __DIR__ . '/dbConnect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . $e->getMessage());
}

// Haal de productgegevens op
$query = "SELECT id, categorie, subcategorie, TypeNummer, sticker_text, aantal_per_doos 
          FROM products";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$jsonData = json_encode($products);
?>
<style>
    /* Algemene styling */
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    /* Header als grid: links logo & titel, rechts productselector */
    header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        padding: 15px 20px;
        background-color: var(--heellichtpaars);
        gap: 20px;
        height: 12vh;
        max-height: 12vh;
        position: relative;
    }

    .header-left,
    header-right {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-right {
        padding-right: 10rem;
    }



    /* Styling voor de productselector in de header */
    #productSelectorContainer {
        height: 10vh;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        max-height: 15vh;
        overflow-y: auto;
        border: 1px solid white;
        border-radius: 8px;
        position: relative;
    }

    .button-row {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 5px;
    }

    button {
        padding: 5px 10px;
        border: none;
        border-radius: 10px;
        background-color: #007BFF;
        color: #fff;
        font-size: 12px;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.2s;
    }

    button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    button:active {
        background-color: #003f7f;
        transform: translateY(0);
    }

    .selected {
        background-color: #ffcc66;
        color: #000;
        font-weight: bold;
    }

    /* Extra content onder de header */
    .main-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Responsive: onder een bepaalde breedte onder elkaar stapelen */
    @media (max-width: 768px) {
        header {
            grid-template-columns: 1fr;
            text-align: center;
        }

        #productSelectorContainer {
            margin-top: 15px;
        }
    }
</style>

<header>
    <!-- Links: Logo en titel -->
    <div class="header-left">
        <?php
        $kleur = 'paars';
        $logo = 'logoklein';
        include_once '../../incs/logo.php';
        ?>
    </div>
    <!-- Rechts: De productselector -->
    <div class="header-right">
        <div id="productSelectorContainer">
            <div id="selectorCategory" class="button-row"></div>
            <div id="selectorSubcategory" class="button-row"></div>
            <div id="selectorProduct" class="button-row"></div>
        </div>
    </div>
</header>
<script>
    // Alle producten uit PHP beschikbaar als JavaScript-object
    const allProducts = <?php echo $jsonData; ?>;
    let selectedCategory = null;
    let selectedSubcategory = null;
    let selectedProduct = null;

    // Lees eventuele GET-parameters voor behoud van selectie
    let urlParams = new URLSearchParams(window.location.search);
    let initialCategory = urlParams.get('category');
    let initialSubcategory = urlParams.get('subcategory');
    let initialProductParam = urlParams.get('product');

    // Helper-functie om de huidige path te normaliseren (zonder trailing slash)
    function getNormalizedPath() {
        return window.location.pathname.replace(/\/$/, '');
    }

    // Highlight de geselecteerde knop in een rij
    function highlightSelectedBtn(parentElement, clickedButton) {
        const allButtons = parentElement.querySelectorAll('button');
        allButtons.forEach(btn => btn.classList.remove('selected'));
        clickedButton.classList.add('selected');
    }

    // Toon de unieke categorieën
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
                showSubcategories(cat);
            };
            categoryRow.appendChild(btn);
        });
        // Als er een initialCategory is, klik dan automatisch op de juiste knop
        if (initialCategory) {
            const btn = Array.from(categoryRow.children).find(button => button.textContent === initialCategory);
            if (btn) {
                setTimeout(() => {
                    btn.click();
                }, 0);
            }
        }
        // Maak subcategorie- en productrij leeg
        document.getElementById('selectorSubcategory').innerHTML = '';
        document.getElementById('selectorProduct').innerHTML = '';
    }

    // Toon de subcategorieën voor de gekozen categorie
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
                showProducts(category, subcat);
            };
            subcatRow.appendChild(btn);
        });
        // Als er een initialSubcategory is, klik dan automatisch op de juiste knop
        if (initialSubcategory) {
            const btn = Array.from(subcatRow.children).find(button => button.textContent === initialSubcategory);
            if (btn) {
                setTimeout(() => {
                    btn.click();
                }, 0);
            }
        }
        // Maak de productrij leeg
        document.getElementById('selectorProduct').innerHTML = '';
    }

    // Toon de producten voor de gekozen categorie en subcategorie
    function showProducts(category, subcategory) {
        const filtered = allProducts.filter(p =>
            p.categorie === category && p.subcategorie === subcategory
        );
        const productRow = document.getElementById('selectorProduct');
        productRow.innerHTML = '';
        filtered.forEach(product => {
            const btn = document.createElement('button');
            btn.textContent = product.TypeNummer;
            btn.onclick = () => {
                selectedProduct = product;
                highlightSelectedBtn(productRow, btn);
                // Bouw de SEO-vriendelijke URL met behoud van de GET parameters
                const newUrl = `/artikelen/${product.TypeNummer}?category=${encodeURIComponent(category)}&subcategory=${encodeURIComponent(subcategory)}&product=${encodeURIComponent(product.TypeNummer)}`;
                // Alleen navigeren als we nog niet op de juiste pagina zitten (met normalisatie)
                if (getNormalizedPath() !== `/artikelen/${product.TypeNummer}`) {
                    window.location.href = newUrl;
                }
            };
            productRow.appendChild(btn);
        });
        // Automatisch de juiste productknop selecteren
        if (initialProductParam) {
            const btn = Array.from(productRow.children).find(button => button.textContent === initialProductParam);
            if (btn) {
                // Als we op de productpagina zitten, dus als window.isProductPage true is,
                // of als de huidige URL al overeenkomt, dan alleen highlighten, geen redirect.
                if (window.isProductPage || getNormalizedPath() === `/artikelen/${initialProductParam}`) {
                    highlightSelectedBtn(productRow, btn);
                    initialProductParam = null; // voorkom herhaalde actie
                } else {
                    setTimeout(() => {
                        btn.click();
                        initialProductParam = null;
                    }, 0);
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', showCategories);
</script>