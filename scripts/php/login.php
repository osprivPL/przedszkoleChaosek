<?php
//0 - błęd połączenia
//1 - nie znaleziono uzytkownika
session_start();
require_once "printArr.php";
if (isset($_POST['logged']) && $_POST['logged'] == 'true') {
    header('Location: ./../../index.php');
    die();
}
$_SESSION['logged'] = false;

printArr($_POST);

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if (!$connection) {
    $_POST["error"] = "Wystąpił błąd, spróbuj ponownie później";
    $_SESSION['error'] = 0;
    header('Location: ./../../index.php');
} else {
    $email = htmlentities($_POST['tbxEmail'], ENT_QUOTES, 'UTF-8');
    $password = htmlentities($_POST['tbxHaslo'], ENT_QUOTES, 'UTF-8');
    if ($result = $connection->query(sprintf("SELECT * FROM uzytkownicy WHERE email='%s'", mysqli_real_escape_string($connection, $email)))) {
        if ($result->num_rows > 0){

        }
        else{
            $_SESSION['error'] = 1;
            header('Location: ./../../index.php');
            die();
        }
    }

    print_r($result);
}