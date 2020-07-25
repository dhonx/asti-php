<?php
include "../utils.php";
include "../config.php";

authenticate();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../css.php" ?>
    <title>Dashboard - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Dashboard</h3>
    </main>
    <?php require_once "../scripts.php" ?>
</body>

</html>
