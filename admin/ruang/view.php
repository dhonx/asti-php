<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_ruang"]) && !is_numeric($_GET["id_ruang"])) {
    redirect("./");
}

$id_ruang = $_GET["id_ruang"];
$q_get_ruang = "SELECT
                    `ruang`.*,
                    `unit`.`nama` AS `nama_unit`
                FROM
                    `ruang`
                INNER JOIN `unit`
                    ON `ruang`.`id_unit` = `unit`.`id_unit`
                WHERE
                    `id_ruang` = $id_ruang";
$r_get_ruang = $connection->query($q_get_ruang);
if ($r_get_ruang && $r_get_ruang->num_rows == 0) {
    redirect('./');
}

// DEBUG
// else {
//     print_r($connection->error_list);
//     die();
// }

while ($row = $r_get_ruang->fetch_assoc()) {
    $data["id_ruang"]   = $row["id_ruang"];
    $data["id_unit"]    = $row["id_unit"];
    $data["nama"]       = $row["nama"];
    $data["latitude"]   = $row["latitude"];
    $data["longitude"]  = $row["longitude"];
    $data["nama_unit"]  = $row["nama_unit"];
    $data["keterangan"] = $row["keterangan"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Ruang <?= $data["nama"] ?> - ASTI</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Ruang <?= $data["nama"] ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Nama Ruang:</span>
                <span><?= $data["nama"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Nama Unit:</span>
                <span>
                    <?= $data["nama_unit"] ?>
                    <a class="mdi mdi-link" href="../unit/view?id_unit=<?= $data["id_unit"] ?>" title="Lihat detail unit"></a>
                </span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Latitude:</span>
                <span><?= $data["latitude"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Longitude:</span>
                <span><?= $data["longitude"] ?></span>
            </div>

            <div class="flex mb-2 justify-center">
                <div class="w-full rounded-md" data-latitude="<?= $data["latitude"] ?>" data-longitude="<?= $data["longitude"] ?>" data-readonly="true" id="map" style="height:100vh; max-height: 500px"></div>
            </div>

            <a class="block my-2" href="https://www.google.com/maps/search/?api=1&query=<?= $data["latitude"] ?>,<?= $data["longitude"] ?>" target="_blank">Buka di google map</a>

            <div class="block mt-2">
                <span class="block font-bold">Keterangan:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" style="min-height: 200px;" readonly><?= $data["keterangan"] ?></textarea>
            </div>
            <div class="border border-b mt-2"></div>
            <div class="flex">
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_ruang=<?= $data["id_ruang"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_ruang=<?= $data["id_ruang"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
    <script defer src="<?= build_url("/admin/ruang/ruang.js") ?>"></script>
</body>

</html>
