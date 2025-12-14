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

if ($connection) {
    $name = htmlentities($_POST['newGroupName'], ENT_QUOTES, 'UTF-8');
    $supervisor = htmlentities($_POST['newGroupSupervisor'], ENT_QUOTES, 'UTF-8');
    $stmt = $connection->prepare('INSERT INTO grupy (nazwa, wychowawca) VALUES (?, ?)');
    $stmt->bind_param("si", $name, $supervisor);

    if ($stmt->execute()) {
        $_SESSION['powodzenie'] = "Grupa został dodany pomyślnie.";
        if ($user->typ[2] == 1) {
            header("Location: ./../../electronicDiary/principle.php");
        } else {
            header("Location: ./../../electronicDiary/teacher.php");
        }
    }
} else {
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    if ($user->typ[2] == 1) {
        header("Location: ./../../electronicDiary/principle.php");
    } else {
        header("Location: ./../../electronicDiary/teacher.php");
    }
}
die();