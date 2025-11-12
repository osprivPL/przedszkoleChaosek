<?php
if (isset($_POST['logged']) && $_POST['logged'] == 'true') {
    header('Location: ./../../index.php');
}
    $connection = mysqli_connect("localhost", "root", "", "klekot");

    if (!$connection) {
        $_POST["error"] = "Wystąpił błąd, spróbuj ponownie później";
    }