<?php
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

$keyword = isset($_GET["keyword"]) && strlen($_GET["keyword"]) >= 1 ? $_GET["keyword"] : "";
$is_search_mode = strlen($keyword) >= 1;

// Main query
$query  = "SELECT * FROM admin WHERE tipe_admin != 'super admin' LIMIT $limit OFFSET $offset";
$query  = "SELECT * FROM ($query) AS admin_ ORDER BY $sort_by $asc";

// If on search mode
if ($is_search_mode) {
    $splited_keyword = explode(" ", $keyword);
    $query  = "SELECT * FROM admin WHERE tipe_admin != 'super admin' AND ";
    $query .= build_search_query($keyword, ["nama", "email", "no_telp"]);
    $query .= " LIMIT $limit OFFSET $offset";
    $query  = "SELECT * FROM ($query) AS admin_ ORDER BY $sort_by $asc";
}

$result = $connection->query($query);

$query          = "SELECT * FROM admin WHERE tipe_admin != 'super admin'";
$count_result   = $connection->query($query);

$total_items    = !$is_search_mode ? $count_result->num_rows : $result->num_rows;
$page_count     = ceil($total_items / $ipp)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASE_PATH ?>/css/tailwind.min.css" rel="stylesheet">
    <link href="<?= BASE_PATH ?>/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css" integrity="sha512-mRuH7OxetZB1XiSaKZ2fxENKgxVvx3ffpzX0FUcaP6GBqAuqOLc8YiC/3EVTUVt5p5mIRT5D9i4LitZUQKWNCg==" crossorigin="anonymous" />
    <title>Manajemen Admin - ASTI</title>
</head>
<body class="font-sans min-h-screen bg-gray-200 overflow-hidden text-sm">
    <?php require_once "../../header.php" ?>

    <main class="main lg:ml-64">
        <h3 class="font-bold page-header py-2 text-2xl">Manajemen Admin</h3>
        <div class="flex my-4 ">
            <a class="bg-blue-500 mr-2 px-3 py-2 rounded-md text-white" href="create.php">Tambah</a>
            <a class="bg-green-500 mr-2 px-3 py-2 rounded-md text-white" href="./">Reset Sort</a>
            <form class="hidden lg:flex ml-auto relative" method="get">
                <input class="px-2 mx-2 rounded-md" type="text" name="keyword" placeholder="Cari..." value="<?= isset($_GET["keyword"]) ? $_GET["keyword"] : "" ?>">
                <i class="mdi self-center absolute" style="right:10px">search</i>
            </form>
        </div>

        <div class="mb-2">
            <table class="border-collapse w-full">
                <thead>
                    <tr class="bg-gray-300 font-bold text-gray-800">
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "nama", "asc"=> $asc == "asc" ? "desc" : "asc"])) ?>
                            <a class="block" href="?<?= $url_query ?>">Nama</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "email", "asc"=> $asc == "asc" ? "desc" : "asc"])) ?>
                            <a class="block" href="?<?= $url_query ?>">Email</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "no_telp", "asc"=> $asc == "asc" ? "desc" : "asc"])) ?>
                            <a class="block" href="?<?= $url_query ?>">No HP</a>
                        </th>
                        <th class="border border-gray-400 hidden lg:table-cell p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "aktif", "asc"=> $asc == "asc" ? "desc" : "asc"])) ?>
                            <a class="block" href="?<?= $url_query ?>">Status</a>
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
                            <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Nama
                                </span>
                                <?= $nama ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Email
                                </span>
                                <?= $email ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    No HP
                                </span>
                                <?= $no_telp ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Status
                                </span>
                                <span class="rounded bg-<?= $aktif == 1 ? 'blue' : 'red' ?>-400 text-white py-1 px-3 text-xs font-bold">
                                    <?= $aktif == 1 ? "aktif" : "tidak aktif" ?>
                                </span>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">
                                <a href="view.php?id_admin=<?= $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600">
                                    <i class="mdi">visibility</i>
                                </a>
                                <a href="update.php?id_admin=<?= $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600">
                                    <i class="mdi">edit</i>
                                </a>
                                <a data-nama="<?= $nama ?>" href="delete.php?id_admin=<?= $id_admin ?>" class="delete-link cursor-pointer text-red-400 text-lg p-1 hover:text-red-600">
                                    <i class="mdi">delete</i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="flex justify-center lg:justify-end my-3">
                <form class="flex" method="get">
                    <label class="self-center px-2">Baris per halaman</label>
                    <select class="p-2 rounded-sm" name="ipp" onchange="this.form.submit()">
                        <option <?= $ipp == 5 ? "selected" : "" ?> value="5">5</option>
                        <option <?= $ipp == 10 ? "selected" : "" ?> value="10">10</option>
                        <option <?= $ipp == 15 ? "selected" : "" ?> value="15">15</option>
                    </select>
                </form>
            </div>
            <div class="flex w-full justify-center items-center my-3">
                <?php foreach (range(1, $page_count) as $page_num) { ?>
                    <?php if ($page_count == 1) continue; ?>
                    <?php $url_query = http_build_query(array_merge($_GET, ["page" => $page_num])) ?>
                    <a class="bg-blue-400 m-1 px-2 py-1 text-white rounded-sm <?= $page_num == $page ? "bg-blue-500 text-lg" : "" ?>" href="?<?= $url_query ?>">
                        <?= $page_num ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php require_once "../../scripts.php"; ?>
</body>

</html>
