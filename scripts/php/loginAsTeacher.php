<?php
use models\User;
require_once "./../../models/User.php";
session_start();
session_destroy();
session_start();
$_SESSION['logged'] = true;
$_SESSION['user'] = new User(2, "Stanisław", "Odrowski", 1, "9999999999", "stasiu@outlook.com");
header('Location: ./../../index.php');
die();