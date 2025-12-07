<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection) {
    $title = $_POST['komunikatHeader'];
    $content = $_POST['komunikatContent'];
    $visibility = $_POST['komunikatGrupa'];

    $sql = "INSERT INTO komunikaty (tytul, tresc, przynaleznosc, data) VALUES ('$title', '$content', '$visibility', CURRENT_DATE())";
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