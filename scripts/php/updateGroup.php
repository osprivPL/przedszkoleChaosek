<?php
session_start();
require_once "printArr.php";

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if ($connection) {
    printArr($_POST);
    $id = $_POST['groupID'];
    $nazwa = $_POST['inputGroupName'.$id];
    $opiekun = $_POST['inputGroupSupervisor'.$id];

    $stmt = $connection->prepare("UPDATE grupy SET nazwa = ?, wychowawca = ? WHERE id = ?");
    $stmt->bind_param("sii", $nazwa, $opiekun, $id);
    if ($stmt->execute()) {
        echo 'g';
        $_SESSION['powodzenie'] = "Grupa została zaktualizowana pomyślnie.";
    }
    else{
        echo 'nieg';
        $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    }
}
else{
    echo 'nieg';
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
}
header("Location: ./../../electronicDiary/principle.php");
die();