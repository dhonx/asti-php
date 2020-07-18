<?php

include "../connection/connection.php";

$nama =  "Don Alfonsus Nisnoni";
$email = "donnisnoni@uyelindo.ac.id";
$password = password_hash('donnisnoni@1234', PASSWORD_BCRYPT);


echo $password;
