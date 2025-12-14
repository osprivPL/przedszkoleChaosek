<?php

session_start();
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id > 0 && isset($connection)) {
    $stmtCheck = $connection->prepare('SELECT count(id) as liczba FROM grupy WHERE Wychowawca = ?');
    $stmtCheck->bind_param('i', $id);
    if ($stmtCheck->execute()) {
        $result = $stmtCheck->get_result();
        $row = $result->fetch_assoc();
        if ($row['liczba'] > 0) {
            echo "Błąd: Nie można usunąć nauczyciela przypisanego do grupy.";
            $stmtCheck->close();
            exit();
        }
        else{
            $stmt = $connection->prepare("DELETE FROM uzytkownicy WHERE id = ?");

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
        }
    } else {
        echo "Błąd wykonania podczas sprawdzania przypisania do grupy: " . $stmtCheck->error;
        $stmtCheck->close();
        exit();
    }


} else {
    echo "Błąd: Brak ID lub połączenia z bazą.";
}