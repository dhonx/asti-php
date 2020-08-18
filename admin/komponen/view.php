<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_komponen"]) && !is_numeric($_GET["id_komponen"])) {
    redirect("./");
}

$id_komponen = $_GET["id_komponen"];

$q_get_komponen =   "SELECT
                        `komponen`.`nama`,
                        `komponen`.`tipe`,
                        `komponen`.`merek`,
                        `komponen`.`spesifikasi`,
                        `komponen`.`keterangan`,
                        `komponen`.`aktif`,
                        `komponen`.`created_at`,
                        `komponen`.`updated_at`,
                        `admin`.`nama` AS `nama_admin`
                    FROM
                        `komponen`
                    INNER JOIN
                        `admin` ON `komponen`.`id_admin` = `admin`.`id_admin`
                    WHERE
                        `komponen`.`id_komponen` = $id_komponen";
$r_get_komponen = $connection->query($q_get_komponen);
if ($r_get_komponen && $r_get_komponen->num_rows == 0) {
    redirect('./');
}

while ($row = $r_get_komponen->fetch_assoc()) {
    $data["nama"]        = $row["nama"];
    $data["tipe"]        = $row["tipe"];
    $data["merek"]       = $row["merek"];
    $data["spesifikasi"] = $row["spesifikasi"];
    $data["keterangan"]  = $row["keterangan"];
    $data["aktif"]       = $row["aktif"];
    $data["created_at"]  = $row["created_at"];
    $data["updated_at"]  = $row["updated_at"];
    $data["nama_admin"]  = $row["nama_admin"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Komponen <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Komponen <?= $data["nama"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Nama:</span>
                <span><?= $data["nama"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Tipe:</span>
                <span><?= $data["tipe"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Merek:</span>
                <span><?= $data["merek"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Status:</span>
                <span class="rounded bg-<?= $data["aktif"] == 1 ? "blue" : "red" ?>-500 text-white py-1 px-3 text-xs font-bold">
                    <?= $data["aktif"] == 1 ? "aktif" : "tidak aktif" ?>
                </span>
            </div>
            <div class="block mt-2">
                <span class="block font-bold">Spesifikasi:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" style="min-height: 200px;"><?= $data["spesifikasi"] ?></textarea>
            </div>
            <div class="block mt-2">
                <span class="block font-bold">Keterangan:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" style="min-height: 200px;"><?= $data["keterangan"] ?></textarea>
            </div>
            <div class="block mt-2">
                <span class="font-bold">Dibuat oleh:</span>
                <span><?= $data["nama_admin"] ?></span>
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
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_komponen=<?= $id_komponen ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil-outline"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_komponen=<?= $id_komponen ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
