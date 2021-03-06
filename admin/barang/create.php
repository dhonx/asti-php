<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

if (isset($_POST["create_barang"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "kode_inventaris" => "required",
        "id_komponen"     => "required|numeric",
        "id_perolehan"    => "required|numeric",
        // "jumlah"          => "required|numeric",
        // "harga_beli"      => "required|numeric",
        "kondisi"         => ["required", $validator("in", ["baik", "rusak ringan", "rusak berat"])],
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $kode_inventaris = $connection->real_escape_string(clean($_POST["kode_inventaris"]));
        $id_komponen     = $_POST["id_komponen"];
        $id_perolehan    = $_POST["id_perolehan"];
        $kondisi         = $connection->real_escape_string($_POST["kondisi"]);
        $status          = isset($_POST["status"]) ? 1 : 0;
        $keterangan      = $connection->real_escape_string(clean($_POST["keterangan"]));

        $q_check_kode_inventaris = "SELECT `kode_inventaris` FROM `barang` WHERE `kode_inventaris` = '$kode_inventaris'";
        $r_check_kode_inventaris = $connection->query($q_check_kode_inventaris);

        $q_check_perolehan = "SELECT `id_perolehan` FROM `perolehan` WHERE `id_perolehan` = $id_perolehan";
        $r_check_perolehan = $connection->query($q_check_perolehan);

        if ($r_check_kode_inventaris && $r_check_kode_inventaris->num_rows != 0) {
            array_push($errors, "Kode inventaris $kode_inventaris sudah ada");
        } else if ($r_check_perolehan && $r_check_perolehan->num_rows == 0) {
            array_push($errors, "Id perolehan tidak valid");
        } else {
            $admin_email = $_SESSION["email"];
            $q_get_id_admin = "SELECT `id_admin` FROM `admin` WHERE `email` = '$admin_email'";
            $r_get_id_admin = $connection->query($q_get_id_admin);
            $first_row = $r_get_id_admin->fetch_assoc();
            $id_admin = $first_row["id_admin"];

            $q_insert = "INSERT INTO `barang`
                        (`kode_inventaris`, `id_komponen`, `id_perolehan`, `kondisi`, `aktif`, `keterangan`, `id_admin`) 
                    VALUES
                        ('$kode_inventaris', $id_komponen, $id_perolehan, '$kondisi', $status, '$keterangan', $id_admin)";

            if ($connection->query($q_insert)) {
                redirect("./");
            } else {
                print_r($connection->error_list);
                die();
            }
        }
    }
}

$q_get_komponen = "SELECT `id_komponen`, `nama` FROM `komponen`";
$r_get_komponen = $connection->query($q_get_komponen);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <script defer src="https://unpkg.com/axios@0.16.1/dist/axios.min.js"></script>
    <title>Tambah Barang - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Barang</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">
            <?php if ($errors) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="kode_inventaris">Kode Inventaris <span class="text-red-500" title="Harus diisi">*</span></label>
            <input autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="kode_inventaris" name="kode_inventaris" required spellcheck="false" type="text" value="<?= $errors ? get_prev_field("kode_inventaris") : "" ?>">

            <label class="block" for="id_komponen">Komponen <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_komponen" name="id_komponen">
                <?php $prev_value = $errors ? get_prev_field("id_komponen") : "" ?>
                <?php while ($row = $r_get_komponen->fetch_assoc()) {
                    $v_id_komponen = $row["id_komponen"];
                    $v_nama_komponen = $row["nama"];
                ?>
                    <option <?= $prev_value == $v_id_komponen ? "selected" : "" ?> value="<?= $v_id_komponen ?>">
                        <?= $v_nama_komponen ?>
                    </option>
                <?php } ?>
            </select>

            <div class="mt-1 mb-2">
                <a href="../komponen/create">Tambahkan komponen baru jika tidak ada</a>
            </div>

            <label class="block" for="id_perolehan">Perolehan <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_perolehan" name="id_perolehan"></select>

            <div class="mt-1 mb-2">
                <a href="../perolehan/create">Tambahkan perolehan baru jika tidak ada</a>
            </div>

            <!-- <label class="block" for="harga_beli">Harga Beli <span class="text-red-500" title="Harus diisi">*</span></label> -->
            <!-- <input class="bg-gray-200 px-3 py-2 mb-2 rounded-md w-full" id="harga_beli" min="0.00" max="100.000.0000" name="harga_beli" required spellcheck="false" step="0.01" type="number" value="<?= $errors ? get_prev_field("harga_beli") : "" ?>"> -->

            <!-- <label class="block" for="jumlah">Jumlah <span class="text-red-500" title="Harus diisi">*</span></label> -->
            <!-- <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="jumlah" name="jumlah" required type="number" value="<?= $errors ? get_prev_field("jumlah") : 1 ?>"> -->

            <!-- <div>Total</div> -->
            <!-- <output class="bg-gray-200 block px-3 py-2 mb-2 rounded-md w-full" for="harga_beli jumlah" id="total" name="total">0</output> -->

            <label class="block" for="kondisi">Kondisi <span class="text-red-500" title="Harus diisi">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="kondisi" name="kondisi">
                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>
            </select>

            <span class="block">Status</span>
            <?php $status = $errors ? get_prev_field("status") : "" ?>
            <input class="bg-gray-200 inline-block ml-2 px-3 py-2" <?= $status == "on" ? "checked" : "" ?> id="status" name="status" type="checkbox">
            <label class="cursor-pointer inline-block w-11/12" for="status">Aktif</label>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : "-" ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_barang" title="Tambah Barang" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
    <script defer src="<?= build_url("/admin/barang/get_perolehan.js") ?>"></script>
</body>

</html>
