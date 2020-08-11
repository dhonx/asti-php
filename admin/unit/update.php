<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

// Redirect to refferer url if id_unit is not valid
if (!isset($_GET["id_unit"]) && !is_numeric($_GET["id_unit"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_unit_to_update = $_GET["id_unit"];

$q_get_unit = "SELECT * FROM `unit` WHERE `id_unit` = $id_unit_to_update";
$r_get_unit = $connection->query($q_get_unit);
if ($r_get_unit && $r_get_unit->num_rows == 0) {
    redirect('./');
}

if (isset($_POST["update_unit"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "nama"     => "required|min:3",
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $nama       = $connection->real_escape_string(clean($_POST["nama"]));
        $keterangan = $connection->real_escape_string(clean($_POST["keterangan"]));

        $q_update =     "UPDATE
                            `unit`
                        SET
                            `nama` = '$nama',
                            `keterangan` = '$keterangan'
                        WHERE
                            `id_unit` = $id_unit_to_update";
        if ($connection->query($q_update)) {
            redirect("./");
        }
    }
}

while ($row = $r_get_instansi->fetch_assoc()) {
    $data["nama"] = $row["nama"];
    $data["keterangan"] = $row["keterangan"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Ubah Unit <?= $data["nama"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Ubah Unit <?= $data["nama"] ?></h3>

        <form action="?id_unit=<?= $id_unit_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">
            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="nama">Nama <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="nama" minlength="5" name="nama" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field('nama') : $data['nama'] ?>">

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : "-" ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_unit" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
