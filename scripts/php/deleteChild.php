<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

// 2. Odbierz dane (ID) wysłane przez JavaScript
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id > 0 && isset($connection)) {
    $stmt = $connection->prepare("DELETE FROM dzieci WHERE id = ?");

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