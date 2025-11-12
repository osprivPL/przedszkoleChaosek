<?php
//    TO JEST NA PROFILOWE ZEBY MOC PRZESYLAC ZDJ DO ARTYKUŁÓW
    session_start();
    require_once "connect.php";
//    require_once "printArr.php";

    $uploadDir = "./../../uploads/avatars/";
    $fileName = basename($_FILES["fileUpload"]["name"]);
    $targetFile = $uploadDir . $fileName;


    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $newFileName = $_SESSION['username'] .".". $fileType;
    $targetFile = $uploadDir . $newFileName;
    $extensions = ['jpg', 'jpeg', 'png'];

//    printArr($_SESSION);
//    echo $newFileName."<br>";
//    echo $targetFile . "<br>";

    if (in_array($fileType, $extensions) === false) {
        $_SESSION['blad'] = "<span style='color:red'>Nieprawidłowy format pliku.</span>";
        header("Location: ./../../dashboard.php");
        die();
    }
    if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $targetFile)) {
        /** @var TYPE_NAME $host */
        /** @var TYPE_NAME $db_user */
        /** @var TYPE_NAME $db_passwd */
        /** @var TYPE_NAME $db_name */
        $connection = @new mysqli($host, $db_user, $db_passwd, $db_name);

        if ($connection->connect_errno == 0 ) {
            $row = $connection->query("UPDATE users SET photo = './uploads/avatars/" . $newFileName . "' WHERE username = '" . $_SESSION['username'] . "'");
            header("Location: ./../../dashboard.php");
            die();
        }
        else{
            $_SESSION['blad'] = "<span style='color:red'>Wystąpił błąd podczas przesyłania pliku.</span>";
            echo "Error: " . $connection->connect_errno;
            header("Location: ./../../dashboard.php");
            die();
        }


    } else {
        $_SESSION['blad'] = "<span style='color:red'>Wystąpił błąd podczas przesyłania pliku.</span>";
    }

    header("Location: ./../../dashboard.php");
    die();

