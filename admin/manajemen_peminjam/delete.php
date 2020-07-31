<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_peminjam"]) && !is_numeric($_GET["id_peminjam"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_peminjam = $_GET["id_peminjam"];
$q_delete = "DELETE FROM `peminjam` WHERE `id_peminjam` = $id_peminjam";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
