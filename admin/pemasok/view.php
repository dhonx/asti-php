<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_pemasok"]) && !is_numeric($_GET["id_pemasok"])) {
    redirect("./");
}

$id_pemasok = $_GET["id_pemasok"];
$q_get_pemasok = "SELECT
                    `pemasok`.*,
                    `admin`.`nama` AS `nama_admin`
                FROM
                    `pemasok`
                INNER JOIN `admin`
                    ON `pemasok`.`id_admin` = `admin`.`id_admin`
                WHERE 
                    `id_pemasok` = $id_pemasok";
$r_get_pemasok = $connection->query($q_get_pemasok);
if ($r_get_pemasok && $r_get_pemasok->num_rows == 0) {
    redirect('./');
}

while ($row = $r_get_pemasok->fetch_assoc()) {
    $data["id_pemasok"]   = $row["id_pemasok"];
    $data["nama"]         = $row["nama"];
    $data["no_telp"]      = $row["no_telp"];
    $data["alamat"]       = $row["alamat"];
    $data["email"]        = $row["email"];
    $data["aktif"]        = $row["aktif"];
    $data["keterangan"]   = $row["keterangan"];
    $data["nama_admin"]   = $row["nama_admin"];
    $data["nama_pemilik"] = $row["nama_pemilik"];
    $data["created_at"]   = $row["created_at"];
    $data["updated_at"]   = $row["updated_at"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Pemasok <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Pemasok <?= $data["nama"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Nama:</span>
                <span><?= $data["nama"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Alamat:</span>
                <span><?= $data["alamat"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">No Telp:</span>
                <a href="tel:<?= $data["no_telp"] ?>"><?= $data["no_telp"] ?></a>
            </div>
            <div class="mt-2">
                <span class="font-bold">Email:</span>
                <a href="mailto:<?= $data["email"] ?>"><?= $data["email"] ?></a>
            </div>
            <div class="mt-2">
                <span class="font-bold">Nama Pemilik:</span>
                <span><?= $data["nama_pemilik"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Status:</span>
                <span class="rounded bg-<?= $data["aktif"] == 1 ? "blue" : "red" ?>-500 text-white py-1 px-3 text-xs font-bold">
                    <?= $data["aktif"] == 1 ? "aktif" : "tidak aktif" ?>
                </span>
            </div>
            <div class="block mt-2">
                <span class="block font-bold">Keterangan:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" style="min-height: 200px;" readonly><?= $data["keterangan"] ?></textarea>
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
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_pemasok=<?= $data["id_pemasok"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil-outline"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_pemasok=<?= $data["id_pemasok"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
