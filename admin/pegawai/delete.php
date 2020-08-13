<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_pegawai"]) && !is_numeric($_GET["id_pegawai"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_pegawai = $_GET["id_pegawai"];
$q_delete = "DELETE FROM `pegawai` WHERE `id_pegawai` = $id_pegawai";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
