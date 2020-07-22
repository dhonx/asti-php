<?php

function goto_login_page($message = "")
{
    if (strlen($message) < 1) {
        header('location: /asti/login.php');
    } else {
        header("location: /asti/login.php?message=$message");
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

function build_search_query($keyword, $fields = ["id"]) {
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
