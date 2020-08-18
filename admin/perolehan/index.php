<?php
require_once "../../utils.php";
require_once "../../config.php";
require_once "../../connection/connection.php";
require_once "../../common/page.php";

authenticate(["super_admin", "admin"]);

$valid_columns  = ["id_pemasok", "nama_komponen", "nama_pemasok", "harga_beli", "jumlah", "total", "tanggal", "status"];
$common_data = processs_common_input($_GET, $valid_columns);

$sort_by        = $common_data["sort_by"];
$asc            = $common_data["asc"];
$keyword        = $connection->real_escape_string(clean($common_data["keyword"]));
$ipp            = $common_data["ipp"];
$page           = $common_data["page"];
$is_search_mode = $common_data["is_search_mode"];

$search_query = "SELECT
                    `perolehan`.*,
                    `pemasok`.`nama` AS `nama_pemasok`,
                    `detail_perolehan`.`harga_beli`,
                    `detail_perolehan`.`jumlah`,
                    `komponen`.`nama` AS `nama_komponen`,
                    SUM(`detail_perolehan`.`harga_beli` * `detail_perolehan`.`jumlah`) `total`
                FROM
                    `perolehan`
                INNER JOIN `detail_perolehan`
                    ON `perolehan`.`id_perolehan` = `detail_perolehan`.`id_perolehan`
                INNER JOIN `pemasok`
                    ON `perolehan`.`id_pemasok` = `pemasok`.`id_pemasok`
                INNER JOIN `komponen`
                    ON `detail_perolehan`.`id_komponen` = `komponen`.`id_komponen`
                WHERE ";
$search_query .= build_search_query($keyword, ["`komponen`.`nama`", "`pemasok`.`nama`"]);
$search_query .= " GROUP BY `perolehan`.`id_perolehan`";

if ($is_search_mode) {
    $count_all_result  = $connection->query("SELECT * FROM ($search_query) AS `perolehan_` ORDER BY $sort_by $asc");
} else {
    $count_all_result  = $connection->query("SELECT * FROM `perolehan`");
}

$total_items = $count_all_result->num_rows;
$page_count  = get_page_count($total_items, $ipp);

if ($page > $page_count) {
    $page = $page_count;
}

$offset_limit = get_offset_limit($page, $ipp);
$offset       = $offset_limit["offset"];
$limit        = $offset_limit["limit"];

if ($is_search_mode) {
    $search_query .= " LIMIT $limit OFFSET $offset";
    $query = "SELECT * FROM ($search_query) AS `perolehan_` ORDER BY $sort_by $asc";
} else {
    $query = "SELECT
                    `perolehan`.*,
                    `pemasok`.`nama` AS `nama_pemasok`,
                    `detail_perolehan`.`harga_beli`,
                    `detail_perolehan`.`jumlah`,
                    `komponen`.`nama` AS `nama_komponen`,
                    SUM(`detail_perolehan`.`harga_beli` * `detail_perolehan`.`jumlah`) `total`
                FROM
                    `perolehan`
                INNER JOIN `detail_perolehan`
                    ON `perolehan`.`id_perolehan` = `detail_perolehan`.`id_perolehan`
                INNER JOIN `pemasok`
                    ON `perolehan`.`id_pemasok` = `pemasok`.`id_pemasok`
                INNER JOIN `komponen`
                    ON `detail_perolehan`.`id_komponen` = `komponen`.`id_komponen`
                GROUP BY `perolehan`.`id_perolehan`
                LIMIT $limit OFFSET $offset";
    $query = "SELECT * FROM ($query) AS `perolehan_` ORDER BY $sort_by $asc";
}

$result = $connection->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title><?= $is_search_mode ? "Hasil pencarian dari $keyword - " : "" ?> Manajemen Perolehan Barang - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/header.php" ?>
    <?php require_once "../../includes/loading.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="font-bold page-header py-2 text-2xl">Manajemen Perolehan Barang</h3>
        <div class="flex my-4">
            <a class="active-scale bg-blue-900 font-bold mr-2 px-3 py-2 rounded-md text-white" href="create" role="button" title="Tambah Perolehan Barang">
                <span class="mdi align-middle mdi-plus"></span>
                Tambah
            </a>
            <form class="flex ml-auto relative" method="get">
                <input class="bg-gray-200 px-2 mx-2 rounded-md" placeholder="Cari..." name="keyword" title="Cari data perolehan" type="text" value="<?= $keyword ?>">
                <span class="absolute mdi mdi-magnify self-center" style="right:15px"></span>
            </form>
        </div>

        <?php if ($total_items > 0) { ?>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200 font-bold">
                        <?php $asc_toggle = $asc == "asc" ? "desc" : "asc"  ?>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "nama_komponen", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Komponen</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "nama_pemasok", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Pemasok</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "harga_beli", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Harga</a>
                        </th>
                        <th class="hidden lg:table-cell p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "jumlah", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Jumlah</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "total", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Total</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "tanggal", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Tanggal</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "status", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Status</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-right p-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) {
                        $id_perolehan   = $row["id_perolehan"];
                        $nama_komponen  = $row["nama_komponen"];
                        $nama_pemasok   = $row["nama_pemasok"];
                        $tanggal        = $row["tanggal"];
                        $harga_beli     = $row["harga_beli"];
                        $jumlah         = $row["jumlah"];
                        $total          = $row["total"];
                        $status         = $row["status"];
                    ?>
                        <tr class="bg-white flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap">
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Komponen
                                </span>
                                <?= $nama_komponen ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Pemasok
                                </span>
                                <?= $nama_pemasok ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Harga
                                </span>
                                <?= number_format($harga_beli, "2") ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center block relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Jumlah
                                </span>
                                <?= $jumlah ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    total
                                </span>
                                <?= number_format($total, "2") ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Tanggal
                                </span>
                                <?= date_format(date_create($tanggal), "j F Y") ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Status
                                </span>
                                <?= ucwords($status) ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 flex relative lg:static">
                                <div class="ml-auto">
                                    <a href="view?id_perolehan=<?= $id_perolehan ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Lihat detail">
                                        <span class="mdi mdi-information-outline"></span>
                                    </a>
                                    <a href="update?id_perolehan=<?= $id_perolehan ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Ubah">
                                        <span class="mdi mdi-pencil-outline"></span>
                                    </a>
                                    <a data-nama="Perolehan Barang <?= $nama_komponen ?> yang diperoleh pada tanggal <?= $tanggal ?>" href="delete?id_perolehan=<?= $id_perolehan ?>" class="delete-link cursor-pointer text-red-400 text-lg p-1 hover:text-red-600" role="button" title="Hapus">
                                        <span class="mdi mdi-trash-can-outline"></span>
                                    </a>
                                </div>
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
        <?php } else { ?>
            <?php if ($is_search_mode) { ?>
                <div class="text-lg text-center m-auto">Tidak ada hasil dari kata pencarian '<?= $keyword ?>'</div>"
            <?php } else { ?>
                <div class="text-lg text-center m-auto">Belum ada data</div>"
            <?php } ?>
        <?php } ?>
    </main>
    <?php require_once "../../includes/scripts.php"; ?>
</body>

</html>
