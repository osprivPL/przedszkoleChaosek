<?php
function generateRandomString() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < 16; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}


$connection = mysqli_connect("localhost", "root", "", "przedszkole");

// 2. Odbierz dane (ID) wysłane przez JavaScript
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;
$group = isset($input['group']) ? (int)$input['group'] : 0;
$haslo = generateRandomString();

if ($id > 0 && isset($connection) && $group > 0 && $group <=4) {

    $sql = "SELECT * FROM oczekujace WHERE id=".$id.';';
    $result = $connection->query($sql)->fetch_assoc();

    $stmt = $connection->prepare("INSERT INTO uzytkownicy (imie, nazwisko, typ, numerTelefonu, login, haslo)")



    $stmt = $connection->prepare("INSERT INTO dzieci (imie, nazwisko, pesel, adres, grupa, img, IDRodzica) VALUES (?, ?, ?, ?, ?, 'brak', ?)");
    if ($stmt) {
        $stmt->bind_param("ssssisi", $result['imie'], $result['nazwisko'], $result['pesel'], $result['adres'], $group, $result['IDRodzica']);
        if ($stmt->execute()) {
            //ok
        } else {
            echo "Błąd wykonania: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Błąd zapytania SQL: " . $connection->error;
    }

    $stmt = $connection->prepare("DELETE FROM komunikaty WHERE id = ?");

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
    echo "Błąd: Brak danych lub połączenia z bazą";
}
