<?php

function goto_login_page($message = "")
{
    if (strlen($message) < 1) {
        header('location: /asti/login');
    } else {
        header("location: /asti/login?message=$message");
    }
}

function authenticate($user = [])
{
    session_start();
    if (!isset($_SESSION['logged_in'])) {
        goto_login_page();
    }
    if (isset($_SESSION["login_as"]) && !in_array($_SESSION["login_as"], $user)) {
        http_response_code(404);
        include_once "error_pages/404.php";
        die();
    }
}

function redirect($url)
{
    header("location: $url");
}

function get_prev_field($field_name)
{
    return isset($_POST[$field_name]) ? $_POST[$field_name] : "";
}

function prints($text = "")
{
    echo $text;
}

function build_search_query($keyword, $fields = ["id"])
{
    $query = "";
    $splited_keyword = explode(" ", $keyword);
    foreach ($splited_keyword as $idx_keyword => $alphabet) {
        foreach ($fields as $idx_field => $field) {
            $query .= " $field LIKE '%$alphabet%' ";
            if ($idx_field < count($fields) - 1) {
                $query .= " OR";
            }
        }
        if ($idx_keyword < count($splited_keyword) - 1) {
            $query .= " OR";
        }
    }
    return $query;
}

function get_current_url()
{
    $url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}

function get_http_protocol()
{
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
}

function check_active_url($url, $folder = FALSE)
{
    $protocol = get_http_protocol();
    $host = $_SERVER["HTTP_HOST"];
    $url = $protocol . $host . BASE_PATH . $url;
    if ($folder) {
        return starts_with(get_current_url(), $url) ? "active" : "";
    }
    return get_current_url() == $url ? "active" : "";
}

function starts_with($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function build_url($url = "")
{
    return BASE_PATH . $url;
}

function clean($input)
{
    return htmlspecialchars($input);
}

function convert_date($date_str)
{
    $date = date_create($date_str);
    return date_format($date, "Y-m-d");
}


function get_current_id_admin()
{
    require "connection/connection.php";
    $admin_email = $_SESSION["email"];
    $q_get_id_admin = "SELECT `id_admin` FROM `admin` WHERE `email` = '$admin_email'";
    $r_get_id_admin = $connection->query($q_get_id_admin);
    $first_row = $r_get_id_admin->fetch_assoc();
    return $first_row["id_admin"];
}
