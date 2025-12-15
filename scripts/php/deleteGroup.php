<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id > 0 && isset($connection)) {
    $rows = $connection->query("SELECT * FROM dzieci WHERE grupa = $id")->num_rows;
    if ($rows > 0) {
        echo "Błąd: Nie można usunąć grupy, do której przypisane są dzieci.";
        exit();
    }
    $stmt = $connection->prepare("DELETE FROM grupy WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "OK";
        } else {
            echo "Błąd wykonania: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Błąd zapytania SQL: " . $connection->error;
    }

} else {
    echo "Błąd: Brak ID lub połączenia z bazą.";
}