<?php
session_start();
require_once "./printArr.php";
$kod = $_POST["tbxCode"];
if ($kod == $_SESSION['kod']) {
    header('Location: ./../../rekrutacjaCompleted.php');
    session_destroy();
    $_SESSION['registered'] = true;
} else {
    $_SESSION['error'] = 3;
    header('Location: ./../../mailCode.php');
}