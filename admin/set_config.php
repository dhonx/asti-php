<?php

require_once "../utils.php";

authenticate();

if (isset($_GET["sidenav"])) {
    $prev_sidenav_value = isset($_SESSION["sidenav"]) && is_numeric($_SESSION["sidenav"]) ? $_SESSION["sidenav"] : 1;
    $_SESSION["sidenav"] = $prev_sidenav_value == 1 ? 0 : 1;
}

?>
