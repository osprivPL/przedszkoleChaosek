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
    $tytle = $arr[1];
    $content = $arr[2];
    $reciver = $connection->real_escape_string($_POST['odbiorca']);
    $action = $arr[3];
    echo $action;

    if ($action == 'save'){
        $sql = "UPDATE wiadomosci SET tytul = '". $tytle ."', tresc = '". $content ."', odbiorcaID = '". $reciver ."', robocze = 1  WHERE id = ". $messageID . ";";
    }
    else{
        $sql = "UPDATE wiadomosci SET tytul = '". $tytle ."', tresc = '". $content ."', odbiorcaID = '". $reciver ."', robocze = 0 WHERE id = ". $messageID . ";";
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
