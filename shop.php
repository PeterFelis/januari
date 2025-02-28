<?php
// shop.php
$title = "Fetum - webshop";
$statusbalk = "Iets bestellen? Gewoon even mailen of bellen!";
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>
<style>
    /* Standaard stijlen voor de selectie-component */
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

    /* Product grid en kaartstijlen */
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

    .product-card:hover {
        transform: scale(1.05);
    }

    .card-usp p {
        margin: 0;
    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <div class="container">
        <!-- Linker kolom: hier komt de selectie-component -->
        <div class="left-pane">
            <div id="selectionComponent"></div>
        </div>
        <!-- Rechter kolom: productgrid -->
        <div class="right-pane">
            <h2>Producten</h2>
            <div class="product-grid" id="productGrid">
                <!-- Productkaarten worden hier geladen -->
                <?php include __DIR__ . '/incs/random_products.php'; ?>
            </div>
        </div>
    </div>
    <script src="incs/selection_component.js"></script>
    <script>
        // Variabelen voor filtering
        let products = [];
        let currentCategory = "";
        let currentSubcategory = "";
        // Variabele om de originele (random) producten op te slaan
        let defaultProductHTML = "";

        // Haal alle producten op en toon de grid
        async function fetchProducts() {
            try {
                const response = await fetch('api_products.php');
                products = await response.json();
                filterAndDisplayProducts();
            } catch (error) {
                console.error("Fout bij ophalen van producten:", error);
            }
        }

        function filterAndDisplayProducts() {
            const grid = document.getElementById('productGrid');
            // Filter eerst alle producten op leverbaarheid
            let filtered = products.filter(p => p.leverbaar === 'ja');

            // Pas verdere filtering toe op categorie en subcategorie
            if (currentCategory !== "") {
                filtered = filtered.filter(p => p.categorie === currentCategory);
            }
            if (currentSubcategory !== "") {
                filtered = filtered.filter(p => p.subcategorie === currentSubcategory);
            }

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
                // Bepaal de target: als er een hoofdproduct is, dan gebruiken we dat als navigatiedoel
                let targetType = product.TypeNummer;
                if (product.hoofd_product && product.hoofd_product.trim() !== "") {
                    targetType = product.hoofd_product;
                }
                card.addEventListener('click', () => {
                    window.location.href = 'artikelen/' + encodeURIComponent(targetType) + '/index.php';
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
            showProducts: false, // Alleen categorieën en subcategorieën voor de webshop
            onSelectionChange: function(selectionData) {
                currentCategory = selectionData.category || "";
                currentSubcategory = selectionData.subcategory || "";
                filterAndDisplayProducts();
            }
        });

        window.onload = function() {
            // Sla de originele random producten op (indien nodig)
            defaultProductHTML = document.getElementById('productGrid').innerHTML;
            fetchProducts();
        };
    </script>
    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>