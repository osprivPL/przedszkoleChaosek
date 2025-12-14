<?php
require_once __DIR__ . '/../../models/User.php';

use models\User;

function generateRandomString(): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 16; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

session_start();
$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ./../../index.php");
    die();
}

require_once "sanitizeName.php";

$uploadDir = "./../../assets/articles/";

if (!isset($_FILES["teacherImg"]) || $_FILES["teacherImg"]["error"] != 0) {
    $_SESSION['blad'] = "<span style='color:red'>Nie wybrano zdjęcia lub wystąpił błąd.</span>";
    die("Błąd przesyłania pliku.");
}
$originalName = basename($_FILES["teacherImg"]["name"]);
$fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

$extensions = ['jpg', 'jpeg', 'png', 'webp'];
if (!in_array($fileType, $extensions)) {
    $_SESSION['blad'] = "<span style='color:red'>Niedozwolony format pliku.</span>";
    die("Błąd formatu.");
}


$rawTitle = $_POST['teacherImg'];


$safeTitle = sanitizeFileName($rawTitle);

$newFileName = $safeTitle . "_" . uniqid() . "." . $fileType;

$targetFile = $uploadDir . $newFileName;

// ==========================================================

if (move_uploaded_file($_FILES["teacherImg"]["tmp_name"], $targetFile)) {

    $connection = mysqli_connect("localhost", "root", "", "przedszkole");

    if ($connection) {
        $name = htmlentities($_POST['teacherFirstName'], ENT_QUOTES, 'UTF-8');
        $lastname = htmlentities($_POST['teacherLastName'], ENT_QUOTES, 'UTF-8');
        $email = htmlentities($_POST['teacherEmail'], ENT_QUOTES, 'UTF-8');
        $password = generateRandomString();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $phone = htmlentities($_POST['teacherPhone'], ENT_QUOTES, 'UTF-8');
        $role = htmlentities($_POST['teacherRole'], ENT_QUOTES, 'UTF-8');
        $imgNameDB = $connection->real_escape_string($newFileName);
        $rodzic = 0;
        $nauczyciel = 1;
        $dyrektor = 0;
        $firstLogin = 1;

        if ($role == 2) {
            $dyrektor = 1;
        }

        $stmt = $connection->prepare('INSERT INTO uprawnienia (rodzic, nauczyciel, dyrektor) VALUES(?, ?, ?)');
        $stmt->bind_param("iii", $rodzic, $nauczyciel, $dyrektor);
        $stmt->execute();
        $permissionId = $stmt->insert_id;

        $stmt = $connection->prepare('INSERT INTO uzytkownicy (imie, nazwisko, login, typ, numerTelefonu, haslo, firstLogin, zdjecie) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param("sssissis", $name, $lastname, $email, $permissionId, $phone, $hashedPassword, $firstLogin, $imgNameDB);

    if ($stmt->execute()) {
        $_SESSION['powodzenie'] = "Nauczyciel został dodany pomyślnie.";
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
}
die();