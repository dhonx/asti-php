<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];
$valid_status = ["usulan", "diterima", "dalam proses pemesanan", "ditunda", "ditolak"];

// Redirect to refferer url if id_pemesanan is not valid
if (!isset($_GET["id_pemesanan"]) && !is_numeric($_GET["id_pemesanan"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_pemesanan_to_update = $_GET["id_pemesanan"];
$q_get_pemesanan = "SELECT
                `pemesanan`.`id_pemesanan`,
                `pemesanan`.`tanggal_pesan`,
                `pemesanan`.`status`,
                `pemesanan`.`tanggal_pesan`,
                `pemesanan`.`keterangan`,
                `pemesanan`.`id_pegawai`,
                `pegawai`.`nama` AS nama_pegawai,
                `komponen`.`nama` AS nama_komponen,
                `detail_pemesanan`.`id_komponen`,
                `detail_pemesanan`.`jumlah`
            FROM
                `detail_pemesanan`
            INNER JOIN `pemesanan`
                ON `detail_pemesanan`.`id_pemesanan` = `pemesanan`.`id_pemesanan`
            INNER JOIN `pegawai`
                ON `pemesanan`.`id_pegawai` = `pegawai`.`id_pegawai`
            INNER JOIN `komponen`
                ON `detail_pemesanan`.`id_komponen` = `komponen`.`id_komponen`
            WHERE
                `pemesanan`.`id_pemesanan` = $id_pemesanan_to_update";

$r_get_pemesanan = $connection->query($q_get_pemesanan);
if ($r_get_pemesanan && $r_get_pemesanan->num_rows == 0) {
    redirect('./');
}

if (isset($_POST["update_pemesanan"])) {
    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($_POST, [
        "id_pegawai"    => "required|numeric|min:1",
        "id_komponen"   => "required|numeric|min:1",
        "jumlah"        => "required|numeric|min:1",
        "tanggal_pesan" => "required|date:Y-m-d",
        "status"        => ["required", $validator("in", $valid_status)],
    ]);
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors()->firstOfAll();
    } else {
        $tanggal_pesan = convert_date($_POST["tanggal_pesan"]);
        $id_pegawai    = $_POST["id_pegawai"];
        $status        = $_POST["status"];
        $keterangan    = $connection->real_escape_string(clean($_POST["keterangan"]));
        $id_komponen   = $_POST["id_komponen"];
        $jumlah        = $_POST["jumlah"];

        // Check is pegawai is valid
        $q_check_pegawai = "SELECT `id_pegawai` FROM `pegawai` WHERE `id_pegawai` = $id_pegawai";
        $r_check_pegawai = $connection->query($q_check_pegawai);

        // Check is komponen is valid
        $q_check_komponen = "SELECT `id_komponen` FROM `komponen` WHERE `id_komponen` = $id_komponen";
        $r_check_komponen = $connection->query($q_check_komponen);

        if ($r_check_pegawai && $r_check_pegawai->num_rows == 0) {
            array_push($errors, "Pegawai tidak valid");
        } else if ($r_check_komponen && $r_check_komponen->num_rows == 0) {
            array_push($errors, "Komponen tidak valid");
        } else {
            $q_update_pemesanan = "UPDATE 
                                        `pemesanan`
                                    SET
                                        `tanggal_pesan` = '$tanggal_pesan',
                                        `id_pegawai` = $id_pegawai,
                                        `status` = '$status',
                                        `keterangan` = '$keterangan'
                                    WHERE
                                        `id_pemesanan` = $id_pemesanan_to_update";
            if ($connection->query($q_update_pemesanan)) {
                $q_update_detail_pemesanan =    "UPDATE
                                                    `detail_pemesanan`
                                                SET
                                                    `id_komponen` = $id_komponen,
                                                    `jumlah` = $jumlah
                                                WHERE `id_pemesanan` = $id_pemesanan_to_update
                                                ";
                if ($connection->query($q_update_detail_pemesanan)) {
                    redirect("./");
                }

                // Update detail pemesanan failed
                else {
                    // 
                }
            }

            // Update pemesanan failed
            else {
                // 
            }
        }
    }
}

while ($row = $r_get_pemesanan->fetch_assoc()) {
    $data["id_pegawai"]    = $row["id_pegawai"];
    $data["id_komponen"]   = $row["id_komponen"];
    $data["nama_pegawai"]  = $row["nama_pegawai"];
    $data["nama_komponen"] = $row["nama_komponen"];
    $data["jumlah"]        = $row["jumlah"];
    $data["tanggal_pesan"] = $row["tanggal_pesan"];
    $data["status"]        = $row["status"];
    $data["keterangan"]    = $row["keterangan"];
}

$q_get_pegawai = "SELECT `id_pegawai`, `no_pegawai`, `nama` FROM `pegawai`";
$r_get_pegawai = $connection->query($q_get_pegawai);

$q_get_komponen = "SELECT `id_komponen`, `nama` FROM `komponen`";
$r_get_komponen = $connection->query($q_get_komponen);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>Ubah Pemesanan Barang <?= $data["nama_komponen"] ?> oleh <?= $data["nama_pegawai"] ?> - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php"; ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">Ubah Pemesanan Barang <?= $data["nama_komponen"] ?> oleh <?= $data["nama_pegawai"] ?></h3>

        <form action="?id_pemesanan=<?= $id_pemesanan_to_update ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 rounded-md text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="id_pegawai">Pegawai <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_pegawai" name="id_pegawai">
                <?php $prev_value = $errors ? get_prev_field("id_pegawai") : $data["id_pegawai"] ?>
                <?php while ($row = $r_get_pegawai->fetch_assoc()) {
                    $v_id_pegawai = $row["id_pegawai"];
                    $v_no_pegawai = $row["no_pegawai"];
                    $v_nama_pegawai = $row["nama"];
                ?>
                    <option <?= $prev_value == $v_id_pegawai ? "selected" : "" ?> value="<?= $v_id_pegawai ?>">
                        <?= $v_nama_pegawai ?> | <?= $v_no_pegawai ?>
                    </option>
                <?php } ?>
            </select>

            <label class="block" for="id_komponen">Komponen <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="id_komponen" name="id_komponen">
                <?php $prev_value = $errors ? get_prev_field("id_komponen") : $data["id_komponen"] ?>
                <?php while ($row = $r_get_komponen->fetch_assoc()) {
                    $v_id_komponen = $row["id_komponen"];
                    $v_nama_komponen = $row["nama"];
                ?>
                    <option data-price="<" <?= $prev_value == $v_id_komponen ? "selected" : "" ?> value="<?= $v_id_komponen ?>">
                        <?= $v_nama_komponen ?>
                    </option>
                <?php } ?>
            </select>

            <div class="mt-1 mb-2">
                <a href="../manajemen_komponen/create">Tambahkan komponen baru jika tidak ada</a>
            </div>

            <label class="block" for="jumlah">Jumlah <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="jumlah" min="1" minlength="1" name="jumlah" required type="number" value="<?= $errors ? get_prev_field("jumlah") : $data["jumlah"] ?>">

            <label class="block" for="tanggal_pesan">Tanggal Pesan <span class="text-red-500" title="Harus diisi">*</span></label>
            <input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="tanggal_pesan" name="tanggal_pesan" required type="date" value="<?= $errors ? get_prev_field("tanggal_pesan") : $data["tanggal_pesan"] ?>">

            <label class="block" for="status">Status <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="status" name="status">
                <?php $prev_value = $errors ? get_prev_field("status") : $data["status"] ?>
                <option <?= $prev_value == "usulan" ? "selected" : "" ?> value="usulan">Usulan</option>
                <option <?= $prev_value == "ditolak" ? "selected" : "" ?> value="ditolak">Ditolak</option>
                <option <?= $prev_value == "ditunda" ? "selected" : "" ?> value="ditunda">Ditunda</option>
                <option <?= $prev_value == "diterima" ? "selected" : "" ?> value="diterima">Diterima</option>
                <option <?= $prev_value == "dalam proses pemesanan" ? "selected" : "" ?> value="dalam proses pemesanan">Dalam proses pemesanan</option>
            </select>

            <label class="block" for="keterangan">Keterangan</label>
            <textarea class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="keterangan" name="keterangan" style="min-height: 200px;"><?= $errors ? get_prev_field("keterangan") : $data["keterangan"] ?></textarea>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 block px-3 py-2 mx-1 my-2 rounded-md" title="Reset Formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 block px-3 py-2 mx-1 my-2 text-white rounded-md" name="update_pemesanan" title="Simpan/Update" type="submit">
                    <span class="mdi align-middle mdi-content-save"></span>
                    Simpan
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
