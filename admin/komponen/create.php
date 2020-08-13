<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

if (isset($_POST["create_komponen"])) {
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
        $keterangan  = $connection->real_escape_string(clean($_POST["keterangan "]));
        $status      = isset($_POST["status"]) ? 1 : 0;

        $admin_email = $_SESSION["email"];
        $q_get_id_admin = "SELECT `id_admin` FROM `admin` WHERE `email` = '$admin_email'";
        $r_get_id_admin = $connection->query($q_get_id_admin);
        $first_row = $r_get_id_admin->fetch_assoc();
        $id_admin = $first_row["id_admin"];

        $q_insert = "INSERT INTO `komponen` (`nama`, `tipe`, `merek`, `spesifikasi`, `keterangan`, `aktif`, `id_admin`) 
                    VALUES ('$nama', '$tipe', '$merek', '$spesifikasi', '$keterangan', $status, $id_admin)";
        if ($connection->query($q_insert)) {
            redirect("./");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Tambah Komponen - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Komponen</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">
            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("nama") : "" ?>">

            <label class="block" for="tipe">Tipe <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tipe" name="tipe" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("tipe") : "" ?>">

            <label class="block" for="merek">Merek <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="merek" name="merek" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("merek") : "" ?>">

            <label class="block" for="spesifikasi">Spesifikasi</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="spesifikasi" name="spesifikasi" style="min-height: 200px;"><?= $errors ? get_prev_field("spesifikasi") : "" ?></textarea>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : "" ?></textarea>

            <span class="block">Status</span>
            <?php $status = $errors ? get_prev_field("status") : "" ?>
            <input class="bg-gray-200 inline-block ml-2 px-3 py-2" <?= $status == "" ? "checked" : "" ?> id="status" name="status" type="checkbox">
            <label class="cursor-pointer inline-block w-11/12" for="status">Aktif</label>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_komponen" title="Tambah Komponen" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
