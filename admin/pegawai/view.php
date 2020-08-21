<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_pegawai"]) && !is_numeric($_GET["id_pegawai"])) {
    redirect("./");
}

$id_pegawai = $_GET["id_pegawai"];
$q_get_pegawai =    "SELECT
                        `pegawai`.*,
                        `unit`.`nama` AS `nama_unit`
                    FROM
                        `pegawai`
                    INNER JOIN `unit`
                        ON `unit`.`id_unit` = `pegawai`.`id_unit`
                    WHERE
                        `id_pegawai` = $id_pegawai";
$r_get_pegawai = $connection->query($q_get_pegawai);
if ($r_get_pegawai && $r_get_pegawai->num_rows == 0) {
    redirect('./');
}

// Get pegawai fails, uncomment this code for debugging purpose only
// else {
//     print_r($connection->error_list);
//     die();
// }

while ($row = $r_get_pegawai->fetch_assoc()) {
    $data["id_pegawai"] = $row["id_pegawai"];
    $data["no_pegawai"] = $row["no_pegawai"];
    $data["nama"]       = $row["nama"];
    $data["email"]      = $row["email"];
    $data["id_unit"]    = $row["id_unit"];
    $data["nama_unit"]  = $row["nama_unit"];
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
    <title>Detail Pegawai <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Pegawai <?= $data["nama"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">No Pegawai:</span>
                <span><?= $data["no_pegawai"] ?></span>
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
                <span class="font-bold">Unit:</span>
                <span><?= $data["nama_unit"] ?></span>
                <a class="mdi mdi-information-outline" href="../unit/view?id_unit=<?= $data["id_unit"] ?>" title="Lihat detail unit"></a>
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
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_pegawai=<?= $data["id_pegawai"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil-outline"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_pegawai=<?= $data["id_pegawai"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
