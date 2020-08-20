<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_peminjaman"]) && !is_numeric($_GET["id_peminjaman"])) {
    redirect("./");
}

$id_peminjaman = $_GET["id_peminjaman"];
$q_get_peminjaman =  "SELECT
                        `peminjaman`.`id_peminjaman`,
                        `peminjam`.`nama` AS `nama_peminjam`,
                        `komponen`.`nama` AS `nama_komponen`,
                        (
                            SELECT 
                                COUNT(*)
                            FROM 
                                `detail_peminjaman`
                            WHERE 
                                `detail_peminjaman`.`id_peminjaman` = `peminjaman`.`id_peminjaman`
                        ) AS `jumlah`,
                        `peminjaman`.`tanggal_peminjaman`,
                        `peminjaman`.`tanggal_mulai`,
                        `peminjaman`.`tanggal_selesai`,
                        `peminjaman`.`tujuan_peminjaman`,
                        `peminjaman`.`keterangan_peminjaman`
                    FROM
                        `peminjaman`
                    INNER JOIN `detail_peminjaman`
                        ON `detail_peminjaman`.`id_peminjaman` = `peminjaman`.`id_peminjaman`
                    INNER JOIN `peminjam`
                        ON `peminjam`.`id_peminjam` = `peminjaman`.`id_peminjam`
                    INNER JOIN `barang`
                        ON `barang`.`id_barang` = `detail_peminjaman`.`id_barang`
                    INNER JOIN `detail_perolehan`
                        ON `detail_perolehan`.`id_perolehan` = `barang`.`id_perolehan`
                    INNER JOIN `komponen`
                        ON `komponen`.`id_komponen` = `detail_perolehan`.`id_komponen`
                    WHERE
                        `peminjaman`.`id_peminjaman` = $id_peminjaman";
$r_get_peminjaman = $connection->query($q_get_peminjaman);
if ($r_get_peminjaman && $r_get_peminjaman->num_rows == 0) {
    redirect('./');
}

// Get peminjam fails, uncomment this code for debugging purpose only
// else {
//     print_r($connection->error_list);
//     die();
// }

while ($row = $r_get_peminjaman->fetch_assoc()) {
    $data["id_peminjaman"]          = $row["id_peminjaman"];
    $data["nama_peminjam"]          = $row["nama_peminjam"];
    $data["nama_komponen"]          = $row["nama_komponen"];
    $data["tanggal_peminjaman"]     = $row["tanggal_peminjaman"];
    $data["tanggal_mulai"]          = $row["tanggal_mulai"];
    $data["tanggal_selesai"]        = $row["tanggal_selesai"];
    $data["jumlah"]                 = $row["jumlah"];
    $data["tujuan_peminjaman"]      = $row["tujuan_peminjaman"];
    $data["keterangan_peminjaman"]  = $row["keterangan_peminjaman"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Peminjaman Barang <?= $data["nama_komponen"] ?> oleh <?= $data["nama_peminjam"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Peminjaman Barang <?= $data["nama_komponen"] ?> oleh <?= $data["nama_peminjam"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div class="mt-2">
                <span class="font-bold">Peminjam:</span>
                <span><?= $data["nama_peminjam"] ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Komponen:</span>
                <span><?= $data["nama_komponen"] ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Jumlah:</span>
                <span><?= $data["jumlah"] ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Tanggal Peminjaman:</span>
                <span><?= date_format(date_create($data["tanggal_peminjaman"]), "j F Y") ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Tanggal Mulai:</span>
                <span><?= date_format(date_create($data["tanggal_mulai"]), "j F Y") ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Tanggal Selesai:</span>
                <span><?= date_format(date_create($data["tanggal_selesai"]), "j F Y") ?></span>
            </div>

            <div class="block mt-2">
                <span class="block font-bold">Tujuan Peminjaman:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" style="min-height: 200px;" readonly><?= $data["tujuan_peminjaman"] ?></textarea>
            </div>

            <div class="block mt-2">
                <span class="block font-bold">Keterangan:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" style="min-height: 200px;" readonly><?= $data["keterangan_peminjaman"] ?></textarea>
            </div>

            <div class="border border-b mt-2"></div>

            <div class="flex">
                <a data-nama="Peminjaman Barang <?= $data["nama_komponen"] ?> oleh <?= $data["nama_peminjam"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_peminjaman=<?= $data["id_peminjaman"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
