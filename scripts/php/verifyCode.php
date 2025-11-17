<?php
session_start();
require_once "./printArr.php";
$kod = $_POST["tbxCode"];
printArr($_SESSION);
if ($kod == $_SESSION['kod']) {
    session_destroy();
    $_SESSION['registered'] = true;
    header('Location: ./../../rekrutacjaCompleted.php');
    die();
} else {
    $_SESSION['error'] = 3;
    header('Location: ./../../mailCode.php');
}