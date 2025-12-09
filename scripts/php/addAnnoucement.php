<?php
session_start();
$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection) {
    $title = $_POST['komunikatHeader'];
    $content = $_POST['komunikatContent'];
    $visibility = $_POST['komunikatGrupa'];

    $sql = "INSERT INTO komunikaty (tytul, tresc, przynaleznosc, data, autor) VALUES ('$title', '$content', '$visibility', CURRENT_DATE(), ".$user->typ.")";
    $connection->query($sql);
    $_SESSION['powodzenie'] = "Komunikat został dodany pomyślnie.";
    header("Location: ./../../electronicDiary/principle.php");
    die();
}
else{
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    header("Location: ./../../electronicDiary/principle.php");
    die();
}