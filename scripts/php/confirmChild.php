<?php
// Na samym początku upewniamy się, że błędy nie są wypisywane na ekran
error_reporting(0);
ini_set('display_errors', 0);

// Rozpoczynamy buforowanie (nawet jeśli nadrzędny plik już to zrobił)
ob_start();

require_once('./MAIL.php');

function generateRandomString() : string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 16; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Funkcja pomocnicza do czyszczenia wszystkiego i kończenia skryptu
function responseAndExit($message) {
    // Czyścimy WSZYSTKIE poziomy buforowania, aż do zera
    while (ob_get_level()) {
        ob_end_clean();
    }
    // Wypisujemy komunikat
    echo $message;
    // Zabijamy skrypt
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;
$group = isset($input['group']) ? (int)$input['group'] : 0;
$haslo = generateRandomString();

if ($id > 0 && $connection && $group > 0 && $group <= 4) {
    $stmt = $connection->prepare("SELECT * FROM oczekujace WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result) {
        $idRodzica = 0;

        $cryptedPassword = password_hash($haslo, PASSWORD_BCRYPT);
        // Poprawka: w SQL jest 6 wartości (3 to '0'), ale bindujesz 5 zmiennych - to jest OK, bo typ jest hardcoded
        $stmtParent = $connection->prepare("INSERT IGNORE INTO uzytkownicy (imie, nazwisko, typ, numerTelefonu, login, haslo) VALUES (?, ?, 0, ?, ?, ?)");
        $stmtParent->bind_param("sssss", $result['imieRodzica'], $result['nazwiskoRodzica'], $result['numerTelefonu'], $result['email'], $cryptedPassword);
        $stmtParent->execute();

        if ($stmtParent->affected_rows > 0) {
            $idRodzica = $connection->insert_id;
        } else {
            $stmtCheck = $connection->prepare("SELECT ID FROM uzytkownicy WHERE login = ?");
            $stmtCheck->bind_param("s", $result['email']);
            $stmtCheck->execute();
            $resCheck = $stmtCheck->get_result()->fetch_assoc();

            if ($resCheck) {
                $idRodzica = $resCheck['ID'];
            }
            $stmtCheck->close();
        }
        $stmtParent->close();

        if ($idRodzica > 0) {
            $stmtChild = $connection->prepare("INSERT INTO dzieci (imie, nazwisko, pesel, adres, grupa, img, IDRodzica) VALUES (?, ?, ?, ?, ?, 'brak', ?)");
            $stmtChild->bind_param("ssssii", $result['imieDziecka'], $result['nazwiskoDziecka'], $result['pesel'], $result['adres'], $group, $idRodzica);
            $stmtChild->execute();
            $stmtChild->close();

            $stmtDel = $connection->prepare("DELETE FROM oczekujace WHERE id = ?");
            $stmtDel->bind_param("i", $id);

            if ($stmtDel->execute()) {
                try {
                    sendTempPassword($haslo, $result['imieRodzica'], $result['nazwiskoRodzica']);
                } catch (Exception $e) {
                    // mail error, ignorujemy
                }

                // --- TU JEST KLUCZOWA ZMIANA ---
                $stmtDel->close();
                responseAndExit("OK");
                // -------------------------------
            }
            $stmtDel->close();

        } else {
            responseAndExit("Błąd: Nie udało się ustalić ID rodzica.");
        }
    } else {
        responseAndExit("Błąd: Nie znaleziono wpisu w oczekujących.");
    }

} else {
    responseAndExit("Błąd: Nieprawidłowe dane wejściowe.");
}