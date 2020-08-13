<?php
require_once "../../connection/connection.php";

if (!isset($_GET["id"]) && !is_numeric($_GET["id"])) {
    $response = json_encode(["error" => true, "message" => "id_komponen harus ada dan harus berupa angka"]);
    http_response_code(300);
    echo $response;
    die();
}

$id_komponen = $_GET["id"];

$q_get_komponen = "SELECT
                        `perolehan`.`id_perolehan`,
                        `perolehan`.`tanggal`,
                        `perolehan`.`status`,
                        `detail_perolehan`.`jumlah`,
                        `komponen`.`nama`
                    FROM
                        `perolehan`
                    INNER JOIN `detail_perolehan`
                        ON `perolehan`.`id_perolehan` = `detail_perolehan`.`id_perolehan`
                    INNER JOIN komponen
                        ON `detail_perolehan`.`id_komponen` = `komponen`.`id_komponen`
                    WHERE
                        `detail_perolehan`.`id_komponen` = $id_komponen
                    GROUP BY
                        `perolehan`.`id_perolehan`";

$r_get_komponen = $connection->query($q_get_komponen);

if ($r_get_komponen && $r_get_komponen->num_rows == 0) {
    $response = json_encode(["error" => true, "message" => "komponen dengan id $id_komponen tidak ada"]);
    http_response_code(300);
    echo $response;
    die();
}

$data = [];
while ($row = $r_get_komponen->fetch_assoc()) {
    $new_row                    = [];
    $new_row["id_perolehan"]    = $row["id_perolehan"];
    $new_row["nama"]            = $row["nama"];
    $new_row["jumlah"]          = $row["jumlah"];
    $new_row["status"]          = ucwords($row["status"]);
    $new_row["tanggal"]         = date_format(date_create($row["tanggal"]), "j F Y");
    array_push($data, $new_row);
}


echo json_encode($data);
