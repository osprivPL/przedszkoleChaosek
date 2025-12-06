<?php
session_start();
require_once "./printArr.php";
$kod = $_POST["tbxCode"];
$sqlTemp = $_SESSION['sql'];
printArr($_SESSION);
if ($kod == $_SESSION['kod']) {
    session_destroy();
    session_start();
    $_SESSION['registered'] = true;
    $_SESSION['sql'] = $sqlTemp;
    header('Location: ./../../rekrutacjaCompleted.php');
    die();
//    echo 'g';
} else {
    $_SESSION['error'] = 3;
    header('Location: ./../../mailCode.php');
//    echo 'nieg';
    die();
}