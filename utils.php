<?php

function goto_login_page($message = "")
{
    if (strlen($message) < 1) {
        header('location: /asti/login.php');
    } else {
        header("location: /asti/login.php?message=$message");
    }
}
