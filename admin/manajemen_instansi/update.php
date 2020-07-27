<?php
include_once "../../utils.php";
include_once "../../connection/connection.php";
include_once "../../config.php";
require('../../vendor/autoload.php');

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

// Redirect to refferer url if id_instansi is not valid
if (!isset($_GET["id_instansi"]) && !is_numeric($_GET["id_instansi"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_admin_to_update = $_GET["id_instansi"];

// It's a update mode
if (isset($_POST["update_instansi"])) {
    $validator = new Validator(VALIDATION_MESSAGES);

    $validation = $validator->make($_POST, [
        "nama"     => "required|min:6",
        "email"    => "required|email",
        "nomor_hp" => "required|numeric|min:8|max:12",
        "alamat"   => "required|min:8|max:100",
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama     = $_POST["nama"];
        $email    = $_POST["email"];
        $nomor_hp = $_POST["nomor_hp"];
        $alamat   = $_POST["alamat"];

        // Check if email is exist
        $q_check_email = htmlspecialchars("SELECT email FROM instansi WHERE email = '$email' AND id_instansi != 1 AND id_instansi != $id_admin_to_update");
        $result = $connection->query($q_check_email);
        if ($result->num_rows > 0) {
            array_push($errors, "Email $email sudah ada");
        } else {
            $q_update = htmlspecialchars("UPDATE instansi SET nama = '$nama', email = '$email', no_telp = '$nomor_hp', alamat = $alamat WHERE id_instansi != 1 AND id_instansi = $id_admin_to_update");
            if ($connection->query(mysqli_real_escape_string($connection, $q_update)) == TRUE) {
                $connection->close();
                redirect("./");
            }
        }
    }
}

// It's GET mode
else {
    $query = "SELECT id_instansi FROM instansi WHERE id_instansi = $id_admin_to_update";
    $result = $connection->query($query);
    if ($result && $result->num_rows < 1) {
        redirect('./');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Ubah Instansi - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Update Instansi</h3>

        <form action="?id_instansi=<?= $id_admin_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

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
            if (!isset($_POST["update_instansi"])) {
                $query = "SELECT * FROM instansi WHERE id_instansi = " . $id_admin_to_update;
                $result = $connection->query($query);
                while ($row = $result->fetch_row()) {
                    $data["id_instansi"]    = $row[0];
                    $data["nama"]           = $row[1];
                    $data["email"]          = $row[2];
                    $data["alamat"]         = $row[3];
                    $data["no_telp"]        = (int)$row[4];
                    $data["created_at"]     = $row[5];
                    $data["updated_at"]     = $row[6];
                }
                $connection->close();
            }
            ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<? $errors && get_prev_field('nama');
                                                                                                                                                                        !$errors && prints($data['nama']) ?>">

            <label class="block" for="email">Email <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="5" name="email" required spellcheck="false" type="email" value="<? $errors && get_prev_field('email');
                                                                                                                                                                !$errors && prints($data['email']) ?>">

            <label class="block" for="nomor_hp">No HP/Telp <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nomor_hp" minlength="5" name="nomor_hp" required type="number" value="<? $errors && get_prev_field('nomor_hp');
                                                                                                                                                    !$errors && prints($data['no_telp']) ?>">

            <label class="block" for="alamat">Alamat <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="alamat" minlength="5" name="alamat" required spellcheck="false" type="text" value="<?= $errors && get_prev_field('alamat') ?>">
            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_instansi" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
