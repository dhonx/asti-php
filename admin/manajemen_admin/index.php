<?
include "../../utils.php";
include_once "../../config.php";
include_once "../../connection/connection.php";

authenticate();

$valid_columns  = ["nama", "email", "no_telp", "aktif"];
$valid_ipp      = [5, 10, 15];

$valid_asc  = ["asc", "desc"];
$sort_by    = isset($_GET["sort_by"]) && in_array($_GET["sort_by"], $valid_columns) ? $_GET["sort_by"] : "id_admin";
$asc        = isset($_GET["asc"]) && in_array($_GET["asc"], $valid_asc) ? $_GET["asc"] : "asc";

$ipp    = isset($_GET["ipp"]) && is_numeric($_GET["ipp"]) && in_array($_GET["ipp"], $valid_ipp) ? $_GET["ipp"] : 5;
$page   = isset($_GET["page"]) && is_numeric($_GET["page"]) ? $_GET["page"] : 1;
$offset = $page * $ipp - $ipp;
$limit  = $ipp - 0;

$query  = "SELECT * FROM admin WHERE tipe_admin != 'super admin' LIMIT $limit OFFSET $offset";
$query  = "SELECT * FROM ($query) AS admin_ ORDER BY $sort_by $asc";
$result = $connection->query($query);

$query          = "SELECT * FROM admin WHERE tipe_admin != 'super admin'";
$count_result   = $connection->query($query);

$total_items    = $count_result->num_rows;
$page_count     = ceil($total_items / $ipp)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASE_PATH; ?>/css/tailwind.min.css" rel="stylesheet">
    <link href="<?= BASE_PATH; ?>/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css" integrity="sha512-mRuH7OxetZB1XiSaKZ2fxENKgxVvx3ffpzX0FUcaP6GBqAuqOLc8YiC/3EVTUVt5p5mIRT5D9i4LitZUQKWNCg==" crossorigin="anonymous" />
    <title>Manajemen Admin - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 overflow-hidden text-sm">
    <? require_once "../../header.php"; ?>

    <main class="main lg:ml-64">
        <h3 class="font-bold page-header py-2 text-2xl">Manajemen Admin</h3>

        <div class="flex my-5 justify-end">
            <form class="flex" action="index.php" method="get">
                <select class="px-1 rounded-sm" name="ipp" onchange="this.form.submit()">
                    <option <?= $ipp == 5 ? "selected" : "" ?> value="5">5</option>
                    <option <?= $ipp == 10 ? "selected" : "" ?> value="10">10</option>
                    <option <?= $ipp == 15 ? "selected" : "" ?> value="15">15</option>
                </select>
            </form>
            <a class="bg-green-500 ml-2 px-3 py-2 rounded-md text-white" href="./">Reset Sort</a>
            <a class="bg-blue-500 ml-2 px-3 py-2 rounded-md text-white" href="create.php">Tambah Admin</a>
        </div>

        <div class="mb-2">
            <table class="border-collapse w-full">
                <thead>
                    <tr class="bg-gray-300 font-bold text-gray-800">
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=nama&asc=<?= $asc == 'asc' ? 'desc' : 'asc' ?>&page=<?= $page ?>&ipp=<?= $ipp ?>">Nama</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=email&asc=<?= $asc == 'asc' ? 'desc' : 'asc' ?>&page=<?= $page ?>&ipp=<?= $ipp ?>">Email</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=no_telp&asc=<?= $asc == 'asc' ? 'desc' : 'asc' ?>&page=<?= $page ?>&ipp=<?= $ipp ?>">No HP</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <a class="block" href="index.php?sort_by=aktif&asc=<?= $asc == 'asc' ? 'desc' : 'asc' ?>&page=<?= $page ?>&ipp=<?= $ipp ?>">Status</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <? while ($row = $result->fetch_row()) {
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
                        <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Nama</span>
                            <?= $nama ?>
                        </td>
                        <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">Email</span>
                            <?= $email ?>
                        </td>
                        <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">No HP</span>
                            <?= $no_telp ?>
                        </td>
                        <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">No HP</span>
                            <span class="rounded bg-<?= $aktif == 1 ? 'blue' : 'red' ?>-400 text-white py-1 px-3 text-xs font-bold">
                                <?= $aktif == 1 ? "aktif" : "tidak aktif" ?>
                            </span>
                        </td>
                        <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 text-xs font-bold uppercase">Aksi</span>
                            <a href="view.php?id_admin=<?= $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600"><i class="mdi">visibility</i></a>
                            <a href="update.php?id_admin=<?= $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600"><i class="mdi">edit</i></a>
                            <a data-nama="<?= $nama ?>" href="delete.php?id_admin=<?= $id_admin ?>" class="delete-link cursor-pointer text-red-400 text-lg p-1 hover:text-red-600"><i class="mdi">delete</i></a>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
            <div class="flex w-full justify-center items-center my-2">
                <? foreach (range(1, $page_count) as $page_num) { ?>
                <? if ($page_count == 1) continue; ?>
                <a class="bg-blue-400 m-1 px-2 py-1 text-white rounded-sm <?= $page_num == $page ? " text-lg" : "" ?>" href="index.php?page=<?= $page_num ?>&ipp=<?= $ipp ?>"><?= $page_num ?></a>
                <? } ?>
            </div>
        </div>
    </main>
    <? require_once "../../scripts.php"; ?>
</body>

</html>
