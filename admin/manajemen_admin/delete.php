<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

if (!isset($_GET["id_admin"]) && !is_numeric($_GET["id_admin"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_admin = $_GET["id_admin"];
$query = "DELETE FROM admin WHERE id_admin = $id_admin";
$result = $connection->query($query);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
