<?php
require_once "../../utils.php";
require_once "../../config.php";
require_once "../../connection/connection.php";
require_once "../../common/page.php";

authenticate(["super_admin", "admin"]);

$valid_columns  = ["id_instansi", "nama", "email", "alamat", /* "no_telp", */ "jumlah_peminjam"];
$common_data = processs_common_input($_GET, $valid_columns);

$sort_by        = $common_data["sort_by"];
$asc            = $common_data["asc"];
$keyword        = $connection->real_escape_string($common_data["keyword"]);
$ipp            = $common_data["ipp"];
$page           = $common_data["page"];
$is_search_mode = $common_data["is_search_mode"];

$q_count_peminjam = "SELECT
                        COUNT(*)
                    FROM
                        `peminjam`
                    WHERE
                        `peminjam`.`id_instansi` = `instansi`.`id_instansi`";

$search_query = "SELECT 
                    `instansi`.*,
                    ($q_count_peminjam) AS `jumlah_peminjam`
                FROM
                    `instansi` 
                WHERE
                    `id_instansi` != 1
                AND " . build_search_query($keyword, ["nama", "email", "alamat", "no_telp"]);

if ($is_search_mode) {
    $count_all_result  = $connection->query("SELECT * FROM ($search_query) AS `instansi_` ORDER BY $sort_by $asc");
} else {
    $count_all_result  = $connection->query("SELECT * FROM `instansi` WHERE `id_instansi` != 1");
}

$total_items = $count_all_result->num_rows;
$page_count  = get_page_count($total_items, $ipp);

if ($page > $page_count) {
    $page = $page_count;
}

$offset_limit   = get_offset_limit($page, $ipp);
$offset         = $offset_limit["offset"];
$limit          = $offset_limit["limit"];

if ($is_search_mode) {
    $search_query .= " LIMIT $limit OFFSET $offset";
    $query = "SELECT * FROM ($search_query) AS `instansi_` ORDER BY $sort_by $asc";
} else {
    $query = "SELECT `instansi`.*, ($q_count_peminjam) AS `jumlah_peminjam` FROM `instansi` WHERE `id_instansi` != 1 LIMIT $limit OFFSET $offset";
    $query = "SELECT * FROM ($query) AS `instansi_` ORDER BY $sort_by $asc";
}

$result = $connection->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../includes/css.php" ?>
    <title><?= $is_search_mode ? "Hasil pencarian dari $keyword - " : "" ?> Manajemen Instansi - ASTI</title>
</head>

<body class="flex font-sans min-h-screen overflow-hidden text-sm">
    <?php require_once "../../includes/header.php" ?>
    <?php require_once "../../includes/loading.php" ?>

    <main class="flex flex-auto flex-col main">
        <h3 class="font-bold page-header py-2 text-2xl">Manajemen Instansi</h3>
        <div class="flex my-4">
            <a class="active-scale bg-blue-900 font-bold mr-2 px-3 py-2 rounded-md text-white" href="create" role="button" title="Tambah Instansi">
                <span class="mdi align-middle mdi-plus"></span>
                Tambah
            </a>
            <form class="flex ml-auto relative" method="get">
                <input class="bg-gray-200 px-2 mx-2 rounded-md" placeholder="Cari..." name="keyword" title="Cari data instansi" type="text" value="<?= $keyword ?>">
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
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "alamat", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Alamat</a>
                        </th>
                        <!-- <th class="hidden lg:table-cell lg:text-left p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "no_telp", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">No Telp</a>
                        </th> -->
                        <th class="hidden lg:table-cell p-2">
                            <?php $url_query = http_build_query(array_merge($_GET, ["sort_by" => "jumlah_peminjam", "asc" => $asc_toggle])) ?>
                            <a class="block" href="?<?= $url_query ?>">Jumlah Peminjam</a>
                        </th>
                        <th class="hidden lg:table-cell lg:text-right p-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) {
                        $id_instansi     = $row["id_instansi"];
                        $nama            = $row["nama"];
                        $email           = $row["email"];
                        $alamat          = $row["alamat"];
                        // $no_telp         = $row["no_telp"];
                        $jumlah_peminjam = $row["jumlah_peminjam"];
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
                                    Alamat
                                </span>
                                <?= $alamat  ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 text-center block lg:table-cell relative lg:static">
                                <span class="lg:hidden absolute text-center top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase h-full">
                                    Jumlah Peminjam
                                </span>
                                <?= $jumlah_peminjam ?>
                            </td>
                            <td class="w-full lg:w-auto p-1 flex relative lg:static">
                                <div class="ml-auto">
                                    <a href="view?id_instansi=<?= $id_instansi ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Lihat detail">
                                        <span class="mdi mdi-eye"></span>
                                    </a>
                                    <a href="update?id_instansi=<?= $id_instansi ?>" class="text-blue-400 text-lg p-1 hover:text-blue-600" role="button" title="Ubah">
                                        <span class="mdi mdi-pencil"></span>
                                    </a>
                                    <a data-nama="<?= $nama ?>" href="delete?id_instansi=<?= $id_instansi ?>" class="delete-link cursor-pointer text-red-400 text-lg p-1 hover:text-red-600" role="button" title="Hapus">
                                        <span class="mdi mdi-trash-can"></span>
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