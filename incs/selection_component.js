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
        var css = "";
        if (this.orientation === "horizontal") {
            css += `
            .selection-component-container { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: auto auto; gap: 1rem; width: 100%; height:15rem; margin-bottom: 20px; padding: 30px; padding-top: 5rem; border-bottom:2px solid var(--paars); }
            .selection-category-heading, .selection-subcategory-heading, .selection-products-heading { text-align: center; font-weight: bold; font-size: 3rem; }
            .selection-category-heading { grid-column: 1; grid-row: 1; }
            .selection-subcategory-heading { grid-column: 2; grid-row: 1; }
            .selection-products-heading { grid-column: 3; grid-row: 1; }
            .selection-category-list { grid-column: 1; grid-row: 2; display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; }
            .selection-subcategory-list { grid-column: 2; grid-row: 2; display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; }
            .selection-products-list { grid-column: 3; grid-row: 2; display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; }
            .selection-btn { padding: 2px 6px; font-size: 2rem; line-height: 1.2; border: 1px solid #ccc; background: #f9f9f9; cursor: pointer; }
            .selection-btn.selected { color: var(--paars); }
            button:hover { color: red; }
            `;
        } else {
            css += `
            .selection-component-container { display: block; }
            .selection-category, .selection-subcategory, .selection-products { margin-bottom: 20px; }
            .selection-category h2, .selection-subcategory h3, .selection-products h3 { margin: 0 0 10px; }
            .selection-btn { padding: 5px 10px; margin: 3px; border: 1px solid #ccc; background: #f9f9f9; cursor: pointer; }
            .selection-btn.selected { font-weight: bold; background-color: #007BFF; color: white; }
            `;
        }
        var styleTag = document.createElement("style");
        styleTag.appendChild(document.createTextNode(css));
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
                var btn = document.createElement('button');
                btn.textContent = category;
                btn.className = 'selection-btn';
                btn.id = "category_" + sanitizeForId(category);
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
        var filtered = this.products.filter(p => p.categorie === category);
        var subcategories = [...new Set(filtered.map(p => p.subcategorie))];
        var self = this;
        if (this.orientation === "horizontal") {
            if (!this.subcategoryListDiv) return;
            this.subcategoryListDiv.innerHTML = '';
            subcategories.forEach(subcat => {
                var btn = document.createElement('button');
                btn.textContent = subcat;
                btn.className = 'selection-btn';
                btn.id = "subcategory_" + sanitizeForId(subcat);
                btn.addEventListener('click', function() {
                    self.selectedSubcategory = subcat;
                    self.selectedProduct = null;
                    self.highlightSelection(self.subcategoryListDiv, btn);
                    if (self.showProducts) self.renderProducts(category, subcat);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                self.subcategoryListDiv.appendChild(btn);
            });
            if (this.showProducts && this.productListDiv) this.productListDiv.innerHTML = '';
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
                    self.selectedProduct = null;
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
            if (this.showProducts && this.productContainer) this.productContainer.querySelector('.product-list').innerHTML = '';
        }
    };

    SelectionComponent.prototype.renderProducts = function(category, subcategory) {
        var filtered = this.products.filter(p => p.categorie === category && p.subcategorie === subcategory);
        var self = this;
        if (this.orientation === "horizontal") {
            if (!this.productListDiv) return;
            this.productListDiv.innerHTML = '';
            filtered.forEach(product => {
                var btn = document.createElement('button');
                btn.textContent = product.TypeNummer;
                btn.className = 'selection-btn';
                btn.addEventListener('click', function() {
                    self.selectedProduct = product;
                    self.highlightSelection(self.productListDiv, btn);
                    window.location.href = '/artikelen/' + encodeURIComponent(product.TypeNummer) + '/index.php';
                });
                self.productListDiv.appendChild(btn);
                if (self.checkProductPage) self.checkProductPageExists(product.TypeNummer, btn);
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

    SelectionComponent.prototype.checkProductPageExists = function(productType, button) {
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
                    button.appendChild(linkIcon);
                }
            })
            .catch(() => {});
    };

    SelectionComponent.prototype.highlightSelection = function(container, selectedButton) {
        var buttons = container.querySelectorAll('button');
        buttons.forEach(btn => btn.classList.remove('selected'));
        selectedButton.classList.add('selected');
    };

    SelectionComponent.prototype.setSelected = function(category, subcategory) {
        var self = this;
        if (!this.products.length) {
            // Wacht tot producten zijn geladen
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
        var catBtn = document.getElementById(catId);
        if (catBtn) {
            this.selectedCategory = category;
            this.highlightSelection(this.orientation === "horizontal" ? this.categoryListDiv : this.categoryContainer.querySelector('.category-list'), catBtn);
            this.renderSubcategories(category);
            var subBtn = document.getElementById(subId);
            if (subBtn) {
                this.selectedSubcategory = subcategory;
                this.highlightSelection(this.orientation === "horizontal" ? this.subcategoryListDiv : this.subcategoryContainer.querySelector('.subcategory-list'), subBtn);
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