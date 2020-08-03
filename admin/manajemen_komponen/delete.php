<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_komponen"]) && !is_numeric($_GET["id_komponen"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_komponen = $_GET["id_komponen"];
$q_delete = "DELETE FROM `komponen` WHERE `id_komponen` = $id_komponen";
$result = $connection->query($q_delete);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}
