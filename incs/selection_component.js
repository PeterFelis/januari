/* selection_component.js */
(function(window, document) {
    function SelectionComponent(config) {
        this.container = typeof config.container === 'string'
            ? document.querySelector(config.container)
            : config.container;
        if (!this.container) {
            throw new Error("Container element niet gevonden.");
        }
        this.endpoint = config.endpoint || '/api_products.php';
        this.showProducts = config.showProducts || false;
        this.onSelectionChange = config.onSelectionChange || function() {};
        this.checkProductPage = config.checkProductPage || false;
        // Bepaal de oriëntatie: "horizontal" of "vertical" (standaard "vertical")
        this.orientation = config.orientation || "vertical";

        // In deze variabelen slaan we straks de HTML-elementen op
        // waar de knoppen (categorie, subcategorie, product) komen.
        this.categoryListDiv = null;
        this.subcategoryListDiv = null;
        this.productListDiv = null;

        this.products = [];
        this.selectedCategory = null;
        this.selectedSubcategory = null;
        this.selectedProduct = null;

        this.injectCSS();
        this.init();
    }

    /**
     * injectCSS()
     * Voeg dynamisch CSS toe, afhankelijk van de gekozen oriëntatie.
     */
    SelectionComponent.prototype.injectCSS = function() {
        var css = "";
        if (this.orientation === "horizontal") {
            // === HORIZONTALE LAYOUT (kopjes op 1e rij, knoppen op 2e rij) ===
            css += `
            .selection-component-container {
                display: grid;
                /* 3 kolommen, 2 rijen */
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: auto auto;
                gap: 1rem;
                width: 100%;
                height:15rem;
                margin-bottom: 20px;
                padding: 30px;
                border-bottom:2px solid var(--paars);
            }

            /* Bovenste rij: de headings */
            .selection-category-heading,
            .selection-subcategory-heading,
            .selection-products-heading {
                text-align: center;
                font-weight: bold;
                font-size: 3rem;
            }
            .selection-category-heading {
                grid-column: 1;
                grid-row: 1;
            }
            .selection-subcategory-heading {
                grid-column: 2;
                grid-row: 1;
            }
            .selection-products-heading {
                grid-column: 3;
                grid-row: 1;
            }

            /* Tweede rij: de lijsten met knoppen */
            .selection-category-list {
                grid-column: 1;
                grid-row: 2;
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
            }
            .selection-subcategory-list {
                grid-column: 2;
                grid-row: 2;
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
            }
            .selection-products-list {
                grid-column: 3;
                grid-row: 2;
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
            }

            .selection-btn {
            padding: 2px 6px;
            font-size: 2rem;
            line-height: 1.2;
            border: 1px solid #ccc;
            background: #f9f9f9;
            cursor: pointer;
            }
            .selection-btn.selected {
                color: var(--paars)
            }

            button:hover{
            color:red}

            `;
        } else {
            // === VERTICALE LAYOUT (zoals voorheen) ===
            css += `
            .selection-component-container {
                display: block;
            }
            .selection-category, .selection-subcategory, .selection-products {
                margin-bottom: 20px;
            }
            .selection-category h2, .selection-subcategory h3, .selection-products h3 {
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
            `;
        }
        var styleTag = document.createElement("style");
        styleTag.type = "text/css";
        styleTag.appendChild(document.createTextNode(css));
        document.head.appendChild(styleTag);
    };

    /**
     * init()
     * Bouw de basis-HTML op, afhankelijk van de oriëntatie.
     */
    SelectionComponent.prototype.init = function() {
        // Maak de wrapper (outer container)
        var wrapper = document.createElement("div");
        wrapper.className = "selection-component-container";
        this.container.appendChild(wrapper);

        if (this.orientation === "horizontal") {
            // HORIZONTALE OPZET: 2 rijen, 3 kolommen
            // Rij 1: kopjes
            var catHeading = document.createElement('div');
            catHeading.className = 'selection-category-heading';
            catHeading.textContent = 'Soort';
            wrapper.appendChild(catHeading);

            var subcatHeading = document.createElement('div');
            subcatHeading.className = 'selection-subcategory-heading';
            subcatHeading.textContent = 'Type';
            wrapper.appendChild(subcatHeading);

            var prodHeading = document.createElement('div');
            prodHeading.className = 'selection-products-heading';
            prodHeading.textContent = 'Typenummer';
            wrapper.appendChild(prodHeading);

            // Rij 2: lijsten
            // Category-list
            this.categoryListDiv = document.createElement('div');
            this.categoryListDiv.className = 'selection-category-list';
            wrapper.appendChild(this.categoryListDiv);

            // Subcategory-list
            this.subcategoryListDiv = document.createElement('div');
            this.subcategoryListDiv.className = 'selection-subcategory-list';
            wrapper.appendChild(this.subcategoryListDiv);

            // Product-list (alleen als showProducts true is)
            if (this.showProducts) {
                this.productListDiv = document.createElement('div');
                this.productListDiv.className = 'selection-products-list';
                wrapper.appendChild(this.productListDiv);
            }

        } else {
            // VERTICALE OPZET (oude manier)
            // We maken 3 containers onder elkaar
            this.categoryContainer = document.createElement('div');
            this.categoryContainer.className = 'selection-category';
            this.categoryContainer.innerHTML = '<h2>Categorieën</h2><div class="category-list"></div>';
            wrapper.appendChild(this.categoryContainer);

            this.subcategoryContainer = document.createElement('div');
            this.subcategoryContainer.className = 'selection-subcategory';
            this.subcategoryContainer.innerHTML = '<h3>Subcategorieën</h3><div class="subcategory-list"></div>';
            wrapper.appendChild(this.subcategoryContainer);

            if (this.showProducts) {
                this.productContainer = document.createElement('div');
                this.productContainer.className = 'selection-products';
                this.productContainer.innerHTML = '<h3>Producten</h3><div class="product-list"></div>';
                wrapper.appendChild(this.productContainer);
            }
        }

        // Start met data ophalen
        this.fetchProducts();
    };

    /**
     * fetchProducts()
     * Haal de producten op via this.endpoint en render de categorieën.
     */
    SelectionComponent.prototype.fetchProducts = function() {
        var self = this;
        fetch(this.endpoint)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                self.products = data;
                self.renderCategories();
            })
            .catch(function(error) {
                console.error("Fout bij ophalen van producten:", error);
            });
    };

    /**
     * renderCategories()
     * Toont de categorie-knoppen (horizontaal of verticaal).
     */
    SelectionComponent.prototype.renderCategories = function() {
        var categories = [...new Set(this.products.map(function(p) { return p.categorie; }))];
        var self = this;

        if (this.orientation === "horizontal") {
            // Zoek de categoryListDiv
            if (!this.categoryListDiv) return;
            this.categoryListDiv.innerHTML = '';
            categories.forEach(function(category) {
                var btn = document.createElement('button');
                btn.textContent = category;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedCategory = category;
                    self.selectedSubcategory = null;
                    self.selectedProduct = null;
                    self.highlightSelection(self.categoryListDiv, btn);
                    self.renderSubcategories(category);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                self.categoryListDiv.appendChild(btn);
            });
        } else {
            // VERTICAAL: we hebben in init() een container met class .category-list
            var categoryListDiv = this.categoryContainer.querySelector('.category-list');
            categoryListDiv.innerHTML = '';
            categories.forEach(function(category) {
                var btn = document.createElement('button');
                btn.textContent = category;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedCategory = category;
                    self.selectedSubcategory = null;
                    self.selectedProduct = null;
                    self.highlightSelection(categoryListDiv, btn);
                    self.renderSubcategories(category);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                categoryListDiv.appendChild(btn);
            });
        }
    };

    /**
     * renderSubcategories(category)
     * Toont de subcategorie-knoppen, passend bij de gekozen categorie.
     */
    SelectionComponent.prototype.renderSubcategories = function(category) {
        var filtered = this.products.filter(function(p) { return p.categorie === category; });
        var subcategories = [...new Set(filtered.map(function(p) { return p.subcategorie; }))];
        var self = this;

        if (this.orientation === "horizontal") {
            if (!this.subcategoryListDiv) return;
            this.subcategoryListDiv.innerHTML = '';
            subcategories.forEach(function(subcat) {
                var btn = document.createElement('button');
                btn.textContent = subcat;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedSubcategory = subcat;
                    self.selectedProduct = null;
                    self.highlightSelection(self.subcategoryListDiv, btn);
                    if (self.showProducts) {
                        self.renderProducts(category, subcat);
                    }
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                self.subcategoryListDiv.appendChild(btn);
            });
            // Als we products tonen, leeg de productListDiv
            if (this.showProducts && this.productListDiv) {
                this.productListDiv.innerHTML = '';
            }
        } else {
            var subcategoryListDiv = this.subcategoryContainer.querySelector('.subcategory-list');
            subcategoryListDiv.innerHTML = '';
            subcategories.forEach(function(subcat) {
                var btn = document.createElement('button');
                btn.textContent = subcat;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedSubcategory = subcat;
                    self.selectedProduct = null;
                    self.highlightSelection(subcategoryListDiv, btn);
                    if (self.showProducts) {
                        self.renderProducts(category, subcat);
                    }
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                subcategoryListDiv.appendChild(btn);
            });
            if (this.showProducts && this.productContainer) {
                var productListDiv = this.productContainer.querySelector('.product-list');
                productListDiv.innerHTML = '';
            }
        }
    };

    /**
     * renderProducts(category, subcategory)
     * Toont de product-knoppen, passend bij de gekozen subcategorie.
     */
    SelectionComponent.prototype.renderProducts = function(category, subcategory) {
        var filtered = this.products.filter(function(p) {
            return p.categorie === category && p.subcategorie === subcategory;
        });
        var self = this;

        if (this.orientation === "horizontal") {
            if (!this.productListDiv) return;
            this.productListDiv.innerHTML = '';
            filtered.forEach(function(product) {
                var btn = document.createElement('button');
                btn.textContent = product.TypeNummer;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedProduct = product;
                    self.highlightSelection(self.productListDiv, btn);
                    // Bij horizontale oriëntatie: redirect naar artikel/productnaam/index.php
                    window.location.href = '/artikelen/' + encodeURIComponent(product.TypeNummer) + '/index.php';
                });
                self.productListDiv.appendChild(btn);
                if (self.checkProductPage) {
                    self.checkProductPageExists(product.TypeNummer, btn);
                }
            });
        } else {
            // VERTICALE modus
            var productListDiv = this.productContainer.querySelector('.product-list');
            productListDiv.innerHTML = '';
            filtered.forEach(function(product) {
                var btn = document.createElement('button');
                btn.textContent = product.TypeNummer;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedProduct = product;
                    self.highlightSelection(productListDiv, btn);
                    // Standaard functionaliteit: roep onSelectionChange aan
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                productListDiv.appendChild(btn);
                if (self.checkProductPage) {
                    self.checkProductPageExists(product.TypeNummer, btn);
                }
            });
        }
    };

    /**
     * checkProductPageExists(productType, button)
     * Kijkt of artikelen/{productType}/index.php bestaat (HEAD request).
     * Als ja, dan tonen we een klikbaar 🔗-icoon om die pagina in een nieuwe tab te openen.
     */
    SelectionComponent.prototype.checkProductPageExists = function(productType, button) {
        fetch('artikelen/' + encodeURIComponent(productType) + '/index.php', { method: 'HEAD' })
            .then(function(response) {
                if (response.ok) {
                    var linkIcon = document.createElement('span');
                    linkIcon.textContent = " 🔗";
                    linkIcon.style.cursor = 'pointer';
                    linkIcon.style.color = '#007BFF';
                    linkIcon.title = "Bekijk productpagina";
                    linkIcon.addEventListener('click', function(e) {
                        e.stopPropagation();
                        window.open('artikelen/' + encodeURIComponent(productType) + '/index.php', '_blank');
                    });
                    button.appendChild(linkIcon);
                }
            })
            .catch(function(error) {
                // Als de productpagina niet bestaat, niets doen.
            });
    };

    /**
     * highlightSelection(container, selectedButton)
     * Markeer de aangeklikte knop als geselecteerd.
     */
    SelectionComponent.prototype.highlightSelection = function(container, selectedButton) {
        var buttons = container.querySelectorAll('button');
        buttons.forEach(function(btn) {
            btn.classList.remove('selected');
        });
        selectedButton.classList.add('selected');
    };

    // Exporteer de constructor
    window.SelectionComponent = SelectionComponent;
})(window, document);
