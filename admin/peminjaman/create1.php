<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
// require_once "../../vendor/autoload.php";

// use Rakit\Validation\Validator;

authenticate(["super_admin", "admin"]);

$errors = [];

$param_barang_lists = isset($_GET["items"]) ? $_GET["items"] : "[]";
$barang_lists = json_decode($param_barang_lists);

if (isset($_POST["add_more"])) {
    $id_barang = $_POST["barang"];

    array_push($barang_lists, (int)$id_barang);
    $param_barang_lists = json_encode($barang_lists);
}

$q_get_komponen = "SELECT `id_komponen`, `nama` FROM `komponen`";
$r_get_komponen = $connection->query($q_get_komponen);

// Uncomment this code for debugging purpose only
// if (!$r_get_komponen) {
//     print_r($connection->error_list);
//     die();
// }
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
        <h3 class="text-2xl font-bold py-2 page-header">Pilih barang</h3>

        <form action="?items=<?= $param_barang_lists ?>" class="my-5 p-5 pb-2 rounded-md" method="post">

            <?php if ($errors != null) { ?>
                <div class="bg-red-400 p-2 mb-2 text-white">
                    <?php foreach ($errors as $error) { ?>
                        <div><?= $error ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <label class="block" for="komponen">Komponen <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select autofocus class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="komponen" name="komponen">
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

            <label class="block" for="barang">Barang <span class="text-red-500" title="Harus dipilih">*</span></label>
            <select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="barang" name="barang"></select>

            <div class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md selected-barang-lists">
            </div>

            <div class="border-b border-solid my-2 w-full"></div>

            <div class="flex justify-end">
                <button class="active-scale bg-gray-300 font-bold block px-3 py-2 mx-1 my-2 rounded-md" title="Reset formulir" type="reset">
                    <span class="mdi align-middle mdi-notification-clear-all"></span>
                    Reset
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="create_peminjaman" title="Tambah Peminjaman" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Proses
                </button>
                <button class="active-scale bg-blue-900 font-bold block px-3 py-2 mx-1 my-2 text-white rounded-md" name="add_more" title="Tambah Peminjaman" type="submit">
                    <span class="mdi align-middle mdi-plus"></span>
                    Tambah lainnya
                </button>
            </div>
        </form>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
    <script defer src="<?= build_url("/admin/peminjaman/get_barang.js") ?>"></script>
</body>

</html>
