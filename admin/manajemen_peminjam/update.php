<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

// Redirect to refferer url if id_peminjam is not valid
if (!isset($_GET["id_peminjam"]) && !is_numeric($_GET["id_peminjam"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_peminjam_to_update = $_GET["id_peminjam"];
$is_post               = isset($_POST["update_pegawai"]);

$q_check_id_peminjam = "SELECT `id_peminjam` FROM `peminjam` WHERE `id_peminjam` = $id_peminjam_to_update";
$r_check_id_peminjam = $connection->query($q_check_id_peminjam);
if ($r_check_id_peminjam && $r_check_id_peminjam->num_rows < 1) {
    redirect('./');
}

// It's a update mode
if ($is_post) {
    $validator = new Validator(VALIDATION_MESSAGES);

    $validation = $validator->make($_POST, [
        "nama"             => "required|min:6",
        "jabatan"          => "required|min:3",
        "no_telp"          => "required|min:8|max:12",
        "kategori"         => "required",
        "instansi"         => "required",
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors   = $validation->errors()->firstOfAll();
    } else {
        $nama            = $connection->real_escape_string($_POST["nama"]);
        $jabatan         = $connection->real_escape_string($_POST["jabatan"]);
        $no_telp         = $connection->real_escape_string($_POST["no_telp"]);
        $kategori        = $connection->real_escape_string($_POST["kategori"]);
        $instansi        = $connection->real_escape_string($_POST["instansi"]);

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
            $q_update = "UPDATE
                            `peminjam`
                        SET
                            `nama` = '$nama',
                            `jabatan` = '$jabatan', 
                            `no_telp` = '$no_telp',
                            `id_instansi` = $instansi,
                            `id_kategori` = $kategori
                        WHERE
                            `id_peminjam` = $id_peminjam_to_update";
            $q_update = htmlspecialchars($q_update);
            if ($connection->query($q_update)) {
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
    <title>Ubah Peminjam - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Update Peminjam</h3>

        <form action="?id_peminjam=<?= $id_peminjam_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) {
                        echo "<div>" .  $error . "</div>";
                    } ?>
                </div>
            <?php } ?>

            <?php
            if (!$is_post) {
                $query = "SELECT * FROM `peminjam` WHERE `id_peminjam` = $id_peminjam_to_update";
                $result = $connection->query($query);
                while ($row = $result->fetch_assoc()) {
                    $data["nama"]        = $row["nama"];
                    $data["jabatan"]     = $row["jabatan"];
                    $data["no_telp"]     = $row["no_telp"];
                    $data["id_instansi"] = $row["id_instansi"];
                    $data["id_kategori"] = $row["id_kategori"];
                }
            }
            ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('nama') : $data["nama"] ?>">

            <label class="block" for="jabatan">Jabatan <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="jabatan" minlength="5" name="jabatan" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('jabatan') : $data["jabatan"] ?>">

            <label class="block" for="no_telp">No HP/Telp <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="no_telp" maxlength="12" minlength="8" name="no_telp" required type="number" value="<?= $errors ? get_prev_field('no_telp') : $data["no_telp"] ?>">

            <label class="block" for="instansi">Instansi <span class="text-red-500" title="Harus diisi">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="instansi" name="instansi">
                <?php $prev_value = $errors ? get_prev_field("instansi") : $data["id_instansi"] ?>
                <?php while ($row_instansi = $r_get_instansi->fetch_assoc()) {
                    $v_id_instansi = $row_instansi["id_instansi"];
                    $v_nama_instansi = $row_instansi["nama"];
                ?>
                    <option <?= $prev_value == $v_id_instansi ? "selected" : "" ?> value="<?= $v_id_instansi ?>">
                        <?= $v_nama_instansi ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="kategori">Kategori <span class="text-red-500" title="Harus diisi">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="kategori" name="kategori">
                <?php $prev_value = $errors ? get_prev_field("kategori") : $data["id_kategori"] ?>
                <?php while ($row_kategori = $r_get_kategori->fetch_assoc()) {
                    $v_id_kategori = $row_kategori["id_kategori"];
                    $v_nama_kategori = $row_kategori["nama"];
                ?>
                    <option <?= $prev_value == $v_id_kategori ? "selected" : "" ?> value="<?= $v_id_kategori ?>">
                        <?= $v_nama_kategori ?>
                    </option>
                <?php } ?>
            </select>

            <a class="block my-2" href="change_password?id_peminjam=<?= $id_peminjam_to_update ?>">Ganti sandi</a>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_pegawai" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
