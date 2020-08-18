<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_instansi"]) && !is_numeric($_GET["id_instansi"])) {
    redirect("./");
}

$id_instansi = $_GET["id_instansi"];
$q_count_peminjam = "SELECT
                        COUNT(*)
                    FROM
                        `peminjam`
                    WHERE
                        `peminjam`.`id_instansi` = `instansi`.`id_instansi`";

$q_get_instansi = "SELECT
                        `instansi`.*,
                        ($q_count_peminjam) AS `jumlah_peminjam`
                    FROM
                        `instansi`
                    WHERE
                        `id_instansi` = $id_instansi";
$r_get_instansi = $connection->query($q_get_instansi);
if ($r_get_instansi && $r_get_instansi->num_rows == 0) {
    redirect('./');
}

while ($row = $r_get_instansi->fetch_assoc()) {
    $data["id_instansi"]     = $row["id_instansi"];
    $data["nama"]            = $row["nama"];
    $data["email"]           = $row["email"];
    $data["alamat"]          = $row["alamat"];
    $data["no_telp"]         = (int)$row["no_telp"];
    $data["jumlah_peminjam"] = $row["jumlah_peminjam"];
    $data["created_at"]      = $row["created_at"];
    $data["updated_at"]      = $row["updated_at"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Instansi <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Instansi <?= $data["nama"] ?></h3>

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
                <span class="font-bold">Alamat:</span>
                <span><?= $data["alamat"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Jumlah Peminjam:</span>
                <?php if ($data["jumlah_peminjam"] > 0) { ?>
                    <a href="<?= build_url("/admin/peminjam/?id_instansi=$id_instansi") ?>" title="Lihat peminjam dari instansi ini">
                        <?= $data["jumlah_peminjam"] ?>
                    </a>
                <?php } else { ?>
                    <span><?= $data["jumlah_peminjam"] ?></span>
                <?php } ?>
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
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_instansi=<?= $data["id_instansi"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil-outline"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_instansi=<?= $data["id_instansi"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
