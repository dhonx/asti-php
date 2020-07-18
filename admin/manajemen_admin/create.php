<?php
include "../../utils.php";
include_once "../../config.php";

session_start();
if (!isset($_SESSION['logged_in'])) {
    goto_login_page();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?php echo BASE_PATH; ?>/css/main.css" rel="stylesheet">
    <title>Tambah Admin - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200">
    <?php require_once "../../header.php"; ?>

    <main class="main">
        <h3 class="text-2xl font-bold p-2 page-header">Tambah Admin</h3>
    </main>
</body>

</html>
