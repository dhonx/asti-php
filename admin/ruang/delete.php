<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_ruang"]) && !is_numeric($_GET["id_ruang"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_ruang = $_GET["id_ruang"];
$q_delete = "DELETE FROM `ruang` WHERE id_ruang = $id_ruang";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
