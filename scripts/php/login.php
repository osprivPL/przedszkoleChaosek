<?php
//0 - błęd połączenia
//1 - nie znaleziono uzytkownika
//2 - nieprawidłowe hasło
require_once __DIR__ . '/../../models/User.php';
use models\User;
session_start();
require_once "printArr.php";

if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    header('Location: ./../../index.php');
    die();
}
$_SESSION['logged'] = false;

printArr($_POST);

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if (!$connection) {
    $_SESSION['error'] = 0;
    unset($_POST);
    header('Location: ./../../index.php');
    die();
} else {
    $email = htmlentities($_POST['tbxEmail'], ENT_QUOTES, 'UTF-8');
    $password = htmlentities($_POST['tbxHaslo'], ENT_QUOTES, 'UTF-8');
    if ($result = $connection->query(sprintf("SELECT * FROM uzytkownicy WHERE login='%s'", mysqli_real_escape_string($connection, $email)))) {
        if ($result->num_rows > 0){
            $result=$result->fetch_assoc();
            $sql = "SELECT rodzic,nauczyciel,dyrektor FROM uprawnienia WHERE id=".$result['typ'].";";
            $permResult = $connection->query($sql);
            $permResult = $permResult->fetch_all();
            echo password_hash("haslo", PASSWORD_DEFAULT);
            if (password_verify($password, $result['haslo'])) {
                $user = new User(
                    $result['ID'],
                    $result['imie'],
                    $result['nazwisko'],
                    $permResult[0],
                    $result['numerTelefonu'],
                    $result['login'],
                    $result['firstLogin']
                );
                $_SESSION['user'] = $user;
                $_SESSION['logged'] = true;
//                printArr($_SESSION);
                printArr($result);
                unset($_SESSION['error']);
                unset($_POST);
                echo 'good password';
                header('Location: ./../../index.php');
                die();
            }
            else{
                $_SESSION['error'] = 2;
                unset($_POST);
                header('Location: ./../../index.php');
                die();
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