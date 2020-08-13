<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_peminjam"]) && !is_numeric($_GET["id_peminjam"])) {
    redirect("./");
}

$id_peminjam = $_GET["id_peminjam"];
$q_get_peminjam = "SELECT
                        `peminjam`.`nama`,
                        `peminjam`.`jabatan`,
                        `peminjam`.`no_telp`,
                        `instansi`.`nama` AS `nama_instansi`,
                        `kategori`.`nama` AS `nama_kategori`,
                        `peminjam`.`created_at`,
                        `peminjam`.`updated_at`,
                        `peminjam`.`id_instansi`
                    FROM
                        `peminjam`, `instansi`, `kategori`
                    WHERE
                        `peminjam`.`id_instansi` = `instansi`.`id_instansi`
                    AND
                        `peminjam`.`id_kategori` = `kategori`.`id_kategori`
                    AND
                        `id_peminjam` = $id_peminjam";
$r_get_peminjam = $connection->query($q_get_peminjam);
if ($r_get_peminjam && $r_get_peminjam->num_rows == 0) {
    redirect('./');
}

while ($row = $r_get_peminjam->fetch_assoc()) {
    $data["nama"]          = $row["nama"];
    $data["jabatan"]       = $row["jabatan"];
    $data["no_telp"]       = $row["no_telp"];
    $data["nama_instansi"] = $row["nama_instansi"];
    $data["nama_kategori"] = $row["nama_kategori"];
    $data["created_at"]    = $row["created_at"];
    $data["updated_at"]    = $row["updated_at"];
    $data["id_instansi"]   = $row["id_instansi"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Peminjam <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Peminjam <?= $data["nama"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Nama:</span>
                <span><?= $data["nama"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Jabatan:</span>
                <span><?= $data["jabatan"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">No HP/Telp:</span>
                <a href="telp:<?= $data["no_telp"] ?>"><?= $data["no_telp"] ?></a>
            </div>
            <div class="mt-2">
                <span class="font-bold">Instansi:</span>
                <span>
                    <?= $data["nama_instansi"] ?>
                    <?php if ($data["id_instansi"] != 1) { ?>
                        <a class="mdi mdi-link" href="<?= build_url("/admin/instansi/view?id_instansi={$data["id_instansi"]}") ?>" title="Lihat detail instansi ini"></a>
                    <?php  } ?>
                </span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Kategori:</span>
                <span><?= $data["nama_kategori"] ?></span>
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
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_peminjam=<?= $id_peminjam ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_peminjam=<?= $id_peminjam ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
