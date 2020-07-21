<?php
include_once "../../utils.php";
include_once "../../connection/connection.php";
include_once "../../config.php";
require('../../vendor/autoload.php');

use Rakit\Validation\Validator;

authenticate();

$errors = [];

// Redirect to refferer url if id_admin is not valid
if (!isset($_GET["id_admin"]) && !is_numeric($_GET["id_admin"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

// It's a update mode
if (isset($_POST["update_admin"])) {
    $validator = new Validator([
        "required"  => ":attribute harus diisi",
        "email"     => ":email bukan email yang valid",
        "min"       => ":attribute minimal berisi :min karakter/digit",
        "max"       => ":attribute maximal berisi :max karakter/digit",
    ]);

    $validation = $validator->make($_POST, [
        "nama"     => "required|min:6",
        "email"    => "required|email",
        "nomor_hp" => "required|numeric|min:8|max:12",
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $id_admin_to_update = $_GET["id_admin"];
        $nama               = $_POST["nama"];
        $email              = $_POST["email"];
        $nomor_hp           = $_POST["nomor_hp"];
        $status             = isset($_POST["status"]) ? 1 : 0;

        // Check if email is exist
        $query = htmlspecialchars("SELECT email FROM admin WHERE email = '$email' AND id_admin != $id_admin_to_update");
        $result = $connection->query($query);
        if ($result && $result->num_rows > 0) {
            array_push($errors, "Email $email sudah ada");
        } else {
            $query = htmlspecialchars("UPDATE admin SET nama = '$nama', email = '$email', no_telp = '$nomor_hp', aktif = $status WHERE id_admin = $id_admin_to_update");
            if ($connection->query($query) == TRUE) {
                $connection->close();
                redirect("./index.php");
            } else {
                print_r($connection->error_list);
                $connection->close();
            }
        }
    }
}

// It's GET mode
else {
    $id_admin_to_update = $_GET["id_admin"];
    $query = "SELECT id_admin FROM admin WHERE id_admin = $id_admin_to_update";
    $result = $connection->query($query);
    if ($result && $result->num_rows < 1) {
        redirect('./index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASE_PATH ?>/css/tailwind.min.css" rel="stylesheet">
    <link href="<?= BASE_PATH ?>/css/main.css" rel="stylesheet">
    <title>Tambah Admin - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 overflow-hidden text-sm">
    <?php require_once "../../header.php"; ?>

    <main class="main lg:ml-64">
        <h3 class="text-2xl font-bold py-2 page-header">Update Admin</h3>

        <form action="update.php?id_admin=<?= $_GET['id_admin']; ?>" class="bg-white my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php
                    foreach ($errors as $error) {
                        echo "<div>" .  $error . "</div>";
                    }
                    ?>
                </div>
            <?php } ?>

            <?php
            if (!isset($_POST["update_admin"])) {
                $query = "SELECT * FROM admin WHERE id_admin = " . $_GET['id_admin'];
                $result = $connection->query($query);
                while ($row = $result->fetch_row()) {
                    $data["nama"]       = $row[1];
                    $data["email"]      = $row[2];
                    $data["no_telp"]    = $row[3];
                    $data["aktif"]      = $row[5];
                    $data["tipe_admin"] = $row[6];
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

            <span class="block">Status</span>
            <input class="bg-gray-200 inline-block px-3 py-2 ml-2" <?= !$errors && $data['aktif'] == 1 ? "checked" : "" ?> id="status" name="status" type="checkbox">
            <label for="status">Aktif</label>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <input class="bg-gray-500 block px-3 py-2 mx-1 my-2 text-white rounded-md" type="reset" />
                <input class="bg-blue-500 block px-3 py-2 mx-1 my-2 text-white rounded-md" role="button" name="update_admin" type="submit" value="Update" />
            </div>
        </form>
    </main>
    <?php require_once "../../scripts.php" ?>
</body>

</html>
