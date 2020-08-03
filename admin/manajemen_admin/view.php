<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin"]);

if (!isset($_GET["id_admin"]) && !is_numeric($_GET["id_admin"])) {
    redirect("./");
}

$id_admin = $_GET["id_admin"];
$q_get_admin = "SELECT * FROM `admin` WHERE `tipe_admin` != 'super_admin' AND `id_admin` = $id_admin";
$r_get_admin = $connection->query($q_get_admin);
if ($r_get_admin && $r_get_admin->num_rows < 1) {
    redirect('./');
}

while ($row = $r_get_admin->fetch_assoc()) {
    $data["id_admin"]   = $row["id_admin"];
    $data["nama"]       = $row["nama"];
    $data["email"]      = $row["email"];
    $data["no_telp"]    = $row["no_telp"];
    $data["aktif"]      = $row["aktif"];
    $data["tipe_admin"] = $row["tipe_admin"];
    $data["created_at"] = $row["created_at"];
    $data["updated_at"] = $row["updated_at"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Admin <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Admin <?= $data["nama"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Nama:</span>
                <span><?= $data["nama"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Email:</span>
                <a href="mailto:<?= $data["email"] ?>"><?= $data["email"] ?></a>
            </div>
            <div class="mt-2">
                <span class="font-bold">No HP/Telp:</span>
                <a href="telp:<?= $data["no_telp"] ?>"><?= $data["no_telp"] ?></a>
            </div>
            <div class="mt-2">
                <span class="font-bold">Status:</span>
                <span class="rounded bg-<?= $data["aktif"] == 1 ? "blue" : "red" ?>-500 text-white py-1 px-3 text-xs font-bold">
                    <?= $data["aktif"] == 1 ? "aktif" : "tidak aktif" ?>
                </span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Tanggal dibuat:</span>
                <span><?= $data["created_at"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Tanggal terakhir diupdate:</span>
                <span><?= $data["updated_at"] ?></span>
            </div>
            <div class="border border-b mt-2"></div>
            <div class="flex">
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_admin=<?= $data["id_admin"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_admin=<?= $data["id_admin"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
