<?php
session_start();

require_once "sanitizeName.php";

if (!isset($_POST['articleId'])) {
    $_SESSION['blad'] = "<span style='color:red'>Błąd: Brak identyfikatora artykułu.</span>";
    header("Location: ./../../electronicDiary/principle.php");
    exit();
}

$articleId = (int)$_POST['articleId'];
$uploadDir = "./../../assets/articles/";

$connection = new mysqli("localhost", "root", "", "przedszkole");

if ($connection->connect_errno != 0) {
    $_SESSION['powodzenie'] = "Błąd połączenia z bazą.";
    header("Location: ./../../electronicDiary/principle.php");
    exit();
}

$queryCurrent = "SELECT img FROM artykuly WHERE id = $articleId";
$resultCurrent = $connection->query($queryCurrent);
$currentArticle = $resultCurrent->fetch_assoc();
$currentDbImgPath = $currentArticle['img']; // np. ./assets/articles/fotka.jpg

$title = $connection->real_escape_string($_POST['editArticleHeader']);
$content = $connection->real_escape_string($_POST['editArticleContent']);
$date = $connection->real_escape_string($_POST['editArticleData']);

$sql = "UPDATE artykuly SET naglowek = '$title', tresc = '$content', data = '$date'";

if (isset($_FILES["editArticleImg"]) && $_FILES["editArticleImg"]["error"] == 0) {

    $shouldUpdateImage = true;

    $currentPhysicalPath = str_replace('./assets/articles/', $uploadDir, $currentDbImgPath);

    if (file_exists($currentPhysicalPath)) {
        $hashNew = md5_file($_FILES["editArticleImg"]["tmp_name"]);
        $hashOld = md5_file($currentPhysicalPath);

        if ($hashNew === $hashOld) {
            $shouldUpdateImage = false;
        }
    }

    if ($shouldUpdateImage) {
        $originalName = basename($_FILES["editArticleImg"]["name"]);
        $fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($fileType, $extensions)) {
            $_SESSION['blad'] = "<span style='color:red'>Niedozwolony format pliku. Zmiany tekstowe nie zostały zapisane.</span>";
            $connection->close();
            header("Location: ./../../electronicDiary/principle.php");
            exit();
        }

        $rawTitle = $_POST['editArticleHeader'];
        $safeTitle = sanitizeFileName($rawTitle);
        $newFileName = $safeTitle . "_" . uniqid() . "." . $fileType;
        $targetFile = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES["editArticleImg"]["tmp_name"], $targetFile)) {
            $imgNameDB = $connection->real_escape_string($newFileName);
            $fullPath = './assets/articles/' . $imgNameDB;
            $sql .= ", img = '$fullPath'";
        } else {
            $_SESSION['blad'] = "Błąd podczas przesyłania pliku na serwer.";
            $connection->close();
            header("Location: ./../../electronicDiary/principle.php");
            exit();
        }
    }
}

$sql .= " WHERE id = $articleId";

if ($connection->query($sql)) {
    $_SESSION['powodzenie'] = "Zaktualizowano artykuł!";
} else {
    $_SESSION['powodzenie'] = "Wystąpił błąd bazy danych podczas aktualizacji.";
}

$connection->close();

header("Location: ./../../electronicDiary/principle.php");
exit();
?>