<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection) {
    $title = $_POST['editKomunikatHeader'];
    $content = $_POST['editKomunikatContent'];
    $visibility = $_POST['editKomunikatGrupa'];
    $id = $_POST['editKomunikatIdHiddenInput'];

    $sql = "UPDATE komunikaty SET tytul = '$title', tresc = '$content', przynaleznosc = '$visibility', data = CURRENT_DATE() WHERE id = $id";
    $connection->query($sql);
    $_SESSION['powodzenie'] = "Komunikat został zaktualizowany pomyślnie.";
    header("Location: ./../../electronicDiary/principle.php");
    die();
}
else{
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    header("Location: ./../../electronicDiary/principle.php");
    die();
}