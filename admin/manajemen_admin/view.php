<?php
include_once "../../utils.php";
include_once "../../connection/connection.php";
include_once "../../config.php";
require('../../vendor/autoload.php');

authenticate();

$id_admin = 0;

if (!isset($_GET["id_admin"]) && !is_numeric($_GET["id_admin"])) {
    redirect("./index.php");
} else {
    $id_admin = $_GET["id_admin"];
    $query = "SELECT id_admin FROM admin WHERE id_admin = $id_admin";
    $result = $connection->query($query);
    if ($result && $result->num_rows < 1) {
        redirect('./index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASE_PATH; ?>/css/tailwind.min.css" rel="stylesheet">
    <link href="<?php echo BASE_PATH; ?>/css/main.css" rel="stylesheet">
    <title>Tambah Admin - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 overflow-hidden">
    <?php require_once "../../header.php"; ?>

    <main class="main">
        <h3 class="text-2xl font-bold py-2 page-header">View Admin Data</h3>

        <?php
        $data = null;
        if (!isset($_POST["update_admin"])) {
            $query = "SELECT * FROM admin WHERE id_admin = $id_admin";
            $result = $connection->query($query);
            while ($row = $result->fetch_row()) {
                $data["nama"]       = $row[1];
                $data["email"]      = $row[2];
                $data["no_telp"]    = $row[3];
                $data["aktif"]      = $row[5];
                $data["tipe_admin"] = $row[6];
            }
            $connection->close();
        }
        ?>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Nama:</span>
                <span><?php echo $data["nama"]; ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Email:</span>
                <span><?php echo $data["email"]; ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">No HP/Telp:</span>
                <a class="text-blue-500" href="telp:<?php echo $data["no_telp"]; ?>"><?php echo $data["no_telp"]; ?></a>
            </div>
            <div class="mt-2">
                <span class="font-bold">Status:</span>
                <span class="rounded bg-blue-400 text-white py-1 px-3 text-xs font-bold"><?php echo $data["aktif"] == 1 ? "aktif" : "tidak aktif"; ?></span>
            </div>
        </div>
    </main>
</body>

</html>