<?php
// shop.php
include_once __DIR__ . '/incs/sessie.php';

// Lees eventuele selectie uit de URL
$selCat = isset($_GET['category']) ? $_GET['category'] : '';
$selSub = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';

$title = "Fetum - webshop";
$statusbalk = "Iets bestellen? Gewoon even mailen of bellen!";
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>

<style>
    /* Algemene reset */
    body {
        margin: 0;
        padding: 0;
    }

    /* Standaard stijlen voor selectie-component */
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
        font-weight: bold;
        background-color: #007BFF;
        color: white;
    }

    /* Productfoto’s beperken */
    .card-photo img {
        max-width: calc(100% - 10px);
        height: auto;
        display: block;
        margin: 0 auto;
    }

    /* Product grid & kaartstijlen */
    .product-grid {
        display: grid;
        gap: 20px;
    }

    .product-card {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-content {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
        min-height: 70%;
    }

    .card-photo {
        width: 100%;
        height: auto;
        margin-bottom: 5px;
    }

    .card-usp {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        flex-direction: column;
    }

    .card-usp p {
        margin: 0;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    /* ------ Layout & Breakpoints ------ */

    .shop-page main {
        width: 90%;
        max-width: 1200px;
        margin: 5rem auto 0;
    }

    /* Mobiel: 2 kolommen en enkele aanpassingen */
    @media (max-width: 768px) {
        .shop-page main {
            width: 100%;
            padding: 0 1rem;
        }

        .shop-page .product-grid {
            grid-template-columns: repeat(2, 1fr);
            width: 100%;
            max-width: 100%;
        }
    }

    /* iPad, desktop, etc.: 3 kolommen */
    @media (min-width: 769px) {
        .shop-page .product-grid {
            grid-template-columns: repeat(3, 1fr);
            width: 100%;
            max-width: 100%;
        }
    }
</style>

<body class="shop-page">
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <div class="right-pane">
            <!-- Selectie-component container: Hiermee filter je de producten -->
            <div id="selectionComponent"></div>
            <!-- Productgrid: hier komen de producten -->
            <div class="product-grid" id="productGrid"></div>
        </div>
    </main>

    <script src="incs/selection_component.js"></script>
    <script>
        // Maak huidige selectie beschikbaar in JS
        const currentCategory = <?= json_encode($selCat) ?>;
        const currentSubcategory = <?= json_encode($selSub) ?>;

        // Globale productlijst
        let products = [];

        async function fetchProducts() {
            try {
                const response = await fetch('api_products.php');
                products = await response.json();
                // Na ophalen: filter direct met de URL-initialisatie
                filterAndDisplayProducts();
            } catch (error) {
                console.error("Fout bij ophalen van producten:", error);
            }
        }

        function filterAndDisplayProducts() {
            const grid = document.getElementById('productGrid');
            let filtered = products.filter(p => p.leverbaar === 'ja');

            if (currentCategory) {
                filtered = filtered.filter(p => p.categorie && p.categorie.trim().toLowerCase() === currentCategory.trim().toLowerCase());
            }
            if (currentSubcategory) {
                filtered = filtered.filter(p => p.subcategorie && p.subcategorie.trim().toLowerCase() === currentSubcategory.trim().toLowerCase());
            }

            console.log("🔎 Filterresultaat:", filtered.length, "producten");
            grid.innerHTML = "";

            if (filtered.length === 0) {
                grid.innerHTML = "<p>Geen producten gevonden.</p>";
                return;
            }

            filtered.forEach(product => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.innerHTML = `
                    <h3>${product.TypeNummer}</h3>
                    <p>vanaf prijs: ${getLowestPrice(product.prijsstaffel)}</p>
                    <div class="card-content">
                        <div class="card-photo">
                            <img src="artikelen/${encodeURIComponent(product.TypeNummer)}/Pfoto.png" alt="${product.TypeNummer}">
                        </div>
                        <div class="card-usp">
                            ${product.USP}
                        </div>
                    </div>
                `;
                let targetType = product.hoofd_product && product.hoofd_product.trim() !== "" ? product.hoofd_product : product.TypeNummer;
                card.addEventListener('click', () => {
                    // Afhankelijk van login direct naar detailpagina
                    window.location.href = `artikelen/${encodeURIComponent(targetType)}/index.php`;
                });
                grid.appendChild(card);
            });
        }

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

        // Initialiseer de selectie-component
        var selection = new SelectionComponent({
            container: document.getElementById('selectionComponent'),
            showProducts: false,
            onSelectionChange: function(selectionData) {
                // redirect via URL
                let url = '/shop.php';
                let params = [];
                if (selectionData.category) params.push('category=' + encodeURIComponent(selectionData.category));
                if (selectionData.subcategory) params.push('subcategory=' + encodeURIComponent(selectionData.subcategory));
                window.location.href = url + (params.length ? '?' + params.join('&') : '');
            },
        });

        // Na initialisatie menu open trekken én highlight toepassen
        selection.selectedCategory = currentCategory;
        selection.selectedSubcategory = currentSubcategory;
        selection.renderMenu();

        if (currentCategory) {
            selection.menu.classList.add('open');
        }
        // Laad producten bij eerste paginabezoek
        window.onload = function() {
            fetchProducts();
        };
    </script>

    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>

</html>