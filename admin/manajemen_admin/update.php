<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin"]);

$errors = [];

// Redirect to refferer url if id_admin is not valid
if (!isset($_GET["id_admin"]) && !is_numeric($_GET["id_admin"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_admin_to_update = $_GET["id_admin"];
$is_post            = isset($_POST["update_admin"]);

$q_get_admin = "SELECT * FROM `admin` WHERE `id_admin` = $id_admin_to_update";
$r_get_admin = $connection->query($q_get_admin);
if ($r_get_admin && $r_get_admin->num_rows == 0) {
    redirect('./');
}

// It's a update mode
if ($is_post) {
    $validator = new Validator(VALIDATION_MESSAGES);

    $validation = $validator->make($_POST, [
        "nama"     => "required|min:6",
        "email"    => "required|email",
        "nomor_hp" => "required|min:8|max:12",
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama     = $connection->real_escape_string($_POST["nama"]);
        $email    = $connection->real_escape_string($_POST["email"]);
        $nomor_hp = $connection->real_escape_string($_POST["nomor_hp"]);
        $status   = isset($_POST["status"]) ? 1 : 0;

        // Check if email is exist
        $q_check_email = htmlspecialchars("SELECT email FROM `admin` WHERE `email` = '$email' AND `tipe_admin` != 'super_admin' AND `id_admin` != $id_admin_to_update");
        $result = $connection->query($q_check_email);
        if ($result && $result->num_rows > 0) {
            array_push($errors, "Email $email sudah ada");
        } else {
            $q_update = htmlspecialchars("UPDATE `admin` SET `nama` = '$nama', `email` = '$email', `no_telp` = '$nomor_hp', `aktif` = $status WHERE `tipe_admin` != 'super_admin' AND `id_admin` = $id_admin_to_update");
            if ($connection->query($q_update)) {
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
    <title>Ubah Admin - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Update Admin</h3>

        <form action="?id_admin=<?= $id_admin_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php
                    foreach ($errors as $error) {
                        echo "<div>" .  $error . "</div>";
                    }
                    ?>
                </div>
            <?php } ?>

            <?php
            if (!$is_post) {
                while ($row = $r_get_admin->fetch_assoc()) {
                    $data["nama"]       = $row["nama"];
                    $data["email"]      = $row["email"];
                    $data["no_telp"]    = $row["no_telp"];
                    $data["aktif"]      = $row["aktif"];
                    $data["tipe_admin"] = $row["tipe_admin"];
                }
            }
            ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('nama') : $data['nama'] ?>">

            <label class="block" for="email">Email <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="5" name="email" required spellcheck="false" type="email" value="<?= $errors ? get_prev_field('email') : $data['email'] ?>">

            <label class="block" for="nomor_hp">No HP/Telp <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nomor_hp" minlength="5" name="nomor_hp" required type="number" value="<?= $errors ? get_prev_field('nomor_hp') :  $data['no_telp'] ?>">

            <a class="block my-2" href="change_password?id_admin=<?= $id_admin_to_update ?>">Ganti sandi</a>

            <span class="block">Status</span>
            <?php
            if ($errors) $status_checked = get_prev_field("status") == "on";
            else $status_checked = $data["aktif"] == 1;
            ?>
            <input class="bg-gray-200 inline-block ml-2 px-3 py-2" <?= $status_checked ? "checked" : "" ?> id="status" name="status" type="checkbox">
            <label class="cursor-pointer inline-block w-11/12" for="status">Aktif</label>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_admin" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
