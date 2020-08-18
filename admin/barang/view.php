<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_barang"]) && !is_numeric($_GET["id_barang"])) {
    redirect("./");
}

$id_barang = $_GET["id_barang"];

$q_get_barang =   "SELECT
                    `barang`.*,
                    `detail_perolehan`.`harga_beli`,
                    `komponen`.`nama` AS `nama_komponen`,
                    `perolehan`.`id_perolehan`
                    -- `admin`.`nama` AS `nama_admin`
                FROM
                    `barang`
                -- INNER JOIN
                --     `admin` ON `barang`.`id_admin` = `admin`.`id_admin`
                INNER JOIN `perolehan`
                    ON `barang`.`id_perolehan` = `perolehan`.`id_perolehan`
                INNER JOIN `detail_perolehan`
                    ON `perolehan`.`id_perolehan` = `detail_perolehan`.`id_perolehan`
                INNER JOIN `komponen`
                    ON `komponen`.`id_komponen` = `detail_perolehan`.`id_komponen`
                WHERE
                    `barang`.`id_barang` = $id_barang";

$r_get_barang = $connection->query($q_get_barang);
if ($r_get_barang && $r_get_barang->num_rows == 0) {
    redirect('./');
}

// Query fails, Uncomment this code for debugging purpose only
// else {
//     print_r($connection->error_list);
//     die();
// }

while ($row = $r_get_barang->fetch_assoc()) {
    $data["kode_inventaris"] = $row["kode_inventaris"];
    $data["harga_beli"]      = $row["harga_beli"];
    $data["aktif"]           = $row["aktif"];
    $data["kondisi"]         = $row["kondisi"];
    $data["created_at"]      = $row["created_at"];
    $data["updated_at"]      = $row["updated_at"];
    $data["nama_komponen"]   = $row["nama_komponen"];
    $data["id_perolehan"]   = $row["id_perolehan"];
    // $data["nama_admin"]      = $row["nama_admin"];
    $data["keterangan"]      = $row["keterangan"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Barang <?= $data["kode_inventaris"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Barang <?= $data["kode_inventaris"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Kode Inventaris:</span>
                <span><?= $data["kode_inventaris"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Komponen:</span>
                <span><?= $data["nama_komponen"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Harga Beli:</span>
                <span><?= number_format($data["harga_beli"], 2) ?></span>
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
            <!-- <div class="block mt-2">
                <span class="font-bold">Dibuat oleh:</span>
                <span><?= $data["nama_admin"] ?></span>
            </div> -->
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
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="../perolehan/view?id_perolehan=<?= $data["id_perolehan"] ?>" title="Lihat detail perolehan">
                    <span class="mdi align-middle mdi-information-outline"></span>
                    Lihat detail perolehan
                </a>
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_barang=<?= $id_barang ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil-outline"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["kode_inventaris"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_barang=<?= $id_barang ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
