<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
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
                `pegawai`.`nama` AS nama_pegawai,
                `komponen`.`nama` AS nama_komponen,
                `detail_pemesanan`.`jumlah`
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
    $nama_pegawai   = $row["nama_pegawai"];
    $nama_komponen  = $row["nama_komponen"];
    $jumlah         = $row["jumlah"];
    $status         = $row["status"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Pegawai <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Pegawai <?= $data["nama"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Nama Pegawai:</span>
                <span><?= $data["nama_pegawai"] ?></span>
            </div>
            <div>
                <span class="font-bold">Nama:</span>
                <span><?= $data["nama"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Email:</span>
                <a href="mailto:<?= $data["email"] ?>"><?= $data["email"] ?></a>
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
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_pemesanan=<?= $data["id_pemesanan"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_pemesanan=<?= $data["id_pemesanan"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
