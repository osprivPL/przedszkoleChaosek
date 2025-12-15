<?php
session_start();
require_once "printArr.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ./../../index.php");
    die();
}

require_once "sanitizeName.php";

$uploadDir = "./../../assets/staff/";

if (!isset($_FILES["editTeacherImg"]) || $_FILES["editTeacherImg"]["error"] != 0) {
    $_SESSION['blad'] = "<span style='color:red'>Nie wybrano zdjęcia lub wystąpił błąd.</span>";
    die("Błąd przesyłania pliku.");
}
$originalName = basename($_FILES["editTeacherImg"]["name"]);
$fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$extensions = ['jpg', 'jpeg', 'png', 'webp'];
if (!in_array($fileType, $extensions)) {
    $_SESSION['blad'] = "<span style='color:red'>Niedozwolony format pliku.</span>";
    die("Błąd formatu.");
}

$rawTitle = pathinfo($originalName, PATHINFO_FILENAME);
$safeTitle = sanitizeFileName($rawTitle);
$newFileName = $safeTitle . "_" . uniqid() . "." . $fileType;

$targetFile = $uploadDir . $newFileName;

if (move_uploaded_file($_FILES["editTeacherImg"]["tmp_name"], $targetFile)) {

    $connection = mysqli_connect("localhost", "root", "", "przedszkole");

    if ($connection) {
        printArr($_POST);
        $id = htmlentities($_POST["editTeacherId"], ENT_QUOTES, "UTF-8");
        $name = htmlentities($_POST['editTeacherFirstName'], ENT_QUOTES, "UTF-8");
        $lastname = htmlentities($_POST["editTeacherLastName"], ENT_QUOTES, "UTF-8");
        $email = htmlentities($_POST['editTeacherEmail'], ENT_QUOTES, "UTF-8");
        $permission = htmlentities($_POST['editTeacherRole'], ENT_QUOTES, "UTF-8");
        $phone = htmlentities($_POST['editTeacherPhone'], ENT_QUOTES, "UTF-8");
        $img = $connection->real_escape_string($newFileName);
        $description = htmlentities($_POST['editTeacherDesc'], ENT_QUOTES, "UTF-8");


        $sql = "UPDATE uzytkownicy SET imie = '$name', nazwisko = '$lastname', login = '$email', numerTelefonu = '$phone', opinia = '$description', zdjecie = '$img' WHERE id = $id";
        $connection->query($sql);
        $sql = "SELECT typ FROM uzytkownicy WHERE id = $id";
        $result = $connection->query($sql)->fetch_assoc();
        $permissionId = $result['typ'];


        if ($permission == 2) {
            $sql = "UPDATE uprawnienia SET rodzic = 0, nauczyciel = 1, dyrektor = 1 WHERE id = $permissionId";
        } else {
            $sql = "UPDATE uprawnienia SET rodzic = 0, nauczyciel = 1, dyrektor = 0 WHERE id = $permissionId";
        }
        $connection->query($sql);

        echo 'g';
        $_SESSION['powodzenie'] = "Nauczyciel zostało zaktualizowany pomyślnie.";
    } else {
        echo 'nieg';
        $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    }
    header("Location: ./../../electronicDiary/principle.php");
}
die();