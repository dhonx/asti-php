<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

// Redirect to refferer url if id_komponen is not valid
if (!isset($_GET["id_komponen"]) && !is_numeric($_GET["id_komponen"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_komponen_to_update = $_GET["id_komponen"];

$q_get_komponen =   "SELECT
                            `nama`,
                            `tipe`,
                            `merek`,
                            `spesifikasi`,
                            `keterangan`,
                            `aktif`
                        FROM
                            `komponen`
                        WHERE
                            `id_komponen` = $id_komponen_to_update";
$r_get_komponen = $connection->query($q_get_komponen);
if ($r_get_komponen && $r_get_komponen->num_rows == 0) {
    redirect('./');
}

if (isset($_POST["update_komponen"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "nama"        => "required|min:6",
        "tipe"        => "required",
        "merek"       => "required",
        // "spesifikasi" => "min:6",
        // "keterangan"  => "min:6",
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama        = $connection->real_escape_string(clean($_POST["nama"]));
        $tipe        = $connection->real_escape_string(clean($_POST["tipe"]));
        $merek       = $connection->real_escape_string(clean($_POST["merek"]));
        $spesifikasi = $connection->real_escape_string(clean($_POST["spesifikasi"]));
        $keterangan  = $connection->real_escape_string(clean($_POST["keterangan"]));
        $status      = isset($_POST["status"]) ? 1 : 0;

        $q_update = "UPDATE
                        `komponen` 
                    SET
                        `nama`  = '$nama',
                        `tipe`  = '$tipe',
                        `merek` = '$merek',
                        `spesifikasi` = '$spesifikasi',
                        `keterangan` = '$keterangan',
                        `aktif` = '$status'
                    WHERE
                        `id_komponen` = $id_komponen_to_update";
        if ($connection->query($q_update)) {
            redirect("./");
        }
    }
}

while ($row = $r_get_komponen->fetch_assoc()) {
    $data["nama"]        = $row["nama"];
    $data["tipe"]        = $row["tipe"];
    $data["merek"]       = $row["merek"];
    $data["spesifikasi"] = $row["spesifikasi"];
    $data["keterangan"]  = $row["keterangan"];
    $data["aktif"]       = $row["aktif"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Ubah Komponen <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Ubah Komponen <?= $data["nama"] ?></h3>

        <form action="?id_komponen=<?= $id_komponen_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">
            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("nama") : $data["nama"] ?>">

            <label class="block" for="tipe">Tipe <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tipe" name="tipe" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("tipe") : $data["tipe"] ?>">

            <label class="block" for="merek">Merek <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="merek" name="merek" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("merek") : $data["merek"] ?>">

            <label class="block" for="spesifikasi">Spesifikasi</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="spesifikasi" name="spesifikasi"><?= $errors ? get_prev_field("spesifikasi") : $data["spesifikasi"] ?></textarea>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan"><?= $errors ? get_prev_field("keterangan") : $data["keterangan"] ?></textarea>

            <span class="block">Status</span>
            <?php if ($errors) {
                $status_checked = get_prev_field("status") == "on";
            } else {
                $status_checked = $data["aktif"] == 1;
            } ?>
            <input class="bg-gray-200 inline-block ml-2 px-3 py-2" <?= $status_checked ? "checked" : "" ?> id="status" name="status" type="checkbox">
            <label class="cursor-pointer inline-block w-11/12" for="status">Aktif</label>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_komponen" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
