<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];
$valid_status_values = ["pembelian", "bantuan", "penyesuaian stok"];

if (isset($_POST["create_perolehan"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "id_pemasok"    => "required|numeric",
        "id_komponen"   => "required|numeric",
        "tanggal"       => "required|date:Y-m-d",
        "status"        => ["required", $validator("in", $valid_status_values)],
        "harga_beli"    => "required|numeric",
        "jumlah"        => "required|numeric"
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $id_pemasok  = $_POST["id_pemasok"];
        $id_komponen = $_POST["id_komponen"];

        $q_check_pemasok = "SELECT `id_pemasok` FROM `pemasok` WHERE `id_pemasok` = $id_pemasok";
        $r_check_pemasok = $connection->query($q_check_pemasok);

        $q_check_komponen = "SELECT `id_komponen` FROM `komponen` WHERE `id_komponen` = $id_komponen";
        $r_check_komponen = $connection->query($q_check_komponen);

        if ($r_check_pemasok && $r_check_pemasok->num_rows == 0) {
            array_push($errors, "Pemasok tidak valid");
        } else if ($q_check_komponen && $r_check_komponen->num_rows == 0) {
            array_push($errors, "Komponen tidak valid");
        } else {
            $tanggal    = convert_date($_POST["tanggal"]);
            $status     = $_POST["status"];
            $harga_beli = $_POST["harga_beli"];
            $jumlah     = $_POST["jumlah"];
            $keterangan = $connection->real_escape_string(clean($_POST["keterangan"]));

            $q_insert_perolehan = "INSERT INTO `perolehan`
                                    (`id_pemasok`, `tanggal`, `status`, `keterangan`)
                                    VALUES
                                    ($id_pemasok, '$tanggal', '$status', '$keterangan')";
            $r_insert_perolehan = $connection->query($q_insert_perolehan);

            if ($r_insert_perolehan) {
                $last_insert_id = $connection->insert_id;
                $q_insert_detail_perolehan =    "INSERT INTO `detail_perolehan`
                                                    (`id_perolehan`, `id_komponen`, `harga_beli`, `jumlah`)
                                                VALUES
                                                    ($last_insert_id, $id_komponen, $harga_beli, $jumlah)";
                $r_insert_detail_perolehan = $connection->query($q_insert_detail_perolehan);

                if ($r_insert_detail_perolehan) {
                    $current_id_admin = get_current_id_admin();
                    foreach (range(1, $jumlah) as $number) {
                        $hash_kode_inventaris = sha1($id_pemasok . $tanggal . $number);
                        $q_insert_to_barang =   "INSERT INTO `barang`
                                                    (`kode_inventaris`, `id_perolehan`, `keterangan`, `id_admin`)
                                                VALUES
                                                    ('$hash_kode_inventaris', $last_insert_id, '$keterangan', $current_id_admin)";
                        $r_insert_to_barang = $connection->query($q_insert_to_barang);

                        if ($r_insert_to_barang) {
                            if ($number == $jumlah) {
                                redirect("./");
                            }
                        }

                        // Insert to barang failed, uncomment this for debugging purpose only
                        // else {
                        //     print_r($connection->error_list);
                        //     die();
                        // }
                    }
                }

                // Insert detail_perolehan failed, uncomment this for debugging purpose only
                // else {
                //     print_r($connection->error_list);
                //     die();
                // }
            }

            // Insert perolehan failed, uncomment this for debugging purpose only
            // else {
            //     print_r($connection->error_list);
            //     die();
            // }
        }
    }
}

$q_get_komponen = "SELECT `id_komponen`, `nama` FROM `komponen`";
$r_get_komponen = $connection->query($q_get_komponen);

$q_get_pemasok = "SELECT `id_pemasok`, `nama` FROM `pemasok`";
$r_get_pemasok = $connection->query($q_get_pemasok);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Tambah Perolehan - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Perolehan</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">
            <?php if ($errors) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="id_komponen">Komponen <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_komponen" name="id_komponen">
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

            <label class="block" for="id_pemasok">Pemasok <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_pemasok" name="id_pemasok">
                <?php $prev_value = $errors ? get_prev_field("id_pemasok") : "" ?>
                <?php while ($row = $r_get_pemasok->fetch_assoc()) {
                    $v_id_pemasok = $row["id_pemasok"];
                    $v_nama_pemasok = $row["nama"];
                ?>
                    <option <?= $prev_value == $v_id_pemasok ? "selected" : "" ?> value="<?= $v_id_pemasok ?>">
                        <?= $v_nama_pemasok ?>
                    </option>
                <?php } ?>
            </select>

            <div class="mt-1 mb-2">
                <a href="../pemasok/create">Tambahkan pemasok baru jika tidak ada</a>
            </div>

            <label class="block" for="harga_beli">Harga <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 px-3 py-2 mb-2 rounded-md w-full" id="harga_beli" min="0.00" max="100.000.0000" name="harga_beli" required spellcheck="false" step="0.01" type="number" value="<?= $errors ? get_prev_field("harga_beli") : "" ?>">

            <label class="block" for="jumlah">Jumlah <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="jumlah" name="jumlah" required type="number" value="<?= $errors ? get_prev_field("jumlah") : "" ?>">

            <div>Total</div>
            <output class="bg-gray-200 block px-3 py-2 mb-2 rounded-md w-full" for="harga_beli jumlah" id="total" name="total">0</output>

            <label class="block" for="tanggal">Tanggal Pesan <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tanggal" name="tanggal" required type="date" value="<?= $errors ? get_prev_field("tanggal") : "10" ?>">

            <label class="block" for="status">Status <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="status" name="status">
                <?php $prev_value = $errors ? get_prev_field("status") : "" ?>
                <?php foreach ($valid_status_values as $status_value) { ?>
                    <option <?= $prev_value == $status_value ? "selected" : "" ?> value="<?= $status_value ?>"><?= ucwords($status_value) ?></option>
                <?php } ?>
            </select>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : "-" ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_perolehan" title="Tambah Perolehan" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
    <?php require_once "script.php" ?>
</body>

</html>
