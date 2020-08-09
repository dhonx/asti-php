<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_unit"]) && !is_numeric($_GET["id_unit"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_unit = $_GET["id_unit"];
$q_delete = "DELETE FROM `unit` WHERE id_unit = $id_unit";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
