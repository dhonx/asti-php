<?php
require_once "../../utils.php";
require_once "../../connection/connection.php";
require_once "../../config.php";
require_once "../../vendor/autoload.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_pegawai"]) && !is_numeric($_GET["id_pegawai"])) {
    redirect("./");
}

$id_pegawai = $_GET["id_pegawai"];
$q_check_id_pegawai = "SELECT `id_pegawai` FROM `pegawai` WHERE `id_pegawai` = $id_pegawai";
$r_check_id_pegawai = $connection->query($q_check_id_pegawai);
if ($r_check_id_pegawai && $r_check_id_pegawai->num_rows < 1) {
    redirect('./');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title>View Pegawai - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/loading.php" ?>
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="text-2xl font-bold py-2 page-header">View Pegawai Data</h3>

        <?php
        $q_get_pegawai = "SELECT * FROM `pegawai` WHERE `id_pegawai` = $id_pegawai";
        $r_get_pegawai = $connection->query($q_get_pegawai);
        while ($row = $r_get_pegawai->fetch_assoc()) {
            $data["id_pegawai"] = $row["id_pegawai"];
            $data["no_pegawai"] = $row["no_pegawai"];
            $data["nama"]       = $row["nama"];
            $data["email"]      = $row["email"];
            $data["created_at"] = $row["created_at"];
            $data["updated_at"] = $row["updated_at"];
        }
        ?>

        <div class="bg-white my-2 p-2 rounded-md">
            <div>
                <span class="font-bold">No Pegawai:</span>
                <span><?= $data["no_pegawai"] ?></span>
            </div>
            <div>
                <span class="font-bold">Nama:</span>
                <span><?= $data["nama"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Email:</span>
                <a href="mailto:<?= $data["email"] ?>"><?= $data["email"] ?></a>
            </div>
            <div class="mt-2">
                <span class="font-bold">Tanggal dibuat:</span>
                <span><?= $data["created_at"] ?></span>
            </div>
            <div class="mt-2">
                <span class="font-bold">Tanggal terakhir diupdate:</span>
                <span><?= $data["updated_at"] ?></span>
            </div>
            <div class="border border-b mt-2"></div>
            <div class="flex">
                <a class="active-scale bg-blue-900 block py-2 px-3 mx-1 my-2 rounded-md text-white" href="update?id_pegawai=<?= $data["id_pegawai"] ?>" title="Ubah data ini">
                    <span class="mdi align-middle mdi-pencil"></span>
                    Ubah
                </a>
                <a data-nama="<?= $data["nama"] ?>" class="active-scale bg-red-500 delete-link block py-2 px-3 mx-1 my-2 rounded-md text-white" href="delete?id_pegawai=<?= $data["id_pegawai"] ?>" title="Hapus data ini">
                    <span class="mdi align-middle mdi-trash-can"></span>
                    Hapus
                </a>
            </div>
        </div>
    </main>
    <?php require_once "../../includes/scripts.php" ?>
</body>

</html>
