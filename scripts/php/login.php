<?php
if (isset($_POST['logged']) && $_POST['logged'] == 'true') {
    header('Location: ./../../index.php');
}
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

if (!$connection) {
    $_POST["error"] = "Wystąpił błąd, spróbuj ponownie później";
    header('Location: ./../../index.php');
}