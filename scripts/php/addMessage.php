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
    $reciver = $connection->real_escape_string($_POST['odbiorca']);
    $title = $connection->real_escape_string($_POST['tytle']);
    $content = $connection->real_escape_string($_POST['tresc']);
    $date = date('Y-m-d');
    if($connection->real_escape_string($_POST['action']) == "draft"){
      $draft = 1;
    }
    else{
        $draft = 0;
    }
//    $imgNameDB = $connection->real_escape_string($newFileName);

    $sql = "INSERT INTO wiadomosci (tytul, tresc, dataWyslania, nadawcaID, odbiorcaID, robocze, usunieteNadawcam usunieteOdbiorca) VALUES ('$title', '$content', '$date', $user->id , $reciver, $draft, 0, 0)";



    if ($connection->query($sql)) {
        $_SESSION['powodzenie'] = "Wiadomosć wysłana!";
    } else {
        $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    }
}
else {
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
}
header("Location: ./../../electronicDiary/inbox.php");
