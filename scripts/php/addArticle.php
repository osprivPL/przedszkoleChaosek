<?php
session_start();

require_once "sanitizeName.php";

$uploadDir = "./../../assets/articles/";

if (!isset($_FILES["articleImg"]) || $_FILES["articleImg"]["error"] != 0) {
    $_SESSION['blad'] = "<span style='color:red'>Nie wybrano zdjęcia lub wystąpił błąd.</span>";
    die("Błąd przesyłania pliku.");
}
$originalName = basename($_FILES["articleImg"]["name"]);
$fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

$extensions = ['jpg', 'jpeg', 'png', 'webp'];
if (!in_array($fileType, $extensions)) {
    $_SESSION['blad'] = "<span style='color:red'>Niedozwolony format pliku.</span>";
    die("Błąd formatu.");
}


$rawTitle = $_POST['articleTitle'];


$safeTitle = sanitizeFileName($rawTitle);

$newFileName = $safeTitle . "_" . uniqid() . "." . $fileType;

$targetFile = $uploadDir . $newFileName;

// ==========================================================

if (move_uploaded_file($_FILES["articleImg"]["tmp_name"], $targetFile)) {

    $connection = new mysqli("localhost", "root", "", "przedszkole");

    if ($connection->connect_errno == 0 ) {
        $title = $connection->real_escape_string($_POST['articleTitle']);
        $content = $connection->real_escape_string($_POST['articleContent']);
        $date = $connection->real_escape_string($_POST['articleData']);
        $imgNameDB = $connection->real_escape_string($newFileName);

        $sql = "INSERT INTO artykuly (naglowek, tresc, data, img) VALUES ('$title', '$content', '$date', '".'./assets/articles/'.$imgNameDB."')";

        if($connection->query($sql)){
            $_SESSION['powodzenie'] = "Dodano artykuł!";
        } else {
            $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
        }

        $connection->close();
    } else {
        $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    }

} else {
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
}
header("Location: ./../../electronicDiary/principle.php");