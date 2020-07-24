<?php
include_once "../../utils.php";
include_once "../../connection/connection.php";
include_once "../../config.php";
require('../../vendor/autoload.php');

use Rakit\Validation\Validator;

authenticate();

$errors = [];

if (isset($_POST["create_admin"])) {
    $validator = new Validator([
        "required"  => ":attribute harus diisi",
        "email"     => ":email bukan email yang valid",
        "min"       => ":attribute minimal berisi :min karakter/digit",
        "max"       => ":attribute maximal berisi :max karakter/digit",
        "same"      => "Konfirmasi sandi harus sama dengan sandi"
    ]);

    $validation = $validator->make($_POST, [
        "nama"                  => "required|min:6",
        "email"                 => "required|email",
        "nomor_hp"              => "required|numeric|min:8|max:12",
        "sandi"                 => "required|min:8",
        "konfirmasi_sandi"      => "required|same:sandi",
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama                   = $_POST["nama"];
        $email                  = $_POST["email"];
        $nomor_hp               = $_POST["nomor_hp"];
        $sandi                  = $_POST["sandi"];
        $konfirmasi_sandi       = $_POST["konfirmasi_sandi"];
        $status                 = isset($_POST["status"]) ? 1 : 0;
        $tipe_admin             = "admin";
        $encrypted_sandi        = password_hash($sandi, PASSWORD_BCRYPT);

        // Check if email is exist
        $query = htmlspecialchars("SELECT email FROM admin WHERE email = '$email'");
        $result = $connection->query($query);
        if ($result && $result->num_rows > 0) {
            array_push($errors, "Email $email sudah ada");
        } else {
            $query = htmlspecialchars(
                "INSERT INTO admin (nama, email, no_telp, sandi, aktif, tipe_admin) 
                    VALUES ('$nama', '$email', '$nomor_hp', '$encrypted_sandi', $status, '$tipe_admin')"
            );
            if ($connection->query($query) == TRUE) {
                $connection->close();
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
    <link href="<?= BASE_PATH ?>/css/tailwind.min.css" rel="stylesheet">
    <link href="<?= BASE_PATH ?>/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="<?= BASE_PATH ?>/css/main.compiled.css" rel="stylesheet">
    <title>Tambah Admin - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Admin</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php
                    foreach ($errors as $error) {
                        echo "<div>" .  $error . "</div>";
                    }
                    ?>
                </div>
            <?php } ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors && get_prev_field('nama') ?>">

            <label class="block" for="email">Email <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="5" name="email" required spellcheck="false" type="email" value="<?= $errors && get_prev_field('email') ?>">

            <label class="block" for="nomor_hp">No HP/Telp <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nomor_hp" maxlength="12" minlength="8" name="nomor_hp" required type="number" value="<?= $errors && get_prev_field('nomor_hp') ?>">

            <label class="block" for="sandi">Sandi <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="sandi" minlength="8" name="sandi" required type="password" value="<?= $errors && get_prev_field('sandi') ?>">

            <label class="block" for="konfirmasi_sandi">Konfirmasi Sandi <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="konfirmasi_sandi" minlength="8" name="konfirmasi_sandi" required type="password" value="<?= $errors && get_prev_field('konfirmasi_sandi') ?>">

            <span class="block">Status</span>
            <input class="bg-gray-200 inline-block ml-2 px-3 py-2" checked id="status" name="status" type="checkbox">
            <label class="cursor-pointer inline-block w-11/12" for="status">Aktif</label>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_admin" title="Tambah Admin" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../scripts.php" ?>
</body>

</html>
