<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "shared.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_pemesanan"]) && !is_numeric($_GET["id_pemesanan"])) {
    redirect("./");
}

$id_pemesanan = $_GET["id_pemesanan"];
$q_get_pemesanan = "SELECT
                `pemesanan`.`id_pemesanan`,
                `pemesanan`.`tanggal_pesan`,
                `pemesanan`.`status`,
                `pemesanan`.`tanggal_pesan`,
                `pemesanan`.`keterangan`,
                `pegawai`.`nama` AS nama_pegawai,
                `komponen`.`nama` AS nama_komponen,
                `detail_pemesanan`.`jumlah`,
                `detail_pemesanan`.`id_komponen`,
                `pemesanan`.`id_pegawai`
            FROM
                `detail_pemesanan`
            INNER JOIN `pemesanan`
                ON `detail_pemesanan`.`id_pemesanan` = `pemesanan`.`id_pemesanan`
            INNER JOIN `pegawai`
                ON `pemesanan`.`id_pegawai` = `pegawai`.`id_pegawai`
            INNER JOIN `komponen`
                ON `detail_pemesanan`.`id_komponen` = `komponen`.`id_komponen`
            WHERE
                `pemesanan`.`id_pemesanan` = $id_pemesanan";

$r_get_pemesanan = $connection->query($q_get_pemesanan);
if ($r_get_pemesanan && $r_get_pemesanan->num_rows == 0) {
    redirect('./');
}

while ($row = $r_get_pemesanan->fetch_assoc()) {
    $id_pemesanan   = $row["id_pemesanan"];
    $id_komponen    = $row["id_komponen"];
    $id_pegawai     = $row["id_pegawai"];
    $nama_pegawai   = $row["nama_pegawai"];
    $nama_komponen  = $row["nama_komponen"];
    $jumlah         = $row["jumlah"];
    $status         = $row["status"];
    $tanggal_pesan  = $row["tanggal_pesan"];
    $keterangan     = $row["keterangan"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Pemesanan Barang <?= $nama_komponen ?> oleh <?= $nama_pegawai ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Pemesanan Barang <?= $nama_komponen ?> oleh <?= $nama_pegawai ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Barang:</span>
                <span><?= $nama_komponen ?></span>
                <a class="mdi mdi-information-outline" href="../komponen/view?id_komponen=<?= $id_komponen ?>" title="Lihat detail komponen ini"></a>
            </div>
            <div>
                <span class="font-bold">Dipesan oleh:</span>
                <span><?= $nama_pegawai ?></span>
                <a class="mdi mdi-information-outline" href="../pegawai/view?id_pegawai=<?= $id_pegawai ?>" title="Lihat detail pegawai ini"></a>
            </div>
            <div>
                <span class="font-bold">Jumlah:</span>
                <span><?= $jumlah ?></span>
            </div>
            <div>
                <span class="font-bold">Tanggal Pesan:</span>
                <span><?= $tanggal_pesan ?></span>
            </div>
            <div>
                <span class="font-bold">Status:</span>
                <span class="rounded <?= get_status_bg_color($status) ?> text-white py-1 px-3 text-xs font-bold">
                    <?= $status ?>
                </span>
            </div>
            <div>
                <span class="font-bold">Keterangan:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;" readonly><?= $keterangan ?></textarea>
            </div>
            <div class="border border-b mt-2"></div>
            <div class="flex">
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_pemesanan=<?= $id_pemesanan ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil-outline"></span>
                    Ubah
                </a>
                <a data-nama="<?= $nama_komponen ?> oleh <?= $nama_pegawai ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_pemesanan=<?= $id_pemesanan ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
