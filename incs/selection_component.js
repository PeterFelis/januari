// selection_component.js - Altijd inschuifmenu vanaf links
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
        // Deze flag gebruiken we om het andere gedrag (bewerken/bezichtigen) te activeren.
        this.checkProductPage = config.checkProductPage || false;

        this.products = [];
        this.selectedCategory = null;
        this.selectedSubcategory = null;
        this.selectedProduct = null;

        this.injectCSS();
        this.setupSlideInMenu();
        this.fetchProducts();
    }

    SelectionComponent.prototype.injectCSS = function() {
        var css = `
      .menu-toggle {
    position: fixed;
    top: 0;
    left: 0;
    width: 50px; /* Breedte van de strook, pas dit aan naar wens */
    height: 100vh; /* Volledige viewport hoogte */
    background: var(--paars, #6a1b9a);
    border: none;
    cursor: pointer;
}

.menu-toggle span {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-90deg);
    white-space: nowrap;
    color: white;
    font-weight: bold;
}
    
        .slidein-menu {
            position: fixed;
            top: 0;
            left: -300px;
            width: 300px;
            height: 100%;
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.3);
            padding: 20px;
            overflow-y: auto;
            transition: left 0.3s ease;
            z-index: 999;
        }
        .slidein-menu.open { left: 0; }
        .slidein-menu h3 {
            color: var(--paars, #6a1b9a);
            margin-top: 20px;
        }
        .menu-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        .menu-item.selected {
            font-weight: bold;
            color: var(--paars, #6a1b9a);
        }
        .menu-close {
            background: none;
            border: none;
            font-size: 1.2em;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: var(--paars, #6a1b9a);
        }
        .product-link-icon {
            background: none;
            border: none;
            margin-left: 10px;
            cursor: pointer;
            font-size: 1em;
        }
        `;
        var styleTag = document.createElement("style");
        styleTag.appendChild(document.createTextNode(css));
        document.head.appendChild(styleTag);
    };

    SelectionComponent.prototype.setupSlideInMenu = function() {
        var self = this;

        this.toggleBtn = document.createElement('button');
        this.toggleBtn.className = 'menu-toggle';
        var span = document.createElement('span');
        span.textContent = 'producten';
        this.toggleBtn.appendChild(span);
        document.body.appendChild(this.toggleBtn);

        this.menu = document.createElement('div');
        this.menu.className = 'slidein-menu';
        document.body.appendChild(this.menu);

        // Voeg een sluitknop toe (als eerste element in het menu)
        this.closeBtn = document.createElement('button');
        this.closeBtn.className = 'menu-close';
        this.closeBtn.textContent = '✖';
        this.closeBtn.addEventListener('click', function() {
            self.menu.classList.remove('open');
        });
        // Plaats de sluitknop vooraan in het menu
        this.menu.insertBefore(this.closeBtn, this.menu.firstChild);

        this.toggleBtn.addEventListener('click', function() {
            self.menu.classList.toggle('open');
        });
    };

    SelectionComponent.prototype.fetchProducts = function() {
        var self = this;
        fetch(this.endpoint)
            .then(r => r.json())
            .then(data => {
                self.products = data;
                self.renderMenu();
            })
            .catch(console.error);
    };

    SelectionComponent.prototype.renderMenu = function() {
        var self = this;
        // Leeg eerst het menu en voeg weer de sluitknop toe
        this.menu.innerHTML = '';
        var closeBtn = document.createElement('button');
        closeBtn.className = 'menu-close';
        closeBtn.textContent = '✖';
        closeBtn.addEventListener('click', function() {
            self.menu.classList.remove('open');
        });
        this.menu.appendChild(closeBtn);

        var catHeading = document.createElement('h3');
        catHeading.textContent = 'Categorieën';
        this.menu.appendChild(catHeading);

        var categories = [...new Set(this.products.map(p => p.categorie))];
        categories.forEach(cat => {
            var div = document.createElement('div');
            div.className = 'menu-item';
            div.textContent = cat;
            div.addEventListener('click', function() {
                self.selectedCategory = cat;
                self.selectedSubcategory = null;
                self.selectedProduct = null;
                self.renderMenu();
                self.onSelectionChange({ category: cat });
            });
            if (self.selectedCategory === cat) div.classList.add('selected');
            this.menu.appendChild(div);
        });

        if (this.selectedCategory) {
            var subHeading = document.createElement('h3');
            subHeading.textContent = 'Subcategorieën';
            this.menu.appendChild(subHeading);

            var subcats = [...new Set(this.products.filter(p => p.categorie === this.selectedCategory).map(p => p.subcategorie))];
            subcats.forEach(sub => {
                var div = document.createElement('div');
                div.className = 'menu-item';
                div.textContent = sub;
                div.addEventListener('click', function() {
                    self.selectedSubcategory = sub;
                    self.selectedProduct = null;
                    self.renderMenu();
                    self.onSelectionChange({ category: self.selectedCategory, subcategory: sub });
                });
                if (self.selectedSubcategory === sub) div.classList.add('selected');
                this.menu.appendChild(div);
            });
        }

        if (this.selectedCategory && this.selectedSubcategory && this.showProducts) {
            var prodHeading = document.createElement('h3');
            prodHeading.textContent = 'Producten';
            this.menu.appendChild(prodHeading);

            var prods = this.products.filter(p => p.categorie === this.selectedCategory && p.subcategorie === this.selectedSubcategory);
            prods.forEach(prod => {
                var productDiv = document.createElement('div');
                productDiv.className = 'menu-item product-item';
                if (this.checkProductPage) {
                    // Klik op de productnaam laadt de data voor bewerken
                    var productText = document.createElement('span');
                    productText.textContent = prod.TypeNummer;
                    productText.style.cursor = "pointer";
                    productText.addEventListener('click', function() {
                        self.selectedProduct = prod;
                        self.onSelectionChange({ product: prod });
                    });
                    productDiv.appendChild(productText);

                    // Voeg een icoon toe dat controleert of index.php beschikbaar is en zo ja opent in een nieuw tabblad
                    var target = prod.hoofd_product && prod.hoofd_product.trim() !== "" ? prod.hoofd_product : prod.TypeNummer;
                    var icon = document.createElement('button');
                    icon.className = 'product-link-icon';
                    icon.title = 'Bekijk product';
                    icon.textContent = '🔍';
                    icon.addEventListener('click', function(event) {
                        // Voorkom dat ook het bovenliggende element (productText) wordt getriggerd
                        event.stopPropagation();
                        var url = '/artikelen/' + encodeURIComponent(target) + '/index.php';
                        // Doe een HEAD-request om te controleren of de pagina bestaat
                        fetch(url, { method: 'HEAD' })
                            .then(response => {
                                if (response.ok) {
                                    window.open(url, '_blank');
                                } else {
                                    alert("Productpagina niet beschikbaar.");
                                }
                            })
                            .catch(() => {
                                alert("Productpagina niet beschikbaar.");
                            });
                    });
                    productDiv.appendChild(icon);
                } else {
                    // Voor de mobiele versie (of wanneer checkProductPage niet is ingesteld)
                    productDiv.textContent = prod.TypeNummer;
                    productDiv.addEventListener('click', function() {
                        var target = prod.hoofd_product && prod.hoofd_product.trim() !== "" ? prod.hoofd_product : prod.TypeNummer;
                        window.location.href = '/artikelen/' + encodeURIComponent(target) + '/index.php';
                    });
                }
                this.menu.appendChild(productDiv);
            });
        }
    };

    window.SelectionComponent = SelectionComponent;
})(window, document);
