<?php
// shop.php
include_once __DIR__ . '/incs/sessie.php';
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

    /* ------ Layout & Breakpoints voor debuggen zonder left-pane ------ */

    /* 1. Grote schermen: vanaf 1155px – volledige breedte met sidebar verwijderd */
    @media (min-width: 1155px) {
        main {
            margin: 10vh auto;
            max-width: 1140px;
            width: 90%;
            background-color: #00FF00;
            /* Lime (debug) */
        }

        .right-pane {
            width: 100%;
            padding: 20px;
        }

        .product-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
    }

    /* 2. Schermen tussen 984px en 1154px: achtergrond groen, 2 kolommen */
    @media (max-width: 1154px) and (min-width: 984px) {
        main {
            margin: 10vh auto;
            max-width: 1154px;
            background-color: green;
            /* Debug groen */
        }

        .right-pane {
            width: 100%;
            padding: 20px;
        }

        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* 3. Schermen tussen 869px en 983px: achtergrond groen, 1 kolom */
    @media (max-width: 983px) and (min-width: 869px) {
        main {
            margin: 10vh auto;
            max-width: 983px;
            background-color: green;
            /* Debug groen */
        }

        .right-pane {
            width: 100%;
            padding: 20px;
        }

        .product-grid {
            grid-template-columns: 1fr;
        }
    }

    /* 4. Schermen tussen 757px en 868px: achtergrond groen, 2 kolommen */
    @media (max-width: 868px) and (min-width: 757px) {
        main {
            margin: 5vh auto;
            max-width: 868px;
            background-color: green;
            /* Debug groen */
        }

        .right-pane {
            width: 100%;
            padding: 10px;
        }

        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* 5. Mobiel: onder 757px – achtergrond groen, 2 kolommen */
    @media (max-width: 756px) {
        main {
            flex-direction: column;
            margin: 5vh auto;
            width: 100%;
            background-color: green;
            /* Debug groen */
        }

        .right-pane {
            width: 100%;
            padding: 10px;
        }

        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>
    <main>
        <!-- We gebruiken nu enkel een right-pane. Hierin staat zowel het selectie-menu als de productgrid -->
        <div class="right-pane">
            <!-- Selectie-component container: Hiermee filter je de producten -->
            <div id="selectionComponent"></div>
            <!-- Productgrid: de titel "Producten" is verwijderd -->
            <div class="product-grid" id="productGrid">
                <?php include __DIR__ . '/incs/random_products.php'; ?>
            </div>
        </div>
    </main>
    <script src="incs/selection_component.js"></script>
    <script>
        // Bepaal of de klant is ingelogd
        var isLoggedIn = <?php echo isset($_SESSION['klant_id']) ? 'true' : 'false'; ?>;
        let products = [];
        let currentCategory = "";
        let currentSubcategory = "";
        let defaultProductHTML = "";

        // Haal de producten op en toon ze
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
            // Filter eerst op leverbaarheid
            let filtered = products.filter(p => p.leverbaar === 'ja');

            // Filter op categorie en subcategorie
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
                let targetType = product.TypeNummer;
                if (product.hoofd_product && product.hoofd_product.trim() !== "") {
                    targetType = product.hoofd_product;
                }
                card.addEventListener('click', () => {
                    if (!isLoggedIn) {
                        window.location.href = '/loginForm.php';
                    } else {
                        window.location.href = 'artikelen/' + encodeURIComponent(targetType) + '/index.php';
                    }
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

        // Initialiseer de selectie-component zodat het filtermenu wordt weergegeven
        var selection = new SelectionComponent({
            container: document.getElementById('selectionComponent'),
            showProducts: false,
            onSelectionChange: function(selectionData) {
                currentCategory = selectionData.category || "";
                currentSubcategory = selectionData.subcategory || "";
                filterAndDisplayProducts();
            }
        });

        window.onload = function() {
            defaultProductHTML = document.getElementById('productGrid').innerHTML;
            fetchProducts();
        };
    </script>
    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>