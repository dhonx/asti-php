<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

// Redirect to refferer url if id_pegawai is not valid
if (!isset($_GET["id_pegawai"]) && !is_numeric($_GET["id_pegawai"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_pegawai_to_update = $_GET["id_pegawai"];

$q_get_pegawai = "SELECT * FROM `pegawai` WHERE `id_pegawai` = $id_pegawai_to_update";
$r_get_pegawai = $connection->query($q_get_pegawai);
if ($r_get_pegawai && $r_get_pegawai->num_rows == 0) {
    redirect('./');
}

if (isset($_POST["update_pegawai"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "no_pegawai" => "required|min:8",
        "nama"       => "required|min:6",
        "email"      => "required|email",
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $no_pegawai = $connection->real_escape_string(clean($_POST["no_pegawai"]));
        $nama       = $connection->real_escape_string(clean($_POST["nama"]));
        $email      = $connection->real_escape_string(clean($_POST["email"]));

        // Check if no pegawai is exist
        $q_check_no_pegawai =  "SELECT
                                    `no_pegawai`
                                FROM
                                    `pegawai`
                                WHERE
                                    `no_pegawai` = '$no_pegawai'
                                AND
                                    `id_pegawai` != $id_pegawai_to_update";
        $r_check_no_pegawai = $connection->query($q_check_no_pegawai);

        // Check if email is exist
        $q_check_email =    "SELECT
                                `email`
                            FROM
                                `pegawai`
                            WHERE
                                `email` = '$email'
                            AND
                                `id_pegawai` != $id_pegawai_to_update";
        $r_check_email = $connection->query($q_check_email);

        if ($r_check_no_pegawai && $r_check_no_pegawai->num_rows != 0) {
            array_push($errors, "No pegawai $no_pegawai sudah ada");
        } else if ($r_check_email && $r_check_email->num_rows != 0) {
            array_push($errors, "Email $email sudah ada");
        } else {
            $q_update = "UPDATE
                            `pegawai`
                        SET
                            `no_pegawai` = $no_pegawai,
                            `nama` = '$nama',
                            `email` = '$email'
                        WHERE 
                            `id_pegawai` = $id_pegawai_to_update";
            if ($connection->query($q_update)) {
                redirect("./");
            }
        }
    }
}

while ($row = $r_get_pegawai->fetch_assoc()) {
    $data["no_pegawai"] = $row["no_pegawai"];
    $data["nama"]       = $row["nama"];
    $data["email"]      = $row["email"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Ubah Pegawai <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Ubah Pegawai <?= $data["nama"] ?></h3>

        <form action="?id_pegawai=<?= $id_pegawai_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="no_pegawai">No Pegawai <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="no_pegawai" minlength="5" name="no_pegawai" required type="number" value="<?= $errors ? get_prev_field('no_pegawai') : $data['no_pegawai'] ?>">

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('nama') : $data['nama'] ?>">

            <label class="block" for="email">Email <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="5" name="email" required spellcheck="false" type="email" value="<?= $errors ? get_prev_field('email') : $data['email'] ?>">

            <a class="block my-2" href="change_password?id_pegawai=<?= $id_pegawai_to_update ?>">Ganti sandi</a>

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