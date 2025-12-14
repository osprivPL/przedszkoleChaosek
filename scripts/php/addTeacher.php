<?php
require_once __DIR__ . '/../../models/User.php';

use models\User;
function generateRandomString() : string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 16; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

session_start();
$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ./../../index.php");
    die();
}

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection) {
    $name = htmlentities($_POST['teacherFirstName'], ENT_QUOTES, 'UTF-8');
    $lastname = htmlentities($_POST['teacherLastName'], ENT_QUOTES, 'UTF-8');
    $email = htmlentities($_POST['teacherEmail'], ENT_QUOTES, 'UTF-8');
    $password = generateRandomString();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = htmlentities($_POST['teacherRole'], ENT_QUOTES, 'UTF-8');

//    $stmt = $connection->prepare("INSERT INTO uzytkownicy")

    if ($stmt->execute()) {
        $_SESSION['powodzenie'] = "Grupa został dodany pomyślnie.";
        if ($user->typ[2] == 1) {
            header("Location: ./../../electronicDiary/principle.php");
        } else {
            header("Location: ./../../electronicDiary/teacher.php");
        }
    }
} else {
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    if ($user->typ[2] == 1) {
        header("Location: ./../../electronicDiary/principle.php");
    } else {
        header("Location: ./../../electronicDiary/teacher.php");
    }
}
die();