<?php
require_once __DIR__ . '/../../models/User.php';
use models\User;

session_start();

if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
    header('Location: ./../../index.php');
    die();
}

$newPassword = htmlentities($_POST['tbxFirstPassword'], ENT_QUOTES, 'UTF-8');
$user = $_SESSION['user'];
$connection = mysqli_connect('localhost', 'root', '', 'przedszkole');
if (!$connection) {
    $_SESSION['error'] = 0;
    unset($_POST);
    header('Location: ./../../index.php');
    die();
}
$stmt = $connection->prepare("UPDATE uzytkownicy SET haslo=?, firstLogin=0 WHERE ID=?");
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt->bind_param("si", $hashedPassword, $user->id);
if ($stmt->execute()) {
    unset($_SESSION['error']);
    unset($_POST);
    session_destroy();
    header('Location: ./../../index.php');
    die();
} else {
    $_SESSION['error'] = 10;
    unset($_POST);
    header('Location: ./../../index.php');
    die();
}
