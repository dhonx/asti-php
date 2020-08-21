<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_perolehan"]) && !is_numeric($_GET["id_perolehan"])) {
    redirect("./");
}

$id_perolehan = $_GET["id_perolehan"];
$q_get_perolehan =  "SELECT
                        `perolehan`.`id_perolehan`,
                        `komponen`.`nama` AS `nama_komponen`,
                        `pemasok`.`nama` AS `nama_pemasok`,
                        `detail_perolehan`.`harga_beli`,
                        `detail_perolehan`.`jumlah`,
                        `perolehan`.`tanggal`,
                        SUM(`detail_perolehan`.`harga_beli` * `detail_perolehan`.`jumlah`) `total`,
                        `perolehan`.`status`,
                        `perolehan`.`keterangan`
                    FROM
                        `perolehan`
                    INNER JOIN `detail_perolehan`
                        ON `detail_perolehan`.`id_perolehan` = `perolehan`.`id_perolehan`
                    INNER JOIN `komponen`
                        ON `komponen`.`id_komponen` = `detail_perolehan`.`id_komponen`
                    INNER JOIN `pemasok`
                        ON `pemasok`.`id_pemasok` = `perolehan`.`id_pemasok`
                    WHERE
                        `perolehan`.`id_perolehan` = $id_perolehan";
$r_get_perolehan = $connection->query($q_get_perolehan);
if ($r_get_perolehan && $r_get_perolehan->num_rows == 0) {
    redirect('./');
}

// Get perolehan query fails, uncomment this code for debuggin purpose only
// else {
//     print_r($connection->error_list);
//     die();
// }

while ($row = $r_get_perolehan->fetch_assoc()) {
    $data["id_perolehan"]  = $row["id_perolehan"];
    $data["nama_komponen"] = $row["nama_komponen"];
    $data["nama_pemasok"]  = $row["nama_pemasok"];
    $data["harga_beli"]    = $row["harga_beli"];
    $data["jumlah"]        = $row["jumlah"];
    $data["tanggal"]       = $row["tanggal"];
    $data["total"]         = $row["total"];
    $data["status"]        = $row["status"];
    $data["keterangan"]    = $row["keterangan"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Detail Perolehan <?= $data["nama_komponen"] ?> pada tanggal <?= date_format(date_create($data["tanggal"]), "j F Y") ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Detail Perolehan <?= $data["nama_komponen"] ?> pada tanggal <?= date_format(date_create($data["tanggal"]), "j F Y") ?></h3>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">Komponen:</span>
                <span><?= $data["nama_komponen"] ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Pemasok:</span>
                <span>
                    <?= $data["nama_pemasok"] ?>
                </span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Harga:</span>
                <span><?= number_format($data["harga_beli"], "2") ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Jumlah:</span>
                <span><?= $data["jumlah"] ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Total:</span>
                <span><?= number_format($data["total"], "2") ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Status:</span>
                <span><?= ucwords($data["status"]) ?></span>
            </div>

            <div class="mt-2">
                <span class="font-bold">Tanggal:</span>
                <span><?= date_format(date_create($data["tanggal"]), "j F Y") ?></span>
            </div>

            <div class="block mt-2">
                <span class="block font-bold">Keterangan:</span>
                <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" style="min-height: 200px;" readonly><?= $data["keterangan"] ?></textarea>
            </div>

            <div class="border border-b mt-2"></div>
            <div class="flex">
                <!-- <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_perolehan=<?= $data["id_perolehan"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil-outline"></span>
                    Ubah
                </a> -->
                <a data-nama="<?= $data["nama_komponen"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_perolehan=<?= $data["id_perolehan"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can-outline"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
