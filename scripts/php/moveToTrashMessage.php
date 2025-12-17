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

    $value = $connection->real_escape_string($_POST['value']);
    $arr = explode('|', $value);
    $messageID = $arr[0];
    $userID = $arr[1];
    $nadawcaID = $arr[2];

    if ($userID == $nadawcaID){
        $sql = "UPDATE wiadomosci SET usunieteNadawca = 1, robocze = 0 WHERE id = ". $messageID . ";";
    }
    else{
        $sql = "UPDATE wiadomosci SET usunieteOdbiorca = 1, robocze = 0 WHERE id = ". $messageID . ";";
    }



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
