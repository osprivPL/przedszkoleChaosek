<?php
session_start();
session_destroy();
session_start();
$_SESSION['id'] = 1;
$_SESSION['logged'] = true;
$_SESSION['imie'] = "Jan";
$_SESSION['nazwisko'] = "Kruk";
$_SESSION['typ'] = 0;
$_SESSION['telefon'] = "123456789";
$_SESSION['email'] = "jKruk@gmail.com";
header('Location: ./../../index.php');
die();