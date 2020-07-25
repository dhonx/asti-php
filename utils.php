<?php

// require "config.php";

function goto_login_page($message = "")
{
    if (strlen($message) < 1) {
        header('location: /asti/login');
    } else {
        header("location: /asti/login?message=$message");
    }
}

function authenticate()
{
    session_start();
    if (!isset($_SESSION['logged_in'])) {
        goto_login_page();
    }
}

function redirect($url)
{
    header("location: $url");
}

function get_prev_field($field_name)
{
    echo isset($_POST[$field_name]) ? $_POST[$field_name] : "";
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

function check_active_url($url)
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $host = $_SERVER["HTTP_HOST"];
    // echo $protocol . $host . BASE_PATH . $url . "<br>";
    // echo get_current_url();
    return get_current_url() == $protocol . $host . BASE_PATH . $url ? "active" : "";
}
