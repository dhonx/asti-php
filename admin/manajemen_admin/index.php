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
    <title>Manajemen Admin - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 overflow-hidden">
    <?php require_once "../../header.php"; ?>

    <main class="main">
        <h3 class="text-2xl font-bold py-2 page-header">Manajemen Admin</h3>

        <div class="flex my-5 justify-end">
            <a class="py-2 px-3 bg-blue-500 text-white rounded-md" href="create.php">Tambah Admin</a>
        </div>

        <div class="mb-2">
            <table class="border-collapse w-full">
                <thead>
                    <tr class="bg-blue-400 font-bold text-white">
                        <th class="border border-gray-300 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=nama&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">Nama</a>
                        </th>
                        <th class="border border-gray-300 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=email&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">Email</a>
                        </th>
                        <th class="border border-gray-300 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=no_telp&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">No HP</a>
                        </th>
                        <th class="border border-gray-300 hidden lg:table-cell p-2">
                             <a class="block" href="index.php?sort_by=aktif&asc=<?php echo $asc == 'asc' ? 'desc' : 'asc' ?>">Status</a>
                        </th>
                        <th class="border border-gray-300 hidden lg:table-cell p-2">Aksi</th>
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
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Aksi</span>
                                <a href="view.php?id_admin=<?php echo $id_admin ?>" class="text-blue-400 hover:text-blue-600 underline">view</a>
                                <a href="update.php?id_admin=<?php echo $id_admin ?>" class="text-blue-400 hover:text-blue-600 underline">edit</a>
                                <a href="delete.php?id_admin=<?php echo $id_admin ?>" class="text-red-400 hover:text-red-600 underline">hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
