<?php
// product_selector.php
// Zorg dat de sessie en eventueel de toegangscontrole al zijn afgehandeld in de hoofdapplicatie
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
<!-- Product Selector HTML & JavaScript -->
<style>
    /* Container met maximale hoogte van 20vh */
    #productSelectorContainer {
        max-height: 20vh;
        overflow-y: auto;
        font-family: 'Poppins', sans-serif;
        padding: 10px;
        border: 1px solid #ccc;
        margin-bottom: 1rem;
    }
    .button-row {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 5px;
    }
    button {
        padding: 5px 10px;
        margin: 2px;
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
</style>

<div id="productSelectorContainer">
    <div id="selectorCategory" class="button-row"></div>
    <div id="selectorSubcategory" class="button-row"></div>
    <div id="selectorProduct" class="button-row"></div>
</div>

<script>
    // Alle producten uit PHP beschikbaar als JavaScript-object
    const allProducts = <?php echo $jsonData; ?>;
    let selectedCategory = null;
    let selectedSubcategory = null;
    let selectedProduct = null;

    // Functie voor het highlighten van de geselecteerde knop in een rij
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
        // Maak de productrij leeg
        document.getElementById('selectorProduct').innerHTML = '';
    }

    // Toon de producten (TypeNummers) voor de gekozen categorie en subcategorie
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
                // Na selectie redirecten we naar de productpagina.
                // Pas de URL hieronder aan indien je directory-structuur anders is.
                // Voorbeeld: als je productdirectory de naam van 'TypeNummer' heeft:
                window.location.href = `../${product.TypeNummer}/index.php`;
                // Als je in plaats daarvan de 'id' wilt gebruiken, vervang dan met:
                // window.location.href = `../${product.id}/index.php`;
            };
            productRow.appendChild(btn);
        });
    }

    document.addEventListener('DOMContentLoaded', showCategories);
</script>
