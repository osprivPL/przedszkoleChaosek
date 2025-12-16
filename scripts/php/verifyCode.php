<?php
session_start();
require_once "./printArr.php";
$kod = $_POST["tbxCode"];
$poprawny =$_SESSION['kod']
$sqlTemp = $_SESSION['sql'];
printArr($_SESSION);
if ($kod == $_SESSION['kod']) {
    session_destroy();
    session_start();
    $_SESSION['registered'] = true;
    $_SESSION['sql'] = $sqlTemp;
    header('Location: ./../../rekrutacjaCompleted.php');
    die();
} else {
    $_SESSION['error'] = 3;
    $_SESSION['kod'] = $poprawny
    header('Location: ./../../mailCode.php');
    die();
}