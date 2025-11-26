<?php
use models\User;
require_once "./../../models/User.php";
session_start();
session_destroy();
session_start();
$_SESSION['logged'] = true;
$_SESSION['user'] = new User(1, "Jan", "Kruk", 0, "123456789", "jKruk@gmail.com");
//$_SESSION['imie'] = "Jan";
//$_SESSION['nazwisko'] = "Kruk";
//$_SESSION['typ'] = 0;
//$_SESSION['telefon'] = "123456789";
//$_SESSION['email'] = "jKruk@gmail.com";
header('Location: ./../../index.php');
die();