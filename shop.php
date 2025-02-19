<?php
// shop.php
$title = "Fetum - webshop";
$statusbalk = "Iets bestellen? Gewoon even mailen of bellen!";
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>

<style>
    /* Layout van de pagina */
    .container {
        display: flex;
        margin-top: 10vh;
    }

    .left-pane {
        width: 20%;
        border-right: 1px solid #ccc;
        padding: 20px;
    }

    .right-pane {
        width: 80%;
        padding: 20px;
    }

    /* Stijlen voor de lijsten */
    .category-list,
    .subcategory-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-list li,
    .subcategory-list li {
        cursor: pointer;
        padding: 5px;
        margin: 5px 0;
    }

    .selected {
        font-weight: bold;
        color: #007BFF;
    }

    /* Product grid en kaarten */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .product-card {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s;
        /* Stel een vaste hoogte in zodat de verhoudingen behouden blijven */
        height: 400px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    /* Layout binnen de kaart: verticale indeling, 75% voor foto, 25% voor USP-tekst */
    .card-content {
        display: flex;
        flex-direction: column;
        height: 75%;
        margin-bottom: 10px;
    }

    .card-photo {
        flex: 0 0 75%;
        margin-bottom: 5px;
    }

    .card-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .card-usp {
        flex: 0 0 25%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <div class="container">
        <!-- Linker kolom: categorieën en subcategorieën -->
        <div class="left-pane">
            <h2>Categorieën</h2>
            <ul class="category-list" id="categoryList"></ul>
            <div id="subCategoryContainer" style="display:none;">
                <h3>Subcategorieën</h3>
                <ul class="subcategory-list" id="subcategoryList"></ul>
            </div>
        </div>
        <!-- Rechter kolom: grid met productkaarten -->
        <div class="right-pane">
            <h2>Producten</h2>
            <div class="product-grid" id="productGrid">
                <!-- Hier komen de productkaarten -->
            </div>
        </div>
    </div>

    <script>
        // Globale variabelen
        let products = [];
        let currentCategory = "";
        let currentSubcategory = "";

        // Haal alle producten op via het API-endpoint
        async function fetchProducts() {
            try {
                const response = await fetch('api_products.php');
                products = await response.json();
                populateCategories();
            } catch (error) {
                console.error("Fout bij ophalen van producten:", error);
            }
        }

        // Vul de lijst met unieke categorieën
        function populateCategories() {
            const categorySet = new Set(products.map(p => p.categorie));
            const categoryList = document.getElementById('categoryList');
            categoryList.innerHTML = "";
            categorySet.forEach(category => {
                const li = document.createElement('li');
                li.textContent = category;
                li.addEventListener('click', () => {
                    currentCategory = category;
                    currentSubcategory = "";
                    highlightSelection(categoryList, li);
                    populateSubcategories(category);
                    filterAndDisplayProducts();
                });
                categoryList.appendChild(li);
            });
        }

        // Vul de lijst met subcategorieën voor een gekozen categorie
        function populateSubcategories(category) {
            const subcategories = new Set(
                products.filter(p => p.categorie === category).map(p => p.subcategorie)
            );
            const subcategoryList = document.getElementById('subcategoryList');
            subcategoryList.innerHTML = "";
            subcategories.forEach(subcat => {
                const li = document.createElement('li');
                li.textContent = subcat;
                li.addEventListener('click', () => {
                    currentSubcategory = subcat;
                    highlightSelection(subcategoryList, li);
                    filterAndDisplayProducts();
                });
                subcategoryList.appendChild(li);
            });
            // Toon of verberg de subcategorieën
            document.getElementById('subCategoryContainer').style.display = subcategories.size > 0 ? 'block' : 'none';
        }

        // Highlight de geselecteerde lijstitem
        function highlightSelection(listElement, selectedItem) {
            listElement.querySelectorAll('li').forEach(item => item.classList.remove('selected'));
            selectedItem.classList.add('selected');
        }

        // Filter de producten op basis van de gekozen categorie en subcategorie en toon ze in de grid
        function filterAndDisplayProducts() {
            let filtered = products.filter(p => p.categorie === currentCategory);
            if (currentSubcategory) {
                filtered = filtered.filter(p => p.subcategorie === currentSubcategory);
            }
            const grid = document.getElementById('productGrid');
            grid.innerHTML = "";
            if (filtered.length === 0) {
                grid.innerHTML = "<p>Geen producten gevonden.</p>";
                return;
            }
            filtered.forEach(product => {
                const card = document.createElement('div');
                card.className = 'product-card';
                // Productkaart: tonen TypeNummer, een foto (boven, 75%) en USP tekst (onder, 25%)
                card.innerHTML = `
                    <h3>${product.TypeNummer}</h3>
                    <div class="card-content">
                        <div class="card-photo">
                        ${product.foto_link}
                            <img src="${product.foto_link ? product.foto_link : 'default_foto.jpg'}" alt="${product.TypeNummer}">
                        </div>
                        <div class="card-usp">
                            <p>${stripHTML(product.USP)}</p>
                        </div>
                    </div>
                    <p><strong>Prijs:</strong> ${getLowestPrice(product.prijsstaffel)}</p>
                `;
                card.addEventListener('click', () => {
                    // Ga naar de productpagina (artikelen/{TypeNummer}/index.php)
                    window.location.href = 'artikelen/' + encodeURIComponent(product.TypeNummer) + '/index.php';
                });
                grid.appendChild(card);
            });
        }

        // Verwijder HTML-tags uit een string
        function stripHTML(html) {
            var div = document.createElement("div");
            div.innerHTML = html;
            return div.textContent || div.innerText || "";
        }

        // Haal de laagste prijs op uit de prijsstaffel-string
        // (Verondersteld dat de staffel wordt weergegeven als: "32 7,86\n64 7,64\n..." etc.)
        function getLowestPrice(prijsstaffel) {
            const lines = prijsstaffel.split('\n');
            let lowest = Number.POSITIVE_INFINITY;
            lines.forEach(line => {
                const parts = line.trim().split(' ');
                if (parts.length >= 2) {
                    let price = parseFloat(parts[1].replace(',', '.'));
                    if (!isNaN(price) && price < lowest) {
                        lowest = price;
                    }
                }
            });
            return (lowest !== Number.POSITIVE_INFINITY) ? lowest.toFixed(2) : "n.v.t.";
        }

        // Initialiseer de pagina door de producten op te halen
        window.onload = fetchProducts;
    </script>

    <?php
    include_once __DIR__ . '/incs/bottom.php';
    ?>