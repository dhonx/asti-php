<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_pemesanan"]) && !is_numeric($_GET["id_pemesanan"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_pemesanan = $_GET["id_pemesanan"];
$q_delete = "DELETE FROM `pemesanan` WHERE `id_pemesanan` = $id_pemesanan";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
