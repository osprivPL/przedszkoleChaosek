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
    unset($_POST);
    header('Location: ./../../index.php');
    die();
} else {
    $email = htmlentities($_POST['tbxEmail'], ENT_QUOTES, 'UTF-8');
    $password = htmlentities($_POST['tbxHaslo'], ENT_QUOTES, 'UTF-8');
    if ($result = $connection->query(sprintf("SELECT * FROM uzytkownicy WHERE email='%s'", mysqli_real_escape_string($connection, $email)))) {
        if ($result->num_rows > 0){
            $result=$result->fetch_assoc();
            print_r($result);
            if (password_verify(password_hash($password, PASSWORD_DEFAULT), $result['haslo'])) {
                $_SESSION['logged'] = true;
                $_SESSION['imie'] = $result['imie'];
                unset($_SESSION['error']);
                unset($_POST);
                if ()
            }

        }
        else{
            $_SESSION['error'] = 1;
            unset($_POST);
            header('Location: ./../../index.php');
            die();
        }
    }
}