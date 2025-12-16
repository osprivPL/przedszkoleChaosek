<?php
session_start();
require_once "printArr.php";

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection) {
    printArr($_POST);
    $imie = $_POST['editChildName'];
    $nazwisko = $_POST['editChildSurname'];
    $pesel = $_POST['editChildPesel'];
    $adres = $_POST['editChildAddress'];
    $grupa = $_POST['editChildGrupa'];
    $id = $_POST['editChildId'];
    $opinia = $_POST['editChildOpinion'];

    $sql = "UPDATE dzieci SET imie = '$imie', nazwisko = '$nazwisko', pesel = '$pesel', adres = '$adres', grupa = '$grupa', opinia = '$opinia' WHERE id = $id";
    echo $sql;
    $connection->query($sql);
    echo 'g';
    $_SESSION['powodzenie'] = "Informacje o dziecku zostały zaktualizowane.";
}
else{
    echo 'nieg';
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
}
header("Location: ./../../electronicDiary/principle.php");
die();