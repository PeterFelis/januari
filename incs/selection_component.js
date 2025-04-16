// selection_component.js - Altijd inschuifmenu vanaf links
(function(window, document) {
    function sanitizeForId(str) {
        return str.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
    }

    function SelectionComponent(config) {
        this.container = typeof config.container === 'string' ? document.querySelector(config.container) : config.container;
        if (!this.container) throw new Error("Container element niet gevonden.");
        this.endpoint = config.endpoint || '/api_products.php';
        // Stel hier standaard in of je producten ook in het menu wilt tonen. Voor grid-laad gedrag stel je deze op false.
        this.showProducts = config.showProducts || false;
        this.onSelectionChange = config.onSelectionChange || function() {};
        // Deze flag gebruiken we voor ander gedrag (bewerken/bezichtigen) indien gewenst.
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
        var css =` 
      .menu-toggle {
          position: fixed;
          top: 0;
          left: 0;
          width: 40px; /* Breedte van de strook, pas dit aan naar wens */
          height: 100vh; /* Volledige viewport hoogte */
          background: rgba(106, 27, 154, 0.5);
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

        // Maak de toggle-knop aan
        this.toggleBtn = document.createElement('button');
        this.toggleBtn.className = 'menu-toggle';
        var span = document.createElement('span');
        span.textContent = 'producten';
        this.toggleBtn.appendChild(span);
        document.body.appendChild(this.toggleBtn);

        // Maak het slide-in menu aan
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
        this.menu.insertBefore(this.closeBtn, this.menu.firstChild);

        // Toggle de zichtbaarheid bij klikken op de toggle-knop
        this.toggleBtn.addEventListener('click', function() {
            self.menu.classList.toggle('open');
            if (self.menu.classList.contains('open')) {
                // Enkel her-renderen (zonder reset-callback)
                self.renderMenu();
            }
        });

        // Dubbelklik overal in het menu sluit het component
        this.menu.addEventListener('dblclick', function(e) {
            self.menu.classList.remove('open');
        });

        // Klik op de 'lege' ruimte in het menu sluit het component
        this.menu.addEventListener('click', function(e) {
            if (e.target === self.menu) {
                self.menu.classList.remove('open');
            }
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

        // --- 1. Categorieën ---
        var catHeading = document.createElement('h3');
        catHeading.textContent = 'Categorieën';
        this.menu.appendChild(catHeading);

        var categories = [...new Set(this.products.map(p => p.categorie))];
        categories.forEach(cat => {
            var div = document.createElement('div');
            div.className = 'menu-item';
            div.textContent = cat;
            // Single click: update de selectie en reset subcategorie en product
            div.addEventListener('click', function() {
                self.selectedCategory = cat;
                self.selectedSubcategory = null;
                self.selectedProduct = null;
                self.renderMenu();
                self.onSelectionChange({ category: cat, subcategory: null, product: null });
            });
            // Double click: sluit het menu
            div.addEventListener('dblclick', function() {
                self.menu.classList.remove('open');
            });
            if (self.selectedCategory === cat) div.classList.add('selected');
            this.menu.appendChild(div);
        });

        // --- 2. Subcategorieën ---
        if (this.selectedCategory) {
            var subHeading = document.createElement('h3');
            subHeading.textContent = 'Subcategorieën';
            this.menu.appendChild(subHeading);

            var subcats = [...new Set(this.products
                .filter(p => p.categorie === this.selectedCategory)
                .map(p => p.subcategorie))];

            subcats.forEach(sub => {
                var div = document.createElement('div');
                div.className = 'menu-item';
                div.textContent = sub;
                // Single click: update de subcategorie en reset product
                div.addEventListener('click', function() {
                    self.selectedSubcategory = sub;
                    self.selectedProduct = null;
                    self.renderMenu();
                    self.onSelectionChange({ category: self.selectedCategory, subcategory: sub, product: null });
                });
                // Double click: sluit het menu
                div.addEventListener('dblclick', function() {
                    self.menu.classList.remove('open');
                });
                if (self.selectedSubcategory === sub) div.classList.add('selected');
                this.menu.appendChild(div);
            });
        }

        // --- 3. Producten (indien 'showProducts' actief is) ---
        if (this.selectedCategory && this.selectedSubcategory && this.showProducts) {
            var prodHeading = document.createElement('h3');
            prodHeading.textContent = 'Producten';
            this.menu.appendChild(prodHeading);

            var prods = this.products.filter(p =>
                p.categorie === this.selectedCategory &&
                p.subcategorie === this.selectedSubcategory
            );
            prods.forEach(prod => {
                var productDiv = document.createElement('div');
                productDiv.className = 'menu-item product-item';

                if (this.checkProductPage) {
                    var productText = document.createElement('span');
                    productText.textContent = prod.TypeNummer;
                    productText.style.cursor = "pointer";
                    productText.addEventListener('click', function() {
                        self.selectedProduct = prod;
                        self.onSelectionChange({ product: prod });
                    });
                    productText.addEventListener('dblclick', function() {
                        self.menu.classList.remove('open');
                    });
                    productDiv.appendChild(productText);

                    var target = prod.hoofd_product && prod.hoofd_product.trim() !== "" ?
                        prod.hoofd_product : prod.TypeNummer;
                    var icon = document.createElement('button');
                    icon.className = 'product-link-icon';
                    icon.title = 'Bekijk product';
                    icon.textContent = '🔍';
                    icon.addEventListener('click', function(event) {
                        event.stopPropagation();
                        var url = '/artikelen/' + encodeURIComponent(target) + '/index.php';
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
                    productDiv.addEventListener('click', function() {
                        var target = prod.hoofd_product && prod.hoofd_product.trim() !== "" ?
                            prod.hoofd_product : prod.TypeNummer;
                        window.location.href = '/artikelen/' + encodeURIComponent(target) + '/index.php';
                    });
                    productDiv.addEventListener('dblclick', function() {
                        self.menu.classList.remove('open');
                    });
                    productDiv.textContent = prod.TypeNummer;
                }
                this.menu.appendChild(productDiv);
            });
        }
    };

    window.SelectionComponent = SelectionComponent;
})(window, document);



