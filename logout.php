<?php

include "./utils.php";

session_start();
session_destroy();
goto_login_page();
