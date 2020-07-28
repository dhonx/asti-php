<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_instansi"]) && !is_numeric($_GET["id_instansi"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_instansi = $_GET["id_instansi"];
$q_delete = "DELETE FROM `instansi` WHERE id_instansi = $id_instansi";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
