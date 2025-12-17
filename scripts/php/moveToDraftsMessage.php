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

if ($connection->connect_errno == 0 ) {
    $id = $connection->real_escape_string(($_POST['messageId']));

    $sql = "UPDATE wiadomosci SET usunieteNadawca = 0, robocze = 1 WHERE id = ". $id. ";";



    if ($connection->query($sql)) {
        $_SESSION['powodzenie'] = "Wszystko poszło OK";
    } else {
        $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    }
}
else {
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
}
header("Location: ./../../electronicDiary/inbox.php");
