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
