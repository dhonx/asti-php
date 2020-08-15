<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];



if (isset($_POST["create_peminjaman"])) {
    $valid_kondisi_values = ["baik", "rusak ringan", "rusak berat"];
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "id_peminjam"     => "required|numeric|min:1",
        "id_barang"       => "required|numeric|min:1",
        "jumlah_pinjam"   => "required|numeric|min:1",
        "kondisi_pinjam"  => ["required", $validator("in", $valid_kondisi_values)],
        "tanggal_mulai"   => "required|date:Y-m-d",
        "tanggal_selesai" => "required|date:Y-m-d",
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $id_peminjam     = $_POST["id_peminjam"];
        $id_barang       = $_POST["id_barang"];
        $jumlah_pinjam   = $_POST["jumlah_pinjam"];
        $kondisi_pinjam  = $_POST["kondisi_pinjam"];
        $tanggal_mulai   = $_POST["tanggal_mulai"];
        $tanggal_selesai = $_POST["tanggal_selesai"];
        $keterangan      = $connection->query($_POST["keterangan_peminjaman"]);

        // Check if id_peminjam is valid
        $q_check_peminjam = "SELECT `id_peminjam` FROM `peminjam` WHERE `id_peminjam` = $id_peminjam";
        $r_check_peminjam = $connection->query($q_check_peminjam);

        // Check if id_barang is valid
        $q_check_barang = "SELECT `id_barang` FROM `barang` WHERE `id_barang` = $id_barang";
        $r_check_barang = $connection->query($q_check_barang);

        if ($r_check_peminjam && $r_check_peminjam->num_rows == 0) {
            array_push($errors, "Peminjam tidak valid");
        } else if ($r_check_barang && $r_check_barang->num_rows == 0) {
            array_push($errors, "Barang tidak valid");
        } else {
            // Get current admin id
            $id_admin = get_current_id_admin();

            $q_insert_peminjaman = "INSERT INTO `peminjaman` (`id_peminjam`, `tanggal_mulai`, `tanggal_selesai`, `keterangan_peminjaman`, `id_admin`)
                                    VALUES ($id_peminjam, '$tanggal_mulai', '$tanggal_selesai', '$keterangan', $id_admin)";
            $r_insert_peminjaman = $connection->query($q_insert_peminjaman);

            if ($r_insert_peminjaman) {
                $last_insert_id = $connection->insert_id;
                $q_insert_detail_peminjaman = "INSERT INTO `detail_peminjaman` (`id_peminjaman`, `id_barang`, `jumlah_pinjam`, `kondisi_pinjam`)
                                                VALUES ($last_insert_id, $id_barang, $jumlah_pinjam, '$kondisi_pinjam')";
                $r_insert_detail_peminjaman = $connection->query($q_insert_detail_peminjaman);

                if ($r_insert_detail_peminjaman) {
                    redirect("./");
                }

                // Insert detail peminjaman fails, uncomment this code for debugging purpose only
                // else {
                //     print_r($connection->error_list);
                //     die();
                // }
            }

            // Insert peminjaman fails, uncomment this code for debugging purpose only
            // else {
            //     print_r($connection->error_list);
            //     die();
            // }
        }
    }
}

$q_get_peminjam = "SELECT
                        `peminjam`.`id_peminjam`,
                        `peminjam`.`nama`,
                        `peminjam`.`jabatan`,
                        `instansi`.`nama` AS `nama_instansi`
                    FROM
                        `peminjam`
                    INNER JOIN `instansi`
                        ON `peminjam`.`id_instansi` = `instansi`.`id_instansi`";
$r_get_peminjam = $connection->query($q_get_peminjam);

$q_get_barang = "SELECT
                    `barang`.`id_barang`,
                    `komponen`.`nama` AS `nama_komponen`,
                    `barang`.`kode_inventaris`
                FROM
                    `barang`
                INNER JOIN `perolehan`
                    ON `barang`.`id_perolehan` = `perolehan`.`id_perolehan`
                INNER JOIN `detail_perolehan`
                    ON `perolehan`.`id_perolehan` = `detail_perolehan`.`id_perolehan`
                INNER JOIN `komponen`
                    ON `detail_perolehan`.`id_komponen` = `komponen`.`id_komponen`
                GROUP BY `barang`.`id_barang`";
$r_get_barang = $connection->query($q_get_barang);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Tambah Peminjaman - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Tambah Peminjaman</h3>

        <form class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="id_peminjam">Peminjam <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_peminjam" name="id_peminjam">
                <?php $prev_value = $errors ? get_prev_field("id_peminjam") : "" ?>
                <?php while ($row = $r_get_peminjam->fetch_assoc()) {
                    $v_id_peminjam = $row["id_peminjam"];
                    $v_nama_peminjam = $row["nama"];
                    $v_jabatan_peminjam = $row["jabatan"];
                    $v_nama_instansi = $row["nama_instansi"];
                ?>
                    <option <?= $prev_value == $v_id_peminjam ? "selected" : "" ?> value="<?= $v_id_peminjam ?>">
                        <?= $v_nama_peminjam ?> | <?= $v_jabatan_peminjam ?> dari <?= $v_nama_instansi ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="id_barang">Barang <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_barang" name="id_barang">
                <?php $prev_value = $errors ? get_prev_field("id_barang") : "" ?>
                <?php while ($row = $r_get_barang->fetch_assoc()) {
                    $v_id_barang = $row["id_barang"];
                    $v_nama_komponen = $row["nama_komponen"];
                    $v_kode_inventaris = $row["kode_inventaris"];
                ?>
                    <option <?= $prev_value == $v_id_barang ? "selected" : "" ?> value="<?= $v_id_barang ?>">
                        <?= $v_nama_komponen ?> | <?= $v_kode_inventaris ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="jumlah_pinjam">Jumlah <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="jumlah_pinjam" name="jumlah_pinjam" required type="number" value="<?= $errors ? get_prev_field("jumlah_pinjam") : 1 ?>">

            <label class="block" for="kondisi_pinjam">Kondisi <span class="text-red-500" title="Harus diisi">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="kondisi_pinjam" name="kondisi_pinjam">
                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>
            </select>

            <label class="block" for="tanggal_mulai">Tanggal Mulai <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tanggal_mulai" name="tanggal_mulai" required type="date" value="<?= $errors ? get_prev_field("tanggal_mulai") : "10" ?>">

            <label class="block" for="tanggal_selesai">Tanggal Selesai <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tanggal_selesai" name="tanggal_selesai" required type="date" value="<?= $errors ? get_prev_field("tanggal_selesai") : "10" ?>">

            <label class="block" for="keterangan_peminjaman">Keterangan Peminjaman</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan_peminjaman" name="keterangan_peminjaman" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan_peminjaman") : "-" ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_peminjaman" title="Tambah Peminjaman" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
