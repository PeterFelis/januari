<?php
// shop.php
//include_once __DIR__ . '/incs/sessie.php';

// Lees eventuele selectie uit de URL
$selCat = isset($_GET['category']) ? $_GET['category'] : '';
$selSub = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';

$title = "Fetum - webshop";
$statusbalk = "Iets bestellen? Mailen of bellen werkt nog sneller";
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
        transform: scale(1.02);
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
        // Huidige selectie uit PHP
        const currentCategory = <?= json_encode($selCat) ?>;
        const currentSubcategory = <?= json_encode($selSub) ?>;

        // Globale lijst
        let products = [];

        async function fetchProducts() {
            try {
                const resp = await fetch('api_products.php');
                products = await resp.json();
                filterAndDisplayProducts();
            } catch (e) {
                console.error("Fout bij ophalen:", e);
            }
        }

        function filterAndDisplayProducts() {
            const grid = document.getElementById('productGrid');
            let filtered = products.filter(p => p.leverbaar === 'ja');

            if (currentCategory) {
                filtered = filtered.filter(p =>
                    p.categorie && p.categorie.trim().toLowerCase() === currentCategory.trim().toLowerCase()
                );
            }
            if (currentSubcategory) {
                filtered = filtered.filter(p =>
                    p.subcategorie && p.subcategorie.trim().toLowerCase() === currentSubcategory.trim().toLowerCase()
                );
            }

            grid.innerHTML = "";
            if (!filtered.length) {
                grid.innerHTML = "<p>Geen producten gevonden.</p>";
                return;
            }

            filtered.forEach(p => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.innerHTML = `
                <h3>${p.TypeNummer}</h3>
                <p>vanaf prijs: ${getLowestPrice(p.prijsstaffel)}</p>
                <div class="card-content">
                    <div class="card-photo">
                        <img src="artikelen/${encodeURIComponent(p.TypeNummer)}/Pfoto.png" alt="${p.TypeNummer}">
                    </div>
                    <div class="card-usp">
                        ${p.USP}
                    </div>
                </div>
            `;
                const target = p.hoofd_product && p.hoofd_product.trim() ? p.hoofd_product : p.TypeNummer;
                card.addEventListener('click', () => {
                    window.location.href = `artikelen/${encodeURIComponent(target)}/index.php`;
                });
                grid.appendChild(card);
            });

            // Wacht tot alle plaatjes écht geladen zijn, dan pas meten
            const imgs = grid.querySelectorAll('img');
            const loads = Array.from(imgs).map(img => new Promise(res => {
                if (img.complete) return res();
                img.addEventListener('load', res);
            }));
            Promise.all(loads).then(() => {
                equalizeHeights();
            });
        }

        function getLowestPrice(prijsstaffel) {
            return prijsstaffel.split('\n').reduce((min, lijn) => {
                    const p = parseFloat(lijn.trim().split(' ')[1].replace(',', '.'));
                    return (!isNaN(p) && p < min) ? p : min;
                }, Infinity) !== Infinity ?
                prijsstaffel.split('\n').reduce((min, lijn) => {
                    const p = parseFloat(lijn.trim().split(' ')[1].replace(',', '.'));
                    return (!isNaN(p) && p < min) ? p : min;
                }, Infinity).toFixed(2) :
                "n.v.t.";
        }

        function equalizeHeights() {
            const cards = Array.from(document.querySelectorAll('.product-card'));
            if (!cards.length) return;

            // Reset hoogtes
            cards.forEach(c => c.style.height = 'auto');

            // Vind de MAXIMALE hoogte
            const maxH = Math.max(...cards.map(c => c.offsetHeight));

            // Zet álle kaarten daarop
            cards.forEach(c => c.style.height = maxH + 'px');
        }

        // Selection component init
        const selection = new SelectionComponent({
            container: document.getElementById('selectionComponent'),
            showProducts: false,
            onSelectionChange(data) {
                const params = [];
                if (data.category) params.push('category=' + encodeURIComponent(data.category));
                if (data.subcategory) params.push('subcategory=' + encodeURIComponent(data.subcategory));
                window.location.href = '/shop.php' + (params.length ? '?' + params.join('&') : '');
            }
        });
        selection.selectedCategory = currentCategory;
        selection.selectedSubcategory = currentSubcategory;
        selection.renderMenu();
        if (currentCategory) selection.menu.classList.add('open');

        // Her-gelijk trekken bij venster resize
        window.addEventListener('resize', () => {
            // eventueel debouncen als je wil; dit is simpel
            equalizeHeights();
        });

        // Kick off
        window.onload = fetchProducts;
    </script>

    <?php include_once __DIR__ . '/incs/bottom.php'; ?>
</body>

</html>