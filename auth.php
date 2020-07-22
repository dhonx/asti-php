<?php

session_start();

include "connection/connection.php";
include "utils.php";

$login_as_types = array("admin", "super_admin", "pegawai");

// Input
$email      = $_POST["email"];
$password   = $_POST["password"];
$login_as   = $_POST["login_as"];

// Check email
if (!isset($email)) {
    goto_login_page("email harus diisi");
} else if (strlen($email) < 6) {
    goto_login_page("username minimal 6 karakter");
}

// Check password
else if (!isset($password)) {
    goto_login_page("password harus diisi");
} else if (strlen($password) < 8) {
    goto_login_page("username minimal 8 karakter");
}

// Check login as
else if (!isset($login_as)) {
    goto_login_page("harus memilih login sebagai");
} else if (!in_array($login_as, $login_as_types)) {
    goto_login_page("Login sebagai tidak valid");
}

if ($login_as == "super_admin" || $login_as == "admin") {
    $query = "SELECT email, sandi, nama FROM admin WHERE email = '$email' AND tipe_admin = '$login_as'";
    $result = $connection->query($query);
    if ($result && $result->num_rows >= 1) {
        while ($row = $result->fetch_row()) {
            $email_from_db = $row[0];
            $hashed_password = $row[1];
            $nama = $row[2];
            if (password_verify($password, $hashed_password)) {
                $_SESSION["email"] = $email;
                $_SESSION["nama"] = $nama;
                $_SESSION["login_as"] = $login_as;
                $_SESSION["logged_in"] = true;
                redirect("./admin");
            } else {
                goto_login_page("Email atau sandi salah");
            }
        }
    } else {
        goto_login_page("Email atau sandi salah");
    }
}
