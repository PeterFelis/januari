<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selector</title>
    <!-- Google Fonts voor de Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Algemene styling */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #007BFF;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header img {
            height: 50px;
            margin-right: 15px;
        }

        header h1 {
            margin: 0;
            color: #fff;
            font-size: 24px;
        }

        .main-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Styling voor de productselector */
        #productSelectorContainer {
            background-color: #fff;
            max-height: 15vh;
            height: 15vh;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
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

        /* Styling voor het productdetails-paneel */
        .product-details {
            background: #fff;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 20px;
            display: none;
        }

        .product-details h2 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <!-- Header met logo en titel -->
    <header>
        <img src="path_to_your_logo.png" alt="Logo">
        <h1>Mijn Product Selector</h1>
    </header>

    <!-- Hoofdcontainer -->
    <div class="main-container">
        <?php
        // product_selector.php
        // Zorg dat de sessie en eventueel de toegangscontrole al zijn afgehandeld in de hoofdapplicatie
        include_once __DIR__ . '/incs/dbConnect.php';
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
        <!-- Product Selector Container -->
        <div id="productSelectorContainer">
            <div id="selectorCategory" class="button-row"></div>
            <div id="selectorSubcategory" class="button-row"></div>
            <div id="selectorProduct" class="button-row"></div>
        </div>

        <!-- Container voor de gegenereerde productkeuze -->
        <div id="productDetails" class="product-details"></div>

        <script>
            // Alle producten uit PHP beschikbaar als JavaScript-object
            const allProducts = <?php echo $jsonData; ?>;
            let selectedCategory = null;
            let selectedSubcategory = null;
            let selectedProduct = null;

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
                // Reset subcategorie, product en details
                document.getElementById('selectorSubcategory').innerHTML = '';
                document.getElementById('selectorProduct').innerHTML = '';
                document.getElementById('productDetails').style.display = 'none';
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
                // Reset product en details
                document.getElementById('selectorProduct').innerHTML = '';
                document.getElementById('productDetails').style.display = 'none';
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
                        showProductDetails(product);
                    };
                    productRow.appendChild(btn);
                });
            }

            // Toon de details van het geselecteerde product in een apart paneel
            function showProductDetails(product) {
                const detailsContainer = document.getElementById('productDetails');
                detailsContainer.style.display = 'block';
                detailsContainer.innerHTML = `
          <h2>Product Details</h2>
          <p><strong>TypeNummer:</strong> ${product.TypeNummer}</p>
          <p><strong>Sticker tekst:</strong> ${product.sticker_text}</p>
          <p><strong>Aantal per doos:</strong> ${product.aantal_per_doos}</p>
          <button id="goToProductBtn">Bekijk product</button>
        `;
                document.getElementById('goToProductBtn').onclick = () => {
                    window.location.href = `/artikelen/${product.TypeNummer}/index.php`;
                };
            }

            document.addEventListener('DOMContentLoaded', showCategories);
        </script>
    </div>
</body>

</html>