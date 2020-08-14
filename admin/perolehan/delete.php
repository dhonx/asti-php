<?php
require_once "../../connection/connection.php";
require_once "../../utils.php";

authenticate(["super_admin", "admin"]);

if (!isset($_GET["id_perolehan"]) && !is_numeric($_GET["id_perolehan"])) {
    redirect($_SERVER['HTTP_REFERER']);
}

$id_perolehan = $_GET["id_perolehan"];
$q_delete_perolehan = "DELETE FROM `perolehan` WHERE `id_perolehan` = $id_perolehan";
$result = $connection->query($q_delete_perolehan);
if ($result) {
    redirect($_SERVER['HTTP_REFERER']);
}

// NOTE: Uncomment this code for debugging purpose only
// else {
//     print_r($connection->error_list);
//     die();
// }
