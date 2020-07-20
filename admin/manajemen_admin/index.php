<?php
include "../../utils.php";
include_once "../../config.php";
include_once "../../connection/connection.php";

authenticate();

$valid_asc  = ["asc", "desc"];
$sort_by    = isset($_GET["sort_by"]) ? $_GET["sort_by"] : "id_admin";
$asc        = isset($_GET["asc"]) && in_array($_GET["asc"], $valid_asc) ? $_GET["asc"] : "asc";

$query = "SELECT * FROM admin WHERE tipe_admin != 'super admin' ORDER BY $sort_by $asc";
$result = $connection->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASE_PATH; ?>/css/tailwind.min.css" rel="stylesheet">
    <link href="<?php echo BASE_PATH; ?>/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css" integrity="sha512-mRuH7OxetZB1XiSaKZ2fxENKgxVvx3ffpzX0FUcaP6GBqAuqOLc8YiC/3EVTUVt5p5mIRT5D9i4LitZUQKWNCg==" crossorigin="anonymous" />
    <title>Manajemen Admin - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 overflow-hidden text-sm">
    <?php require_once "../../header.php"; ?>

    <main class="main lg:ml-64">
        <h3 class="font-bold page-header py-2 text-2xl">Manajemen Admin</h3>

        <div class="flex my-5 justify-end">
            <a class="bg-green-500 ml-2 px-3 py-2 rounded-md text-white" href="./">Reset Sort</a>
            <a class="bg-blue-500 ml-2 px-3 py-2 rounded-md text-white" href="create.php">Tambah Admin</a>
        </div>

        <div class="mb-2">
            <table class="border-collapse w-full">
                <thead>
                    <tr class="bg-gray-300 font-bold text-gray-800">
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=nama&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">Nama</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=email&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">Email</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=no_telp&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">No HP</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=aktif&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">Status</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_row()) {
                        $id_admin   = $row[0];
                        $nama       = $row[1];
                        $email      = $row[2];
                        $no_telp    = $row[3];
                        $aktif      = $row[5];
                        $tipe_admin = $row[6];
                        $created_at = $row[7];
                        $updated_at = $row[8];
                    ?>
                        <tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
                            <td class="w-full lg:w-auto p-2 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Nama</span>
                                <?php echo $nama ?>
                            </td>
                            <td class="w-full lg:w-auto p-2 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Email</span>
                                <?php echo $email ?>
                            </td>
                            <td class="w-full lg:w-auto p-2 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">No HP</span>
                                <?php echo $no_telp ?>
                            </td>
                            <td class="w-full lg:w-auto p-2 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">No HP</span>
                                <span class="rounded bg-<?php echo $aktif == 1 ? 'blue' : 'red' ?>-400 text-white py-1 px-3 text-xs font-bold"><?php echo $aktif == 1 ? 'aktif' : 'tidak aktif' ?></span>
                            </td>
                            <td class="w-full lg:w-auto p-2 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 text-xs font-bold uppercase">Aksi</span>
                                <a href="view.php?id_admin=<?php echo $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600"><i class="mdi">visibility</i></a>
                                <a href="update.php?id_admin=<?php echo $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600"><i class="mdi">edit</i></a>
                                <a href="delete.php?id_admin=<?php echo $id_admin ?>" class="text-red-400 text-lg p-1 hover:text-red-600"><i class="mdi">delete</i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php require_once "../../scripts.php"; ?>
</body>

</html>
