<?php
require_once __DIR__ . '/../../models/User.php';
use models\User;

session_start();
$user = new User();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    header('Location: ./../index.php');
    die();
}

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection) {
    $title = $_POST['editKomunikatHeader'];
    $content = $_POST['editKomunikatContent'];
    $visibility = $_POST['editKomunikatGrupa'];
    $id = $_POST['editKomunikatIdHiddenInput'];

    $sql = "UPDATE komunikaty SET tytul = '$title', tresc = '$content', przynaleznosc = '$visibility', data = CURRENT_DATE() WHERE id = $id";
    $connection->query($sql);
    $_SESSION['powodzenie'] = "Komunikat został zaktualizowany pomyślnie.";
    if ($user->typ[2] == 1){
        header("Location: ./../../electronicDiary/principle.php");
    }
    else if ($user->typ[1] == 1){
        header("Location: ./../../electronicDiary/teacher.php");
    }
    die();
}
else{
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    header("Location: ./../../electronicDiary/principle.php");
    die();
}