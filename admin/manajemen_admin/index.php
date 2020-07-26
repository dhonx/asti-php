<?php
include "../../utils.php";
include "../../config.php";
include "../../connection/connection.php";
require('../../vendor/autoload.php');

use Rakit\Validation\Validator;

authenticate(["super_admin"]);

$valid_columns  = ["id_admin", "nama", "email", "no_telp", "aktif"];
$valid_ipp      = [5, 10, 15];
$valid_asc      = ["asc", "desc"];

$validator = new Validator(VALIDATION_MESSAGES);
$validation = $validator->make($_GET, [
    "sort_by"   => ["default:id_admin", $validator("in", $valid_columns)],
    "asc"       => ["default:asc", $validator("in", $valid_asc)],
    "keyword"   => "default:|min:1",
    "ipp"       => ["default:5", $validator("in", $valid_ipp)],
    "page"      => "default:1"
]);
$validation->validate();

$valid_data = $validation->getValidData();

$sort_by    = $valid_data["sort_by"];
$asc        = $valid_data["asc"];
$keyword    = $valid_data["keyword"];
$ipp        = $valid_data["ipp"];
$page       = $valid_data["page"];

$is_search_mode = strlen($keyword) >= 1;

if ($is_search_mode) {
    $query  = "SELECT * FROM admin WHERE tipe_admin != 'super_admin' AND ";
    $query .= build_search_query($keyword, ["nama", "email", "no_telp"]);
    $count_all_result  = $connection->query("SELECT * FROM ($query) AS admin_ ORDER BY $sort_by $asc");
} else {
    $count_all_result  = $connection->query("SELECT * FROM admin WHERE tipe_admin != 'super_admin'");
}

$total_items = $count_all_result->num_rows;
$page_count  = ceil($total_items / $ipp);

// If the requested page larger than counted page, goto the last page
if ($page > $page_count) {
    $page = $page_count;
}

$offset = $page * $ipp - $ipp;
$limit  = $ipp - 0;

// If on search mode
if ($is_search_mode) {
    $splited_keyword = explode(" ", $keyword);
    // Search query
    $query  = "SELECT * FROM admin WHERE tipe_admin != 'super_admin' AND ";
    $query .= build_search_query($keyword, ["nama", "email", "no_telp"]);
    $query .= " LIMIT $limit OFFSET $offset";
    $query  = "SELECT * FROM ($query) AS admin_ ORDER BY $sort_by $asc";
} else {
    // Main query
    $query  = "SELECT * FROM admin WHERE tipe_admin != 'super_admin' LIMIT $limit OFFSET $offset";
    $query  = "SELECT * FROM ($query) AS admin_ ORDER BY $sort_by $asc";
}

$result = $connection->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title><?= $is_search_mode ? "Hasil pencarian dari $keyword - " : "" ?> Manajemen Admin - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/header.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="font-bold page-header py-2 text-2xl">Manajemen Admin</h3>
        <div class="flex my-4">
            <a class="active-scale bg-blue-900 font-bold mr-2 px-3 py-2 rounded-md text-white" href="create" role="button" title="Tambah Admin">
                <span class="mdi align-middle mdi-plus"></span>
                Tambah
            </a>
            <form class="flex ml-auto relative" method="get">
                <input class="bg-gray-200 px-2 mx-2 rounded-md" placeholder="Cari..." name="keyword" title="Cari data admin" type="text" value="<?= $keyword ?>">
                <span class="absolute mdi mdi-magnify self-center" style="right:15px"></span>
            </form>
        </div>

        <?php if ($total_items > 0) { ?>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200 font-bold">
                        <?php $asc_toggle = $asc == "asc" ? "desc" : "asc"  ?>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "nama", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Nama</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "email", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Email</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "no_telp", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">No HP</a>
                        </th>
                        <th class="hidden lg:table-cell p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "aktif", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Status</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-right p-2"></th>
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
                        <tr class="bg-white flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap">
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Nama
                                </span>
                                <?= $nama ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center lg:text-left block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Email
                                </span>
                                <?= $email ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center lg:text-left block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    No HP
                                </span>
                                <?= $no_telp ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Status
                                </span>
                                <span class="rounded bg-<?= $aktif == 1 ? "blue" : "red" ?>-500 text-white py-1 px-3 text-xs font-bold">
                                    <?= $aktif == 1 ? "aktif" : "tidak aktif" ?>
                                </span>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center block lg:table-cell relative lg:static">
                                <a href="view?id_admin=<?= $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Lihat detail">
                                    <span class="mdi mdi-eye"></span>
                                </a>
                                <a href="update?id_admin=<?= $id_admin ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Ubah">
                                    <span class="mdi mdi-pencil"></span>
                                </a>
                                <a data-nama="<?= $nama ?>" href="delete?id_admin=<?= $id_admin ?>" class="delete-link cursor-pointer text-red-400 text-lg p-1 hover:text-red-600" role="button" title="Hapus">
                                    <span class="mdi mdi-trash-can"></span>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- -------------------------- SELECT ITEMS PER PAGE ------------------------- -->
            <div class="flex justify-center lg:justify-end my-3">
                <form class="flex" method="get">
                    <label class="self-center px-2" for="ipp">Baris per halaman</label>
                    <select class="p-2 rounded-sm bg-gray-200" id="ipp" name="ipp" onchange="submitItemsPerPage(this)">
                        <option <?= $ipp == 5 ? "selected" : "" ?> value="5">5</option>
                        <option <?= $ipp == 10 ? "selected" : "" ?> value="10">10</option>
                        <option <?= $ipp == 15 ? "selected" : "" ?> value="15">15</option>
                    </select>
                </form>
            </div>

            <!-- ------------------------------- PAGINATION ------------------------------- -->
            <div class="flex w-full justify-center items-center mt-auto my-3 pagination">
                <?php foreach (range(1, $page_count) as $page_num) { ?>
                    <?php if ($page_count == 1) continue; ?>
                    <?php $url_query = http_build_query(array_merge($_GET, ["page" => $page_num])) ?>
                    <a class="bg-gray-200 m-1 px-2 py-1 rounded-sm <?= $page_num == $page ? "active" : "" ?>" href="?<?= $url_query ?>" role="button">
                        <?= $page_num ?>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>

        <!-- --------------------------- EMPTY SEARCH RESULT -------------------------- -->
        <?php if (($is_search_mode && $total_items == 0)) { ?>
            <div class='text-lg text-center m-auto'>Tidak ada hasil dari kata pencarian '<?= $keyword ?>'</div>";
        <?php } ?>
    </main>
    <?php require_once "../../includes/scripts.php"; ?>
</body>

</html>
