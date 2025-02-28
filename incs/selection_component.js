/* selection_component.js */
(function(window, document) {
    function sanitizeForId(str) {
        return str.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
    }

    function SelectionComponent(config) {
        this.container = typeof config.container === 'string' ? document.querySelector(config.container) : config.container;
        if (!this.container) throw new Error("Container element niet gevonden.");
        this.endpoint = config.endpoint || '/api_products.php';
        this.showProducts = config.showProducts || false;
        this.onSelectionChange = config.onSelectionChange || function() {};
        this.checkProductPage = config.checkProductPage || false;
        this.orientation = config.orientation || "vertical";

        this.categoryListDiv = null;
        this.subcategoryListDiv = null;
        this.productListDiv = null;
        this.categoryContainer = null;
        this.subcategoryContainer = null;
        this.productContainer = null;

        this.products = [];
        this.selectedCategory = null;
        this.selectedSubcategory = null;
        this.selectedProduct = null;

        this.injectCSS();
        this.init();
    }

    SelectionComponent.prototype.injectCSS = function() {
        var baseCss = `
        .product-card img {
            max-width: 100% !important;
            height: auto !important;
        }
        `;
        var css = "";
        if (this.orientation === "horizontal") {
            css += `
            .selection-component-container {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: auto auto;
                gap: 1rem;
                width: 100%;
                height: 15rem;
                margin-bottom: 20px;
                padding: 30px;
                padding-top: 5rem;
                border-bottom: 2px solid var(--paars);
            }
            .selection-category-heading,
            .selection-subcategory-heading,
            .selection-products-heading {
                text-align: center;
                font-weight: bold;
                font-size: 3rem;
            }
            .selection-category-heading { grid-column: 1; grid-row: 1; }
            .selection-subcategory-heading { grid-column: 2; grid-row: 1; }
            .selection-products-heading { grid-column: 3; grid-row: 1; }
            .selection-category-list,
            .selection-subcategory-list,
            .selection-products-list {
                grid-column: span 1;
                grid-row: 2;
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
            }
            /* Nieuwe stijl voor tekst (geen knop) */
            .selection-text {
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0.5rem;
            }
            .selection-text.selected {
                font-weight: bold;
                color: var(--paars);
            }
            `;
        } else {
            css += `
            .selection-component-container { display: block; }
            .selection-category, .selection-subcategory, .selection-products { margin-bottom: 20px; }
            .selection-category h2, .selection-subcategory h3, .selection-products h3 { margin: 0 0 10px; }
            /* Standaard stijl voor categorie- en subcategorieknoppen */
            .selection-btn {
                display: block;
                width: 100%;
                padding: 10px;
                margin-bottom: 5px;
                border: none;
                background: transparent;
                cursor: pointer;
                text-align: left;
                font-size: 1.5rem;
            }
            .selection-btn:hover { background-color: #f0f0f0; }
            .selection-btn.selected {
                font-weight: bold;
                background-color: #007BFF;
                color: white;
            }
            /* Wijziging voor de productweergave: gebruik grid met 4 kolommen */
            .selection-products .product-list {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 5px;
            }
            .selection-products .product-list .product-card {
                width: 100%;
            }
            .selection-products .product-list .product-card img {
                max-width: 100%;
                height: auto;
                display: block;
            }
            `;
        }
        var finalCss = baseCss + css;
        var styleTag = document.createElement("style");
        styleTag.appendChild(document.createTextNode(finalCss));
        document.head.appendChild(styleTag);
    };

    SelectionComponent.prototype.init = function() {
        var wrapper = document.createElement("div");
        wrapper.className = "selection-component-container";
        this.container.appendChild(wrapper);

        if (this.orientation === "horizontal") {
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

            this.categoryListDiv = document.createElement('div');
            this.categoryListDiv.className = 'selection-category-list';
            wrapper.appendChild(this.categoryListDiv);

            this.subcategoryListDiv = document.createElement('div');
            this.subcategoryListDiv.className = 'selection-subcategory-list';
            wrapper.appendChild(this.subcategoryListDiv);

            if (this.showProducts) {
                this.productListDiv = document.createElement('div');
                this.productListDiv.className = 'selection-products-list';
                wrapper.appendChild(this.productListDiv);
            }
        } else {
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
        this.fetchProducts();
    };

    SelectionComponent.prototype.fetchProducts = function() {
        var self = this;
        return fetch(this.endpoint)
            .then(response => response.json())
            .then(data => {
                self.products = data;
                self.renderCategories();
            })
            .catch(error => console.error("Fout bij ophalen van producten:", error));
    };

    SelectionComponent.prototype.renderCategories = function() {
        var categories = [...new Set(this.products.map(p => p.categorie))];
        var self = this;
        if (this.orientation === "horizontal") {
            if (!this.categoryListDiv) return;
            this.categoryListDiv.innerHTML = '';
            categories.forEach(category => {
                // Gebruik een <span> in plaats van een knop
                var span = document.createElement('span');
                span.textContent = category;
                span.className = 'selection-text';
                span.id = "category_" + sanitizeForId(category);
                span.addEventListener('click', function() {
                    self.selectedCategory = category;
                    self.selectedSubcategory = null;
                    self.selectedProduct = null;
                    self.highlightSelection(self.categoryListDiv, span);
                    self.renderSubcategories(category);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                self.categoryListDiv.appendChild(span);
            });
        } else {
            var categoryListDiv = this.categoryContainer.querySelector('.category-list');
            categoryListDiv.innerHTML = '';
            categories.forEach(category => {
                var btn = document.createElement('button');
                btn.textContent = category;
                btn.className = 'selection-btn';
                btn.id = "category_" + sanitizeForId(category);
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

    SelectionComponent.prototype.renderSubcategories = function(category) {
        var subcategories = [...new Set(this.products
            .filter(p => p.categorie === category)
            .map(p => p.subcategorie))];
        var self = this;
        
        if (this.orientation === "horizontal") {
            if (!this.subcategoryListDiv) return;
            this.subcategoryListDiv.innerHTML = '';
            
            subcategories.forEach(subcat => {
                var span = document.createElement('span');
                span.textContent = subcat;
                span.className = 'selection-text';
                span.id = "subcategory_" + sanitizeForId(subcat);
                span.addEventListener('click', function() {
                    self.selectedSubcategory = subcat;
                    self.highlightSelection(self.subcategoryListDiv, span);
                    if (self.showProducts) self.renderProducts(category, subcat);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                self.subcategoryListDiv.appendChild(span);
            });
        } else {
            var subcategoryListDiv = this.subcategoryContainer.querySelector('.subcategory-list');
            subcategoryListDiv.innerHTML = '';
            
            subcategories.forEach(subcat => {
                var btn = document.createElement('button');
                btn.textContent = subcat;
                btn.className = 'selection-btn';
                btn.id = "subcategory_" + sanitizeForId(subcat);
                btn.addEventListener('click', function() {
                    self.selectedSubcategory = subcat;
                    self.highlightSelection(subcategoryListDiv, btn);
                    if (self.showProducts) self.renderProducts(category, subcat);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                subcategoryListDiv.appendChild(btn);
            });
        }
    };

    SelectionComponent.prototype.renderProducts = function(category, subcategory) {
        var filtered = this.products.filter(p => p.categorie === category && p.subcategorie === subcategory);
        var self = this;
        if (this.orientation === "horizontal") {
            if (!this.productListDiv) return;
            this.productListDiv.innerHTML = '';
            filtered.forEach(product => {
                var span = document.createElement('span');
                span.textContent = product.TypeNummer;
                span.className = 'selection-text';
                span.addEventListener('click', function() {
                    self.selectedProduct = product;
                    self.highlightSelection(self.productListDiv, span);
                    var target = product.hoofd_product && product.hoofd_product.trim() !== "" ? product.hoofd_product : product.TypeNummer;
                    window.location.href = '/artikelen/' + encodeURIComponent(target) + '/index.php';
                });
                self.productListDiv.appendChild(span);
                if (self.checkProductPage) self.checkProductPageExists(product.TypeNummer, span);
            });
        } else {
            var productListDiv = this.productContainer.querySelector('.product-list');
            productListDiv.innerHTML = '';
            filtered.forEach(product => {
                var btn = document.createElement('button');
                btn.textContent = product.TypeNummer;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedProduct = product;
                    self.highlightSelection(productListDiv, btn);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                productListDiv.appendChild(btn);
                if (self.checkProductPage) self.checkProductPageExists(product.TypeNummer, btn);
            });
        }
    };

    SelectionComponent.prototype.checkProductPageExists = function(productType, element) {
        fetch('artikelen/' + encodeURIComponent(productType) + '/index.php', { method: 'HEAD' })
            .then(response => {
                if (response.ok) {
                    var linkIcon = document.createElement('span');
                    linkIcon.textContent = " 🔗";
                    linkIcon.style.cursor = 'pointer';
                    linkIcon.style.color = '#007BFF';
                    linkIcon.title = "Bekijk productpagina";
                    linkIcon.addEventListener('click', e => {
                        e.stopPropagation();
                        window.open('artikelen/' + encodeURIComponent(productType) + '/index.php', '_blank');
                    });
                    element.appendChild(linkIcon);
                }
            })
            .catch(() => {});
    };

    SelectionComponent.prototype.highlightSelection = function(container, selectedElement) {
        var elements = container.querySelectorAll('*');
        elements.forEach(el => el.classList.remove('selected'));
        selectedElement.classList.add('selected');
    };

    SelectionComponent.prototype.setSelected = function(category, subcategory) {
        var self = this;
        if (!this.products.length) {
            this.fetchProducts().then(() => {
                self.applySelection(category, subcategory);
            });
        } else {
            this.applySelection(category, subcategory);
        }
    };

    SelectionComponent.prototype.applySelection = function(category, subcategory) {
        var catId = "category_" + sanitizeForId(category);
        var subId = "subcategory_" + sanitizeForId(subcategory);
        var catElement = document.getElementById(catId);
        if (catElement) {
            this.selectedCategory = category;
            this.highlightSelection(this.orientation === "horizontal" ? this.categoryListDiv : this.categoryContainer.querySelector('.category-list'), catElement);
            this.renderSubcategories(category);
            var subElement = document.getElementById(subId);
            if (subElement) {
                this.selectedSubcategory = subcategory;
                this.highlightSelection(this.orientation === "horizontal" ? this.subcategoryListDiv : this.subcategoryContainer.querySelector('.subcategory-list'), subElement);
                if (this.showProducts) this.renderProducts(category, subcategory);
                this.onSelectionChange({
                    category: this.selectedCategory,
                    subcategory: this.selectedSubcategory,
                    product: this.selectedProduct
                });
            }
        }
    };

    window.SelectionComponent = SelectionComponent;
})(window, document);
