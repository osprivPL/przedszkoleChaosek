<?php
require_once __DIR__ . '/../../models/User.php';
//NIE DZIAŁA
use models\User;

session_start();
$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection->connect_errno == 0 ) {

    $idArr = explode(',', $_POST['idToExplode']);
    $type = $_POST['type'];
    $idList = implode(',', $idArr);

    $column = ($type == 0) ? "usunieteOdbiorca" : "usunieteNadawca";

    $sql = "UPDATE wiadomosci SET {$column} = 1, robocze = 0 WHERE id IN ({$idList});";

    if ($connection->query($sql)) {
        $_SESSION['powodzenie'] = "Wszystko poszło OK";
    } else {
        $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    }




}
else {
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
}

