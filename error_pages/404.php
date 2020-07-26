<?php
require_once "../utils.php";
$css = build_url("/error_pages/404.css");
$img = build_url("/error_pages/404.svg");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Tersesat di luar angkasa</title>
    <link rel="stylesheet" href="<?= $css ?>">
</head>

<body class="bg-purple">
    <div class="stars">
        <div class="central-body">
            <img class="image-404" src="<?= $img ?>" width="300px">
            <a href="../" class="btn-go-home" style="font-size: 1.1rem;font-weight: bold;">Kembali ke Rumah</a>
        </div>
        <div class="objects">
            <img class="object_rocket" src="http://salehriaz.com/404Page/img/rocket.svg" width="40px">
            <div class="earth-moon">
                <img class="object_earth" src="http://salehriaz.com/404Page/img/earth.svg" width="100px">
                <img class="object_moon" src="http://salehriaz.com/404Page/img/moon.svg" width="80px">
            </div>
            <div class="box_astronaut">
                <img class="object_astronaut" src="http://salehriaz.com/404Page/img/astronaut.svg" width="140px">
            </div>
        </div>
        <div class="glowing_stars">
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
        </div>
    </div>
</body>

</html>
