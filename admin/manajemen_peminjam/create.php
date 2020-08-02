<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

if (isset($_POST["create_peminjam"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "nama"             => "required|min:6",
        "email"            => "required|email",
        "jabatan"          => "required|min:3",
        "no_telp"          => "required|min:8|max:12",
        "sandi"            => "required|min:8",
        "konfirmasi_sandi" => "required|min:8|same:sandi",
        "kategori"         => "required",
        "instansi"         => "required",
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama            = $connection->real_escape_string(clean($_POST["nama"]));
        $email           = $connection->real_escape_string(clean($_POST["email"]));
        $jabatan         = $connection->real_escape_string(clean($_POST["jabatan"]));
        $no_telp         = $connection->real_escape_string(clean($_POST["no_telp"]));
        $sandi           = $_POST["sandi"];
        $kategori        = $connection->real_escape_string(clean($_POST["kategori"]));
        $instansi        = $connection->real_escape_string(clean($_POST["instansi"]));
        $encrypted_sandi = password_hash($sandi, PASSWORD_BCRYPT);

        // Check is kategori is valid
        $q_check_kategori = "SELECT `id_kategori` FROM `kategori` WHERE `id_kategori` = '$kategori'";
        $r_check_kategori = $connection->query($q_check_kategori);

        // Check is instansi is valid
        $q_check_instansi = "SELECT `id_instansi` FROM `instansi` WHERE `id_instansi` = '$instansi'";
        $r_check_instansi = $connection->query($q_check_instansi);

        if ($r_check_kategori && $r_check_kategori->num_rows == 0) {
            array_push($errors, "Kategori tidak valid");
        } else if ($r_check_instansi && $r_check_instansi->num_rows == 0) {
            array_push($errors, "Instansi tidak valid");
        } else {
            $q_insert = "INSERT INTO `peminjam` (`nama`, `email`, `jabatan`, `no_telp`, `sandi`, `id_kategori`, `id_instansi`)
                        VALUES ('$nama', '$jabatan', '$email', '$no_telp', '$encrypted_sandi', $kategori, $instansi)";
            if ($connection->query($q_insert)) {
                redirect("./");
            }
        }
    }
}

$q_get_instansi = "SELECT `id_instansi`, `nama` FROM `instansi`";
$r_get_instansi = $connection->query($q_get_instansi);

$q_get_kategori = "SELECT `id_kategori`, `nama` FROM `kategori`";
$r_get_kategori = $connection->query($q_get_kategori);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Tambah Peminjam - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Peminjam</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('nama') : '' ?>">

            <label class="block" for="email">Email <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="5" name="email" required spellcheck="false" type="email" value="<?= $errors ? get_prev_field('email') : '' ?>">

            <label class="block" for="no_telp">No HP/Telp <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="no_telp" maxlength="12" minlength="8" name="no_telp" required type="number" value="<?= $errors ? get_prev_field('no_telp') : '' ?>">

            <label class="block" for="instansi">Instansi <span class="text-red-500" title="Harus diisi">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="instansi" name="instansi">
                <?php $prev_value = $errors ? get_prev_field("instansi") : "" ?>
                <?php while ($row_instansi = $r_get_instansi->fetch_assoc()) {
                    $v_id_instansi = $row_instansi["id_instansi"];
                    $v_nama_instansi = $row_instansi["nama"];
                ?>
                    <option <?= $prev_value == $v_id_instansi ? "selected" : "" ?> value="<?= $v_id_instansi ?>">
                        <?= $v_nama_instansi ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="jabatan">Jabatan <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="jabatan" minlength="5" name="jabatan" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('jabatan') : '' ?>">

            <label class="block" for="kategori">Kategori <span class="text-red-500" title="Harus diisi">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="kategori" name="kategori">
                <?php $prev_value = $errors ? get_prev_field("kategori") : "" ?>
                <?php while ($row_kategori = $r_get_kategori->fetch_assoc()) {
                    $v_id_kategori = $row_kategori["id_kategori"];
                    $v_nama_kategori = $row_kategori["nama"];
                ?>
                    <option <?= $prev_value == $v_id_kategori ? "selected" : "" ?> value="<?= $v_id_kategori ?>">
                        <?= $v_nama_kategori ?>
                    </option>
                <?php } ?>
            </select>

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
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_peminjam" title="Tambah Peminjam" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
