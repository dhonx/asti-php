<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

if (isset($_POST["create_pegawai"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "no_pegawai"       => "required|min:8",
        "nama"             => "required|min:6",
        "email"            => "required|email",
        "sandi"            => "required|min:8",
        "konfirmasi_sandi" => "required|min:8|same:sandi",
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $no_pegawai      = $connection->real_escape_string(clean($_POST["no_pegawai"]));
        $nama            = $connection->real_escape_string(clean($_POST["nama"]));
        $email           = $connection->real_escape_string(clean($_POST["email"]));
        $sandi           = $_POST["sandi"];
        $encrypted_sandi = password_hash($sandi, PASSWORD_BCRYPT);

        // Check if nomor pegawai is exist
        $q_check_no_pegawai =   "SELECT
                                    `no_pegawai`
                                FROM
                                    `pegawai`
                                WHERE
                                    `no_pegawai` = '$no_pegawai'";
        $r_check_no_pegawai = $connection->query($q_check_no_pegawai);

        // Check if email is exist
        $q_check_email =    "SELECT
                                `email`
                            FROM
                                `pegawai`
                            WHERE
                                `email` = '$email'";
        $r_check_email = $connection->query($q_check_email);

        if ($r_check_no_pegawai && $r_check_no_pegawai->num_rows != 0) {
            array_push($errors, "Nomor pegawai sudah ada");
        } else if ($r_check_email && $r_check_email->num_rows != 0) {
            array_push($errors, "Email $email sudah ada");
        } else {
            $q_insert = "INSERT INTO `pegawai` (`no_pegawai`, `nama`, `email`, `sandi`)
                        VALUES ('$no_pegawai', '$nama', '$email', '$encrypted_sandi')";
            if ($connection->query($q_insert)) {
                redirect("./");
            }
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
    <title>Tambah Pegawai - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Pegawai</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">
            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="no_pegawai">No Pegawai <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="no_pegawai" maxlength="12" minlength="8" name="no_pegawai" required type="number" value="<?= $errors ? get_prev_field('no_pegawai') : '' ?>">

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('nama') : '' ?>">

            <label class="block" for="email">Email <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="5" name="email" required spellcheck="false" type="email" value="<?= $errors ? get_prev_field('email') : '' ?>">

            <label class="block" for="sandi">Sandi <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="sandi" minlength="8" name="sandi" required type="password" value="<?= $errors ? get_prev_field('sandi') : '' ?>">

            <label class="block" for="konfirmasi_sandi">Konfirmasi Sandi <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="konfirmasi_sandi" minlength="8" name="konfirmasi_sandi" required type="password" value="<?= $errors ? get_prev_field('konfirmasi_sandi') : '' ?>">

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_pegawai" title="Tambah Pegawai" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
