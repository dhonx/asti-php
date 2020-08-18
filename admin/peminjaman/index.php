<?php
require_once "../../utils.php";
require_once "../../config.php";
require_once "../../connection/connection.php";
require_once "../../common/page.php";
require_once "common.php";

authenticate(["super_admin", "admin"]);

$valid_columns  = ["nama_peminjam", "jumlah_barang", "tanggal_peminjaman", "disetujui"];
$common_data = processs_common_input($_GET, $valid_columns);

$sort_by        = $common_data["sort_by"];
$asc            = $common_data["asc"];
$keyword        = $connection->real_escape_string(clean($common_data["keyword"]));
$ipp            = $common_data["ipp"];
$page           = $common_data["page"];
$is_search_mode = $common_data["is_search_mode"];

$q_get_detail_peminjaman_count =    "SELECT
                                        COUNT(`id_detail_peminjaman`)
                                    FROM
                                        `detail_peminjaman`
                                    WHERE
                                        `id_peminjaman` = `peminjaman`.`id_peminjaman`";
$search_query = "SELECT
                    `peminjaman`.*,
                    `peminjam`.`nama` AS `nama_peminjam`,
                    ($q_get_detail_peminjaman_count) AS `jumlah_barang`,
                    CASE 
                        WHEN
                            `peminjaman`.`setuju_1_pinjam` = 1
                        AND
                            `peminjaman`.`setuju_2_pinjam` = 1
                        AND
                            `peminjaman`.`setuju_3_pinjam` = 1
                        THEN 'Ya'
                        ELSE 'Tidak'
                    END AS `disetujui`
                FROM
                    `peminjaman`
                INNER JOIN `detail_peminjaman`
                    ON `detail_peminjaman`.`id_peminjaman` = `peminjaman`.`id_peminjaman`
                INNER JOIN `peminjam`
                    ON `peminjam`.`id_peminjam` = `peminjam`.`id_peminjam`
                GROUP BY
                    `peminjaman`.`id_peminjaman`
                WHERE ";
$search_query .= build_search_query($keyword, ["nama_peminjam"]);

if ($is_search_mode) {
    $count_all_result  = $connection->query("SELECT * FROM ($search_query) AS `_peminjaman` ORDER BY $sort_by $asc");
} else {
    $count_all_result  = $connection->query("SELECT * FROM `peminjaman`");
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
    $query = "SELECT * FROM ($search_query) AS `_peminjaman` ORDER BY $sort_by $asc";
} else {
    $query =    "SELECT
                    `peminjaman`.*,
                    `peminjam`.`nama` AS `nama_peminjam`,
                    ($q_get_detail_peminjaman_count) AS `jumlah_barang`,
                    CASE 
                        WHEN
                            `peminjaman`.`setuju_1_pinjam` = 1
                        AND
                            `peminjaman`.`setuju_2_pinjam` = 1
                        AND
                            `peminjaman`.`setuju_3_pinjam` = 1
                        THEN 'Ya'
                        ELSE 'Tidak'
                    END AS `disetujui`
                FROM
                    `peminjaman`
                INNER JOIN `detail_peminjaman`
                    ON `detail_peminjaman`.`id_peminjaman` = `peminjaman`.`id_peminjaman`
                INNER JOIN `peminjam`
                    ON `peminjam`.`id_peminjam` = `peminjam`.`id_peminjam`
                GROUP BY
                    `peminjaman`.`id_peminjaman`
                LIMIT $limit OFFSET $offset";
    $query = "SELECT * FROM ($query) AS `_peminjaman` ORDER BY $sort_by $asc";
}

$result = $connection->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title><?= $is_search_mode ? "Hasil pencarian dari $keyword - " : "" ?> Manajemen Peminjaman - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/header.php" ?>
    <?php require_once "../../includes/loading.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="font-bold page-header py-2 text-2xl">Manajemen Peminjaman</h3>
        <div class="flex my-4">
            <a class="active-scale bg-blue-900 font-bold mr-2 px-3 py-2 rounded-md text-white" href="create" role="button" title="Tambah Peminjaman">
                <span class="mdi align-middle mdi-plus"></span>
                Tambah
            </a>
            <form class="flex ml-auto relative" method="get">
                <input class="bg-gray-200 px-2 mx-2 rounded-md" placeholder="Cari..." name="keyword" title="Cari data peminjaman" type="text" value="<?= $keyword ?>">
                <span class="absolute mdi mdi-magnify self-center" style="right:15px"></span>
            </form>
        </div>

        <?php if ($total_items > 0) { ?>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200 font-bold">
                        <?php $asc_toggle = $asc == "asc" ? "desc" : "asc"  ?>
                        <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "nama_peminjam", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Peminjam</a>
                        </th>
                        <th class="hidden lg:table-cell text-center p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "jumlah_barang", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Jumlah Barang</a>
                        </th>
                        <th class="hidden lg:table-cell text-center p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "tanggal_peminjaman", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Tanggal Peminjaman</a>
                        </th>
                        <th class="hidden lg:table-cell text-center p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "disetujui", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Disetujui</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-right p-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) {
                        $id_peminjaman      = $row["id_peminjaman"];
                        $nama_peminjam      = $row["nama_peminjam"];
                        $jumlah_barang      = $row["jumlah_barang"];
                        $tanggal_peminjaman = $row["tanggal_peminjaman"];
                        $disetujui    = $row["disetujui"];
                    ?>
                        <tr class="bg-white flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap">
                            <td class="w-full lg:w-auto p-1 lg:text-left text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Peminjam
                                </span>
                                <?= $nama_peminjam ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Jumlah Barang
                                </span>
                                <?= $jumlah_barang ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Tanggal Peminjaman
                                </span>
                                <?= date_format(date_create($tanggal_peminjaman), "j F Y") ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Disetujui
                                </span>
                                <?= $disetujui ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 flex relative lg:static">
                                <div class="ml-auto">
                                    <a href="view?id_peminjaman=<?= $id_peminjaman ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Lihat detail">
                                        <span class="mdi mdi-information-outline"></span>
                                    </a>
                                    <a href="update?id_peminjaman=<?= $id_peminjaman ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Ubah">
                                        <span class="mdi mdi-pencil-outline"></span>
                                    </a>
                                    <a data-nama="<?= "" ?>" href="delete?id_peminjaman=<?= $id_peminjaman ?>" class="delete-link cursor-pointer text-red-400 text-lg p-1 hover:text-red-600" role="button" title="Hapus">
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
