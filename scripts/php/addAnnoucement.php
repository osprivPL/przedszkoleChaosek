<?php
require_once __DIR__ . '/../../models/User.php';
use models\User;
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
    $title = $_POST['komunikatHeader'];
    $content = $_POST['komunikatContent'];
    $visibility = $_POST['komunikatGrupa'];

    $sql = "INSERT INTO komunikaty (tytul, tresc, przynaleznosc, data, autor) VALUES ('$title', '$content', '$visibility', CURRENT_DATE(), ".$user->id.")";
    $connection->query($sql);
    $_SESSION['powodzenie'] = "Komunikat został dodany pomyślnie.";
    if ($user->typ == 1){
        header("Location: ./../../electronicDiary/teacher.php");
    }
    else{
        header("Location: ./../../electronicDiary/principle.php");
    }
    die();
}
else{
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    if ($user->typ == 1){
        header("Location: ./../../electronicDiary/teacher.php");
    }
    else{
        header("Location: ./../../electronicDiary/principle.php");
    }
    die();
}