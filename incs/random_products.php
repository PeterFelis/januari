<?php
// random_products.php

include_once __DIR__ . '/dbConnect.php';

/**
 * Berekent de laagste prijs uit de prijsstaffel-string.
 * De prijsstaffel wordt verwacht in het formaat: "32 7,86\n64 7,64\n..."
 */
function getLowestPrice($prijsstaffel)
{
    $lines = explode("\n", $prijsstaffel);
    $lowest = null;
    foreach ($lines as $line) {
        $parts = preg_split('/\s+/', trim($line));
        if (count($parts) >= 2) {
            // Vervang komma door punt voor een correcte float-interpretatie
            $price = floatval(str_replace(',', '.', $parts[1]));
            if ($lowest === null || $price < $lowest) {
                $lowest = $price;
            }
        }
    }
    return $lowest !== null ? number_format($lowest, 2, '.', '') : "n.v.t.";
}

// Maak verbinding met de database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Databaseverbinding mislukt: " . $e->getMessage();
    exit;
}

// Haal drie willekeurige producten op die leverbaar zijn
$stmt = $pdo->query("SELECT * FROM products WHERE leverbaar = 'ja' ORDER BY RAND() LIMIT 3");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class='containerProd'>
    <div class="random-products">
        <h2>Ontdek onze producten</h2>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <a href="/artikelen/<?php echo urlencode($product['TypeNummer']); ?>/index.php" class="product-link">
                    <div class="product-card">
                        <h3><?php echo htmlspecialchars($product['TypeNummer']); ?></h3>
                        <div class="product-image">
                            <img src="artikelen/<?php echo urlencode($product['TypeNummer']); ?>/Pfoto.png" alt="<?php echo htmlspecialchars($product['TypeNummer']); ?>">
                        </div>
                        <p>vanaf prijs: <?php echo getLowestPrice($product['prijsstaffel']); ?></p>
                        <div class="product-usp">
                            <?php echo $product['USP']; // USP als HTML weergeven 
                            ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .containerProd {
        width: 100%;
        padding: 10rem 0;
    }

    .random-products {
        margin: 10rem auto;
        text-align: center;
        padding: 0 1rem;
        max-width: 800px;
    }

    .random-products h2 {
        margin-bottom: 1.5rem;
    }

    .random-products .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: center;
    }

    /* Maak de link een flex-item zodat de hoogte gelijk getrokken kan worden */
    .random-products .product-link {
        display: flex;
        text-decoration: none;
        color: inherit;
        width: calc(33% - 1rem);
    }

    /* Zorg dat de productkaart de volledige beschikbare hoogte gebruikt */
    .random-products .product-card {
        flex: 1;
        display: flex;
        flex-direction: column;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1rem;
        width: 100%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .product-usp p {
        padding: 0;
        margin: 0;
    }

    .random-products .product-image img {
        width: 100%;
        height: auto;
        max-height: 200px;
        /* of een waarde die jij passend vindt */
        object-fit: contain;
    }

    /* Vergroot de afbeelding bij hover over de kaart */
    .random-products .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    /* Tablet (max 1024px): 3 kolommen */
    @media (max-width: 1024px) {

        

        .random-products .product-card h3 {
            font-size: 1.4rem;
            /* net iets kleiner */
        }

        .random-products .product-card p {
            font-size: 1rem;
        }
    }

    /* Mobiel (max 768px): 1 kolom */
    @media (max-width: 768px) {
        .random-products .product-link {
            width: 90%;
        }

        .random-products .product-card h3 {
            font-size: 3rem;
        }

        .random-products .product-card p {
            font-size: 1.4rem;
        }
    }
</style>