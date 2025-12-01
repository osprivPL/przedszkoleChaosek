<?php

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

// 2. Odbierz dane (ID) wysłane przez JavaScript
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id > 0 && isset($connection)) {

    // 3. Przygotuj zapytanie (Prepared Statement) - to jest bezpieczne podejście w MySQLi
    // Znak zapytania (?) zastępuje ID
    $stmt = $connection->prepare("UPDATE pracedomowe SET zrobione = 1 WHERE id = ?");

    if ($stmt) {
        // "i" oznacza, że przekazujemy liczbę (integer)
        $stmt->bind_param("i", $id);

        // Wykonaj zapytanie
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
