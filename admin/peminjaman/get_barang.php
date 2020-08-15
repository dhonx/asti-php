<?php

require_once "../../connection/connection.php";

if (!isset($_GET["id"]) && !is_numeric($_GET["id"])) {
    $response = json_encode(["error" => true, "message" => "parameter `id` harus ada dan harus berupa angka"]);
    http_response_code(300);
    echo $response;
    die();
}

$id_komponen = $_GET["id"];

$q_get_barang = "SELECT
                    `barang`.`id_barang`,
                    `barang`.`kode_inventaris`,
                    `komponen`.`nama` AS `nama_komponen`
                FROM
                    `barang`
                INNER JOIN
                    `perolehan` ON `barang`.`id_perolehan` = `perolehan`.`id_perolehan`
                INNER JOIN
                    `detail_perolehan` ON `perolehan`.`id_perolehan` = `detail_perolehan`.`id_perolehan`
                INNER JOIN
                    `komponen` ON `detail_perolehan`.`id_komponen` = `komponen`.`id_komponen`
                WHERE
                    `komponen`.`id_komponen` = $id_komponen";

$r_get_barang = $connection->query($q_get_barang);

if ($r_get_barang && $r_get_barang->num_rows == 0) {
    $response = json_encode(["error" => true, "message" => "komponen dengan id $id_komponen tidak ada"]);
    http_response_code(300);
    echo $response;
    die();
}

$data = [];
while ($row = $r_get_barang->fetch_assoc()) {
    $new_row                        = [];
    $new_row["id_barang"]           = $row["id_barang"];
    $new_row["kode_inventaris"]     = $row["kode_inventaris"];
    $new_row["nama_komponen"]       = $row["nama_komponen"];
    array_push($data, $new_row);
}


echo json_encode($data);
