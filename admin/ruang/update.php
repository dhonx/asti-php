<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

// Redirect to refferer url if id_ruang is not valid
if (!isset($_GET["id_ruang"]) && !is_numeric($_GET["id_ruang"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_ruang_to_update = $_GET["id_ruang"];

$q_get_instansi = "SELECT * FROM `ruang` WHERE `id_ruang` = $id_ruang_to_update";
$r_get_ruang = $connection->query($q_get_instansi);
if ($r_get_ruang && $r_get_ruang->num_rows == 0) {
    redirect('./');
}

if (isset($_POST["update_ruang"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "nama"      => "required|min:3",
        "id_unit"   => "required|numeric|min:1",
        "latitude"  => "required|numeric",
        "longitude" => "required|numeric"
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama       = $connection->real_escape_string(clean($_POST["nama"]));
        $id_unit    = $_POST["id_unit"];
        $latitude   = $_POST["latitude"];
        $longitude  = $_POST["longitude"];
        $keterangan = $connection->real_escape_string(clean($_POST["keterangan"]));

        $q_check_unit = "SELECT `id_unit` FROM `unit` WHERE `id_unit` = $id_unit";
        $r_check_unit = $connection->query($q_check_unit);

        if ($r_check_unit && $r_check_unit->num_rows != 0) {
            $q_update = "UPDATE
                            `ruang`
                        SET
                            `nama` = '$nama',
                            `id_unit` = $id_unit,
                            `latitude` = '$latitude',
                            `longitude` = '$longitude',
                            `keterangan` = '$keterangan'
                        WHERE
                            `id_ruang` = $id_ruang_to_update";
            if ($connection->query($q_update)) {
                redirect("./");
            }
            // else {
            //     print_r($connection->error_list);
            //     die();
            // }
        }
    }
}

while ($row = $r_get_ruang->fetch_assoc()) {
    $data["nama"]       = $row["nama"];
    $data["latitude"]   = $row["latitude"];
    $data["longitude"]  = $row["longitude"];
    $data["keterangan"] = $row["keterangan"];
}

$q_get_unit = "SELECT * FROM `unit`";
$r_get_unit = $connection->query($q_get_unit);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <title>Ubah Unit <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Ubah Unit <?= $data["nama"] ?></h3>

        <form action="?id_ruang=<?= $id_ruang_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">
            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('nama') : $data['nama'] ?>">

            <label class="block" for="id_unit">Unit <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_unit" name="id_unit">
                <?php $prev_value = $errors ? get_prev_field("id_unit") : $data["id_unit"] ?>
                <?php while ($row = $r_get_unit->fetch_assoc()) {
                    $v_id_unit = $row["id_unit"];
                    $v_nama_unit = $row["nama"];
                ?>
                    <option <?= $prev_value == $v_id_unit ? "selected" : "" ?> value="<?= $v_id_unit ?>">
                        <?= $v_nama_unit ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="latitude">Latitude/Longitude <span class="text-red-500" title="Harus diisi">*</span></label>
            <div class="flex mb-2 w-full">
                <input autofocus class="bg-gray-200 flex mr-2 px-3 py-2 rounded-md w-full lg:w-1/2" id="latitude" name="latitude" placeholder="Latitude" readonly required type="number" value="<?= $errors ? get_prev_field("latitude") : $data["latitude"] ?>">
                <input autofocus class="bg-gray-200 flex mr-2 px-3 py-2 rounded-md w-full lg:w-1/2" id="longitude" name="longitude" placeholder="Longitude" readonly required type="number" value="<?= $errors ? get_prev_field("longitude") : $data["longitude"] ?>">
            </div>

            <div class="mb-2">
                Silahkan pilih lokasi di peta
            </div>

            <div class="flex mb-2 justify-center">
                <div class="w-full rounded-md" data-latitude="<?= $data["latitude"] ?>" data-longitude="<?= $data["longitude"] ?>" id="map" style="height:100vh; max-height: 500px"></div>
            </div>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : "-" ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_ruang" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
    <script defer src="<?= build_url("/admin/ruang/ruang.js") ?>"></script>
</body>

</html>
