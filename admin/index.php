<?php
session_start();
include "../utils.php";
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
    <link href="/asti/css/main.css" rel="stylesheet">
    <title>Dashboard - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200">
    <?php require_once "../header.php"; ?>
</body>

</html>
