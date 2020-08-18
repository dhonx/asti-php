<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];


if (isset($_POST["create_peminjaman"])) {
    // print_r($_POST);
    // die();
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "id_komponen"     => "required|numeric|min:1",
        "tanggal_mulai"   => "required|date:Y-m-d",
        "tanggal_selesai" => "required|date:Y-m-d",
        "id_peminjam"     => "required|numeric|min:1",
        "id_barang"       => "required|array"
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $id_komponen        = $_POST["id_komponen"];
        $id_peminjam        = $_POST["id_peminjam"];
        $tanggal_mulai      = $_POST["tanggal_mulai"];
        $tanggal_selesai    = $_POST["tanggal_selesai"];
        $id_barang_list     = $_POST["id_barang"];
        $keterangan         = $connection->real_escape_string(clean($_POST["keterangan"]));
        $tujuan_peminjaman  = $connection->real_escape_string(clean($_POST["tujuan_peminjaman"]));

        $q_check_komponen = "SELECT `id_komponen` FROM `komponen` WHERE `id_komponen` = $id_komponen";
        $r_check_komponen = $connection->query($q_check_komponen);

        $q_check_peminjam = "SELECT `id_peminjam` FROM `peminjam` WHERE `id_peminjam` = $id_peminjam";
        $r_check_peminjam = $connection->query($q_check_peminjam);

        if ($r_check_komponen && $r_check_komponen->num_rows == 0) {
            array_push($errors, "Komponen yang dipilih tidak valid");
        } else if ($r_check_peminjam && $r_check_peminjam->num_rows == 0) {
            array_push($errors, "Peminjam yang dipilih tidak valid");
        } else if (count($id_barang_list) < 1) {
            array_push($errors, "Harus pilih minimal satu barang untuk dipinjam");
        } else {
            $current_id_admin = get_current_id_admin();
            $q_insert_peminjaman =  "INSERT INTO `peminjaman`
                                        (`tanggal_mulai`, `tanggal_selesai`, `id_peminjam`, `tujuan_peminjaman`,`keterangan_peminjaman`, `id_admin`)
                                    VALUES
                                        ('$tanggal_mulai', '$tanggal_selesai', $id_peminjam, '$tujuan_peminjaman', '$keterangan', $current_id_admin)";
            $r_insert_peminjaman = $connection->query($q_insert_peminjaman);
            if ($r_insert_peminjaman) {
                $last_insert_id = $connection->insert_id;
                foreach ($id_barang_list as $id_barang) {
                    $id_barang = (int)$id_barang;
                    $q_check_id_barang = "SELECT `id_barang` FROM `barang` WHERE `id_barang` = $id_barang";
                    $r_check_id_barang = $connection->query($q_check_id_barang);
                    if ($r_check_id_barang && $r_check_id_barang->num_rows == 0) {
                        continue;
                    }
                    $q_insert_detail_peminjaman =   "INSERT INTO `detail_peminjaman`
                                                        (`id_peminjaman`, `id_barang`)
                                                    VALUES
                                                        ($last_insert_id, $id_barang)";
                    $r_insert_detail_peminjaman = $connection->query($q_insert_detail_peminjaman);
                    if ($r_insert_detail_peminjaman) {
                        redirect("./");
                    }

                    // Insert detail peminjaman fails, uncomment this code for debugging purpose only
                    else {
                        print_r($connection->error_list);
                        die();
                    }
                }
            }

            // Insert peminjaman fails, uncomment this code for debugging purpose only
            // else {
            //     print_r($connection->error_list);
            //     die();
            // }
        }
    }
}

$q_get_komponen =   "SELECT
                        `id_komponen`,
                        `nama`
                    FROM
                        `komponen`
                    WHERE
                        `id_komponen` IN (SELECT `detail_perolehan`.`id_komponen` FROM .`detail_perolehan`)";
$r_get_komponen = $connection->query($q_get_komponen);

// Uncomment this code for debugging purpose only
// if (!$r_get_komponen) {
//     print_r($connection->error_list);
//     die();
// }

$q_get_peminjam =   "SELECT
                        `peminjam`.`id_peminjam`,
                        `peminjam`.`nama`,
                        `peminjam`.`jabatan`,
                        `instansi`.`nama` AS `nama_instansi`
                    FROM
                        `peminjam`
                    INNER JOIN `instansi` 
                        ON `peminjam`.`id_instansi` = `instansi`.`id_instansi`";
$r_get_peminjam = $connection->query($q_get_peminjam);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Tambah Peminjaman - ASTI</title>
    <script defer src="https://unpkg.com/axios@0.16.1/dist/axios.min.js"></script>
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

            <label class="block" for="peminjam">Peminjam <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="peminjam" name="id_peminjam" required>
                <?php $prev_value = $errors ? get_prev_field("peminjam") : "" ?>
                <?php while ($row = $r_get_peminjam->fetch_assoc()) {
                    $v_id_peminjam      = $row["id_peminjam"];
                    $v_nama_peminjam    = $row["nama"];
                    $v_jabatan          = $row["jabatan"];
                    $v_nama_instansi    = $row["nama_instansi"];
                ?>
                    <option <?= $prev_value == $v_id_peminjam ? "selected" : "" ?> value="<?= $v_id_peminjam ?>">
                        <?= $v_nama_peminjam ?> | <?= $v_jabatan ?> dari <?= $v_nama_instansi ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="komponen">Komponen <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="komponen" name="id_komponen" required>
                <?php $prev_value = $errors ? get_prev_field("komponen") : "" ?>
                <?php while ($row = $r_get_komponen->fetch_assoc()) {
                    $v_id_komponen = $row["id_komponen"];
                    $v_nama_komponen = $row["nama"];
                ?>
                    <option <?= $prev_value == $v_id_komponen ? "selected" : "" ?> value="<?= $v_id_komponen ?>">
                        <?= $v_nama_komponen ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="barang" id="label-select-barang">Daftar Barang <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 list-box w-full px-3 py-2 mb-1 rounded-md" id="barang" multiple name="id_barang[]" required size="10"></select>

            <label class="block" for="tanggal_mulai">Tanggal Mulai Peminjaman <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tanggal_mulai" name="tanggal_mulai" required type="date" value="<?= $errors ? get_prev_field("tanggal_pesan") : "" ?>">

            <label class="block" for="tanggal_selesai">Tanggal Selesai Peminjaman <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tanggal_selesai" name="tanggal_selesai" required type="date" value="<?= $errors ? get_prev_field("tanggal_pesan") : "" ?>">

            <label class="block" for="tujuan_peminjaman">Tujuan Peminjaman</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tujuan_peminjaman" name="tujuan_peminjaman" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan_peminjaman") : "-" ?></textarea>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan_peminjaman") : "-" ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_peminjaman" title="Tambah Peminjaman" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>Proses
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
    <script defer src="<?= build_url("/admin/peminjaman/get_barang.js") ?>"></script>
</body>

</html>
