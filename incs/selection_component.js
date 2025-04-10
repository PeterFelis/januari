/* selection_component.js - Responsive version */
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
        this.mobileMenuOpen = false;

        this.products = [];
        this.selectedCategory = null;
        this.selectedSubcategory = null;
        this.selectedProduct = null;

        this.injectCSS();
        this.init();
        this.setupResponsiveMenu();
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
                width: 1200px;
                margin: 0 auto;
                margin-bottom: 20px;
                padding: 20px;
                padding-top: 2rem;
                border-bottom: 2px solid var(--paars);
                position: relative; /* Voor positionering van mobiele menu knop */
            }
            .selection-category-heading,
            .selection-subcategory-heading,
            .selection-products-heading {
                text-align: center;
                font-weight: bold;
                font-size: 2rem;
                margin: 0;
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
                line-height: 1.2rem;
                cursor: pointer;
                padding: 0.5rem;
            }
            .selection-text.selected {
                font-weight: bold;
                color: var(--paars);
            }
            
            /* Mobiele menu stijlen */
            .mobile-menu-toggle {
                display: none;
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1000;
                background-color: var(--paars);
                color: white;
                border: none;
                border-radius: 4px;
                padding: 10px 15px;
                font-size: 16px;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            
            .mobile-menu-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 998;
            }
            
            .mobile-menu-container {
                display: none;
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100%;
                background-color: white;
                z-index: 999;
                overflow-y: auto;
                transition: left 0.3s ease;
                box-shadow: 2px 0 5px rgba(0,0,0,0.2);
                padding: 20px;
            }
            
            .mobile-menu-container.open {
                left: 0;
            }
            
            .mobile-menu-close {
                position: absolute;
                top: 10px;
                right: 10px;
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: var(--paars);
            }
            
            /* Responsieve stijlen */
            @media (max-width: 1200px) {
                .selection-component-container {
                    width: 100%;
                    padding: 15px;
                }
            }
            
            @media (max-width: 768px) {
                .selection-component-container {
                    display: none;
                }
                
                .mobile-menu-toggle {
                    display: block;
                }
                
                .mobile-menu-container, .mobile-menu-overlay {
                    display: block;
                }
                
                .mobile-menu-section {
                    margin-bottom: 20px;
                }
                
                .mobile-menu-section h3 {
                    font-size: 1.5rem;
                    margin-bottom: 10px;
                    color: var(--paars);
                }
                
                .mobile-menu-list {
                    display: flex;
                    flex-direction: column;
                    gap: 5px;
                }
                
                .mobile-menu-item {
                    padding: 8px 0;
                    font-size: 1.2rem;
                    cursor: pointer;
                    border-bottom: 1px solid #eee;
                }
                
                .mobile-menu-item.selected {
                    font-weight: bold;
                    color: var(--paars);
                }
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

    SelectionComponent.prototype.setupResponsiveMenu = function() {
        if (this.orientation !== "horizontal") return;
        
        var self = this;
        
        // Maak de mobiele menu toggle knop
        var menuToggle = document.createElement('button');
        menuToggle.className = 'mobile-menu-toggle';
        menuToggle.textContent = 'Keuze';
        document.body.appendChild(menuToggle);
        
        // Maak de overlay voor het mobiele menu
        var overlay = document.createElement('div');
        overlay.className = 'mobile-menu-overlay';
        document.body.appendChild(overlay);
        
        // Maak de container voor het mobiele menu
        var mobileMenu = document.createElement('div');
        mobileMenu.className = 'mobile-menu-container';
        document.body.appendChild(mobileMenu);
        
        // Voeg een sluitknop toe aan het mobiele menu
        var closeButton = document.createElement('button');
        closeButton.className = 'mobile-menu-close';
        closeButton.innerHTML = '&times;';
        mobileMenu.appendChild(closeButton);
        
        // Maak secties voor categorieën, subcategorieën en producten
        var categorySection = document.createElement('div');
        categorySection.className = 'mobile-menu-section';
        categorySection.innerHTML = '<h3>Soort</h3>';
        var categoryList = document.createElement('div');
        categoryList.className = 'mobile-menu-list category-list';
        categorySection.appendChild(categoryList);
        mobileMenu.appendChild(categorySection);
        
        var subcategorySection = document.createElement('div');
        subcategorySection.className = 'mobile-menu-section';
        subcategorySection.innerHTML = '<h3>Type</h3>';
        var subcategoryList = document.createElement('div');
        subcategoryList.className = 'mobile-menu-list subcategory-list';
        subcategorySection.appendChild(subcategoryList);
        mobileMenu.appendChild(subcategorySection);
        
        if (this.showProducts) {
            var productSection = document.createElement('div');
            productSection.className = 'mobile-menu-section';
            productSection.innerHTML = '<h3>Typenummer</h3>';
            var productList = document.createElement('div');
            productList.className = 'mobile-menu-list product-list';
            productSection.appendChild(productList);
            mobileMenu.appendChild(productSection);
        }
        
        // Event listeners voor het openen en sluiten van het menu
        menuToggle.addEventListener('click', function() {
            self.toggleMobileMenu(true);
        });
        
        closeButton.addEventListener('click', function() {
            self.toggleMobileMenu(false);
        });
        
        overlay.addEventListener('click', function() {
            self.toggleMobileMenu(false);
        });
        
        // Sla referenties op naar de mobiele menu elementen
        this.mobileMenuToggle = menuToggle;
        this.mobileMenuOverlay = overlay;
        this.mobileMenuContainer = mobileMenu;
        this.mobileCategoryList = categoryList;
        this.mobileSubcategoryList = subcategoryList;
        this.mobileProductList = this.showProducts ? productList : null;
    };
    
    SelectionComponent.prototype.toggleMobileMenu = function(open) {
        if (open) {
            this.mobileMenuContainer.classList.add('open');
            this.mobileMenuOverlay.style.display = 'block';
            this.mobileMenuOpen = true;
            
            // Vul het menu met de huidige data
            this.renderMobileCategories();
            if (this.selectedCategory) {
                this.renderMobileSubcategories(this.selectedCategory);
                if (this.selectedSubcategory && this.showProducts) {
                    this.renderMobileProducts(this.selectedCategory, this.selectedSubcategory);
                }
            }
        } else {
            this.mobileMenuContainer.classList.remove('open');
            this.mobileMenuOverlay.style.display = 'none';
            this.mobileMenuOpen = false;
        }
    };
    
    SelectionComponent.prototype.renderMobileCategories = function() {
        if (!this.mobileCategoryList) return;
        
        var categories = [...new Set(this.products.map(p => p.categorie))];
        var self = this;
        
        this.mobileCategoryList.innerHTML = '';
        categories.forEach(category => {
            var item = document.createElement('div');
            item.textContent = category;
            item.className = 'mobile-menu-item';
            if (this.selectedCategory === category) {
                item.classList.add('selected');
            }
            
            item.addEventListener('click', function() {
                self.selectedCategory = category;
                self.selectedSubcategory = null;
                self.selectedProduct = null;
                
                // Update selectie in mobiel menu
                self.highlightMobileSelection(self.mobileCategoryList, item);
                self.renderMobileSubcategories(category);
                
                // Update selectie in desktop menu als die zichtbaar is
                if (window.innerWidth > 768) {
                    var desktopItem = document.getElementById("category_" + sanitizeForId(category));
                    if (desktopItem) {
                        self.highlightSelection(self.categoryListDiv, desktopItem);
                        self.renderSubcategories(category);
                    }
                }
                
                self.onSelectionChange({
                    category: self.selectedCategory,
                    subcategory: self.selectedSubcategory,
                    product: self.selectedProduct
                });
            });
            
            self.mobileCategoryList.appendChild(item);
        });
    };
    
    SelectionComponent.prototype.renderMobileSubcategories = function(category) {
        if (!this.mobileSubcategoryList) return;
        
        var subcategories = [...new Set(this.products
            .filter(p => p.categorie === category)
            .map(p => p.subcategorie))];
        var self = this;
        
        this.mobileSubcategoryList.innerHTML = '';
        subcategories.forEach(subcat => {
            var item = document.createElement('div');
            item.textContent = subcat;
            item.className = 'mobile-menu-item';
            if (this.selectedSubcategory === subcat) {
                item.classList.add('selected');
            }
            
            item.addEventListener('click', function() {
                self.selectedSubcategory = subcat;
                
                // Update selectie in mobiel menu
                self.highlightMobileSelection(self.mobileSubcategoryList, item);
                if (self.showProducts) self.renderMobileProducts(category, subcat);
                
                // Update selectie in desktop menu als die zichtbaar is
                if (window.innerWidth > 768) {
                    var desktopItem = document.getElementById("subcategory_" + sanitizeForId(subcat));
                    if (desktopItem) {
                        self.highlightSelection(self.subcategoryListDiv, desktopItem);
                        if (self.showProducts) self.renderProducts(category, subcat);
                    }
                }
                
                self.onSelectionChange({
                    category: self.selectedCategory,
                    subcategory: self.selectedSubcategory,
                    product: self.selectedProduct
                });
            });
            
            self.mobileSubcategoryList.appendChild(item);
        });
    };
    
    SelectionComponent.prototype.renderMobileProducts = function(category, subcategory) {
        if (!this.mobileProductList || !this.showProducts) return;
        
        var filtered = this.products.filter(p => p.categorie === category && p.subcategorie === subcategory);
        var self = this;
        
        this.mobileProductList.innerHTML = '';
        filtered.forEach(product => {
            var item = document.createElement('div');
            item.textContent = product.TypeNummer;
            item.className = 'mobile-menu-item';
            if (this.selectedProduct && this.selectedProduct.TypeNummer === product.TypeNummer) {
                item.classList.add('selected');
            }
            
            item.addEventListener('click', function() {
                self.selectedProduct = product;
                
                // Update selectie in mobiel menu
                self.highlightMobileSelection(self.mobileProductList, item);
                
                // Update selectie in desktop menu als die zichtbaar is
                if (window.innerWidth > 768 && self.productListDiv) {
                    var productLinks = self.productListDiv.querySelectorAll('a');
                    for (var i = 0; i < productLinks.length; i++) {
                        if (productLinks[i].textContent === product.TypeNummer) {
                            self.highlightSelection(self.productListDiv, productLinks[i]);
                            break;
                        }
                    }
                }
                
                // Sluit het mobiele menu na productselectie
                self.toggleMobileMenu(false);
                
                // Als er een hoofdproduct is, gebruik dat als doellink, anders het TypeNummer
                var targetType = product.hoofd_product && product.hoofd_product.trim() !== "" ? product.hoofd_product : product.TypeNummer;
                window.location.href = '/artikelen/' + encodeURIComponent(targetType) + '/index.php';
                
                self.onSelectionChange({
                    category: self.selectedCategory,
                    subcategory: self.selectedSubcategory,
                    product: self.selectedProduct
                });
            });
            
            self.mobileProductList.appendChild(item);
        });
    };
    
    SelectionComponent.prototype.highlightMobileSelection = function(container, selectedElement) {
        var elements = container.querySelectorAll('.mobile-menu-item');
        elements.forEach(el => el.classList.remove('selected'));
        selectedElement.classList.add('selected');
    };

    SelectionComponent.prototype.fetchProducts = function() {
        var self = this;
        return fetch(this.endpoint)
            .then(response => response.json())
            .then(data => {
                // Alleen filteren als we op een echte productpagina zitten
                if (window.isProductPage) {
                    // Voor de zekerheid even trimmen en naar lowercase
                    data = data.filter(function(product) {
                        return product.leverbaar &&
                               product.leverbaar.trim().toLowerCase() === 'ja';
                    });
                }
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
                    
                    // Update mobiele menu als het open is
                    if (self.mobileMenuOpen) {
                        self.renderMobileCategories();
                        self.renderMobileSubcategories(category);
                    }
                    
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
                    
                    // Update mobiele menu als het open is
                    if (self.mobileMenuOpen) {
                        self.renderMobileSubcategories(category);
                        if (self.showProducts) self.renderMobileProducts(category, subcat);
                    }
                    
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
                var productContainer = document.createElement('span');
                productContainer.style.display = 'inline-block';
                productContainer.style.marginRight = '10px';
        
                // Als er een hoofdproduct is, gebruik dat als doellink, anders het TypeNummer.
                var targetType = product.hoofd_product && product.hoofd_product.trim() !== "" ? product.hoofd_product : product.TypeNummer;
        
                var productLink = document.createElement('a');
                productLink.textContent = product.TypeNummer;
                productLink.href = '/artikelen/' + encodeURIComponent(targetType) + '/index.php';
                productLink.className = 'selection-text';
        
                productLink.addEventListener('click', function() {
                    self.selectedProduct = product;
                    self.highlightSelection(self.productListDiv, productLink);
                    
                    // Update mobiele menu als het open is
                    if (self.mobileMenuOpen && self.showProducts) {
                        self.renderMobileProducts(category, subcategory);
                    }
                    
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
        
                productContainer.appendChild(productLink);
                self.productListDiv.appendChild(productContainer);
            });
        } else {
            var productListDiv = this.productContainer.querySelector('.product-list');
            productListDiv.innerHTML = '';
            filtered.forEach(product => {
                var btn = document.createElement('button');
                btn.className = 'selection-btn';
                var hasHoofd = product.hoofd_product && product.hoofd_product.trim() !== "";
                var content = product.TypeNummer;
                if (hasHoofd) {
                    content += ' <span class="hoofd-icon" style="cursor: pointer;" title="Open hoofdproduct">🏠</span>';
                }
                btn.innerHTML = content;
                
                btn.addEventListener('click', function(e) {
                    if(e.target.classList.contains('hoofd-icon')) return;
                    self.selectedProduct = product;
                    self.highlightSelection(productListDiv, btn);
                    self.onSelectionChange({
                        category: self.selectedCategory,
                        subcategory: self.selectedSubcategory,
                        product: self.selectedProduct
                    });
                });
                
                var iconSpan = btn.querySelector('.hoofd-icon');
                if (iconSpan) {
                    iconSpan.addEventListener('click', function(e) {
                        e.stopPropagation();
                        // Gebruik ook hier de hoofdproduct link als deze aanwezig is
                        var targetType = product.hoofd_product && product.hoofd_product.trim() !== "" ? product.hoofd_product : product.TypeNummer;
                        window.open('/artikelen/' + encodeURIComponent(targetType) + '/index.php', '_blank');
                    });
                }
                
                if (self.checkProductPage) {
                    self.checkProductPageExists(product.TypeNummer, btn);
                }
                productListDiv.appendChild(btn);
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
