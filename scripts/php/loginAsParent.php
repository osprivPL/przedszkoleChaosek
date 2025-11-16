<?php
session_start();
$_SESSION['id'] = 1;
$_SESSION['logged'] = true;
$_SESSION['imie'] = "Jan";
$_SESSION['nazwisko'] = "Kruk";
$_SESSION['typ'] = 0;
header('Location: ./../../index.php');
die();