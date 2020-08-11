<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

if (isset($_POST["create_pemasok"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "nama"         => "required|min:3",
        "nomor_hp"     => "required|numeric",
        "alamat"       => "required|min:3",
        "email"        => "required|email",
        "nama_pemilik" => "required|min:3",
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama         = $connection->real_escape_string(clean($_POST["nama"]));
        $no_telp      = $_POST["nomor_hp"];
        $email        = $connection->real_escape_string(clean($_POST["email"]));
        $nama_pemilik = $connection->real_escape_string(clean($_POST["nama_pemilik"]));
        $status       = isset($_POST["aktif"]) ? 1 : 0;
        $keterangan   = $connection->real_escape_string(clean($_POST["keterangan"]));

        $admin_email = $_SESSION["email"];
        $q_get_id_admin = "SELECT `id_admin` FROM `admin` WHERE `email` = '$admin_email'";
        $r_get_id_admin = $connection->query($q_get_id_admin);
        $first_row = $r_get_id_admin->fetch_assoc();
        $id_admin = $first_row["id_admin"];

        // DEBUG
        // if (!$r_get_id_admin) {
        //     print_r($connection->error_list);
        // }

        $q_insert = "INSERT INTO
                        `pemasok` (`nama`, `no_telp`, `alamat`, `email`, `nama_pemilik`, `aktif`, `keterangan`, `id_admin`)
                    VALUES
                        ('$nama', '$no_telp', '$alamat', '$email', '$nama_pemilik', $status, '$keterangan', $id_admin)";

        if ($connection->query($q_insert)) {
            redirect("./");
        }

        // DEBUG
        // else {
        //     print_r($connection->error_list);
        // }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Tambah Pemasok - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Pemasok</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="nama">Nama Pemasok <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="3" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("nama") : "" ?>">

            <label class="block" for="email">Email <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="5" name="email" required spellcheck="false" type="email" value="<?= $errors ? get_prev_field("email") : "" ?>">

            <label class="block" for="nomor_hp">No HP/Telp <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nomor_hp" maxlength="12" minlength="8" name="nomor_hp" required type="number" value="<?= $errors ? get_prev_field("nomor_hp") : "" ?>">

            <label class="block" for="alamat">Alamat <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="alamat" minlength="3" name="alamat" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("alamat") : "" ?>">

            <label class="block" for="nama_pemilik">Nama Pemilik <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama_pemilik" minlength="3" name="nama_pemilik" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("nama_pemilik") : "" ?>">

            <div class="mb-2">
                <span class="block">Status</span>
                <?php if ($errors) {
                    $status_checked = get_prev_field("status") == "on";
                } else {
                    $status_checked = true;
                } ?>
                <input class="bg-gray-200 inline-block ml-2 px-3 py-2" <?= $status_checked ? "checked" : "" ?> id="status" name="status" type="checkbox">
                <label class="cursor-pointer inline-block w-11/12" for="status">Aktif</label>
            </div>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : "-" ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_pemasok" title="Tambah Pemasok" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
