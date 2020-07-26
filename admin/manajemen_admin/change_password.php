<?php
include_once "../../utils.php";
include_once "../../connection/connection.php";
include_once "../../config.php";
require('../../vendor/autoload.php');

use Rakit\Validation\Validator;

authenticate(["super_admin"]);

$errors = [];

// Redirect to refferer url if id_admin is not valid
if (!isset($_GET["id_admin"]) && !is_numeric($_GET["id_admin"])) {
    redirect($_SERVER["HTTP_REFERER"]);
}

$id_admin_to_update = $_GET["id_admin"];

if (isset($_POST["update_admin_password"])) {
    $validator = new Validator(VALIDATION_MESSAGES);

    $validation = $validator->make($_POST, [
        "sandi_lama"            => "required|min:8",
        "sandi_baru"            => "required|min:8",
        "konfirmasi_sandi_baru" => "required|min:8|same:sandi_baru"
    ]);

    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    }

    // Validation passed
    else {
        $sandi_lama             = $_POST["sandi_lama"];
        $sandi_baru             = $_POST["sandi_baru"];
        $konfirmasi_sandi_baru  = $_POST["konfirmasi_sandi_baru"];

        // Get admin password from db
        $result = $connection->query("SELECT sandi FROM admin WHERE id_admin = $id_admin_to_update");
        if ($result->num_rows == 1) {
            $result_array   = $result->fetch_array(1);
            $sandi_from_db  = $result_array["sandi"];

            // Check if sandi lama is valid
            if (password_verify($sandi_lama, $sandi_from_db)) {
                $new_password_hash = password_hash($sandi_lama, PASSWORD_BCRYPT);
                echo $new_password_hash;
                $q_update_password = htmlspecialchars("UPDATE admin SET sandi = '$new_password_hash' WHERE id_admin = $id_admin_to_update");
                if ($connection->query($q_update_password) == TRUE) {
                    $connection->close();
                    redirect("./");
                }
            }

            // Sandi lama is invalid
            else {
                array_push($errors, "Sandi salah");
            }
        }

        // Cannot find data with specified id
        else {
            redirect($_SERVER["HTTP_REFERER"]);
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
    <title>Ubah Password Admin - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Ubah Sandi Admin</h3>

        <form action="?id_admin=<?php echo $id_admin_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) {
                        echo "<div>" .  $error . "</div>";
                    }
                    ?>
                </div>
            <?php } ?>

            <label class="block" for="sandi_lama">Sandi Lama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 mb-2 px-3 py-2 rounded-md w-full" id="sandi_lama" minlength="5" name="sandi_lama" required type="password" value="<? $errors && get_prev_field('sandi_lama') ?>">

            <label class="block" for="sandi_baru">Sandi Baru <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 mb-2 px-3 py-2 rounded-md w-full" id="sandi_baru" minlength="5" name="sandi_baru" required type="password" value="<? $errors && get_prev_field('sandi_baru') ?>">

            <label class="block" for="konfirmasi_sandi_baru">Konfirmasi Sandi Baru <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 mb-2 px-3 py-2 rounded-md w-full" id="konfirmasi_sandi_baru" minlength="5" name="konfirmasi_sandi_baru" required type="password" value="<? $errors && get_prev_field('konfirmasi_sandi_baru') ?>">

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_admin_password" title="Simpan/Update Sandi" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Ubah Sandi
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
