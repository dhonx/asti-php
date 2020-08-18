<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

if (!isset($_GET["id_barang"]) && !is_numeric($_GET["id_barang"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_barang_to_update = $_GET["id_barang"];

$q_get_barang = "SELECT
                    `barang`.`kode_inventaris`,
                    `barang`.`aktif`,
                    `barang`.`kondisi`,
                    `barang`.`keterangan`,
                    `komponen`.`nama` AS `nama_komponen`
                FROM
                    `barang`
                INNER JOIN `perolehan`
                    ON `perolehan`.`id_perolehan` = `barang`.`id_perolehan`
                INNER JOIN `detail_perolehan`
                    ON `detail_perolehan`.`id_perolehan` = `perolehan`.`id_perolehan`
                INNER JOIN `komponen`
                    ON `komponen`.`id_komponen` = `detail_perolehan`.`id_komponen`
                WHERE
                    `id_barang` = $id_barang_to_update";
$r_get_barang = $connection->query($q_get_barang);
if ($r_get_barang && $r_get_barang->num_rows == 0) {
    redirect('./');
}

// Get barang query failed, uncomment this code for debugging purpose only
// else {
//     print_r($connection->error_list);
//     die();
// }

if (isset($_POST["update_barang"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "kondisi" => ["required", $validator("in", ["baik", "rusak ringan", "rusak berat"])],
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $kondisi    = $_POST["kondisi"];
        $status     = isset($_POST["status"]) ? 1 : 0;
        $keterangan = $connection->real_escape_string(clean($_POST["keterangan"]));

        $id_admin = get_current_id_admin();

        $q_update = "UPDATE 
                            `barang`
                        SET
                            `kondisi` = '$kondisi',
                            `aktif` = $status,
                            `keterangan` = '$keterangan'
                        WHERE
                            `id_barang` = $id_barang_to_update";

        if ($connection->query($q_update)) {
            redirect("./");
        }

        // Update barang query fails, uncomment this code for debugging purpose only
        // else {
        //     print_r($connection->error_list);
        //     die();
        // }
    }
}

while ($row = $r_get_barang->fetch_assoc()) {
    $data["kode_inventaris"] = $row["kode_inventaris"];
    $data["aktif"]           = $row["aktif"];
    $data["nama_komponen"]   = $row["nama_komponen"];
    $data["kondisi"]         = $row["kondisi"];
    $data["keterangan"]      = $row["keterangan"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Ubah Barang <?= $data["kode_inventaris"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Ubah Barang <?= $data["kode_inventaris"] ?></h3>

        <form action="?id_barang=<?= $id_barang_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="nama_komponen">Nama Komponen</label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" disabled id="nama_komponen" minlength="5" readonly required spellcheck="false" type="text" value="<?= $data["nama_komponen"] ?>">

            <label class="block" for="kondisi">Kondisi <span class="text-red-500" title="Harus diisi">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="kondisi" name="kondisi">
                <?php $prev_value = $errors ? get_prev_field("kondisi") : $data["kondisi"] ?>
                <option <?= $prev_value == "baik" ? "selected" : "" ?> value="baik">Baik</option>
                <option <?= $prev_value == "rusak ringan" ? "selected" : "" ?> value="rusak ringan">Rusak Ringan</option>
                <option <?= $prev_value == "rusak berat" ? "selected" : "" ?> value="rusak berat">Rusak Berat</option>
            </select>

            <span class="block">Status</span>
            <?php $status = $errors ? get_prev_field("status") : $data["aktif"] ?>
            <input class="bg-gray-200 inline-block ml-2 px-3 py-2" <?= $status == "on" || $status == 1 ? "checked" : "" ?> id="status" name="status" type="checkbox">
            <label class="cursor-pointer inline-block w-11/12" for="status">Aktif</label>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : $data["keterangan"] ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_barang" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
