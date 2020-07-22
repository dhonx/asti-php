<?php
require_once "config.php";

session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $login_as = $_SESSION['login_as'];
    if ($login_as == "super_admin" || $login_as == "admin") {
        header("location: admin");
    } else {
        header("location: pegawai");
    }
}
