<?php
include_once __DIR__ . '/incs/sessie.php';

http_response_code(404);
$title = '404 - Pagina Niet Gevonden';
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';

// Voeg random achtergrondcode toe
$imagesDir = __DIR__ . '/frontHeros/';
$images = glob($imagesDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

if (!empty($images)) {
    $randomImage = basename($images[array_rand($images)]);
    $imageExtension = strtolower(pathinfo($randomImage, PATHINFO_EXTENSION));
} else {
    $randomImage = 'placeholder.png';
    $imageExtension = 'png';
}

if ($imageExtension === 'png') {
    $bgSize = "background-size: auto 100%;";
} else {
    $bgSize = "background-size: 100% auto;";
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            width: 100%;
            position: relative;
        }

        /* Achtergrondcontainer met 90% transparantie (dus 10% opaciteit) */
        .bg-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.3;
            background-repeat: no-repeat;
            background-position: center;
        }

        article {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        h1 {
            font-size: 50px;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/incs/menu.php'; ?>

    <!-- Achtergrondafbeelding -->
    <div class="bg-container" style="background-image: url('frontHeros/<?php echo $randomImage; ?>'); <?php echo $bgSize; ?>"></div>

    <article>
        <h1>404</h1>
        <p>Sorry, de pagina die je zoekt bestaat niet.</p>
    </article>
</body>

</html>