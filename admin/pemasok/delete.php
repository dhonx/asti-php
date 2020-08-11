<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_pemasok"]) && !is_numeric($_GET["id_pemasok"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_pemasok = $_GET["id_pemasok"];
$q_delete = "DELETE FROM `pemasok` WHERE id_pemasok = $id_pemasok";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
