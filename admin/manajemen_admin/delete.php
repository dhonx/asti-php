<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin"]);

if (!isset($_GET["id_admin"]) && !is_numeric($_GET["id_admin"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_admin = $_GET["id_admin"];
$q_delete = "DELETE FROM admin WHERE id_admin = $id_admin";
$result = $connection->query(mysqli_real_escape_string($connection, $q_delete));
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
