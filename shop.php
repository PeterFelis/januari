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
            /* Als je aan de linker kant extra ruimte nodig hebt, kan je dit toevoegen,
           maar houd wel in de gaten dat dit de totale breedte niet te veel vergroot. */
            /* padding-left: 40px; */
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
    <?php include_once __DIR__ . '/incs/menu.php';
    ?>
    <main>
        <!-- We gebruiken nu enkel een right-pane. Hierin staat zowel het selectie-menu als de productgrid -->
        <div class="right-pane">
            <!-- Selectie-component container: Hiermee filter je de producten -->
            <div id="selectionComponent"></div>
            <!-- Productgrid: de titel "Producten" is verwijderd -->
            <div class="product-grid" id="productGrid">
                <!-- Hier komen de producten -->
            </div>
        </div>
    </main>


    <script src="incs/selection_component.js">
    </script>
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
    <script src="incs/selection_component.js"></script>
    <script>
        // Bepaal of de klant is ingelogd
        var isLoggedIn = <?php echo isset($_SESSION['klant_id']) ? 'true' : 'false'; ?>;
        let products = [];
        let currentCategory = "";
        let currentSubcategory = "";

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
            // Begin met alle leverbare producten
            let filtered = products.filter(p => p.leverbaar === 'ja');

            // Vergelijk met gestructureerde (trimmed en lowercase) waarden
            const filterCat = currentCategory.trim().toLowerCase();
            const filterSub = currentSubcategory.trim().toLowerCase();

            if (filterCat !== "") {
                filtered = filtered.filter(p =>
                    p.categorie && p.categorie.trim().toLowerCase() === filterCat
                );
            }
            if (filterSub !== "") {
                filtered = filtered.filter(p =>
                    p.subcategorie && p.subcategorie.trim().toLowerCase() === filterSub
                );
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
                // Werk de globale variabelen alleen bij als er expliciet een waarde is meegegeven
                if (selectionData.hasOwnProperty('category')) {
                    currentCategory = selectionData.category ? selectionData.category.trim() : "";
                }
                if (selectionData.hasOwnProperty('subcategory')) {
                    currentSubcategory = selectionData.subcategory ? selectionData.subcategory.trim() : "";
                }
                console.log("Filterinstellingen:", {
                    category: currentCategory,
                    subcategory: currentSubcategory,
                    callbackData: selectionData
                });
                filterAndDisplayProducts();
            }
        });

        window.onload = function() {
            fetchProducts();
        };
    </script>
    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>