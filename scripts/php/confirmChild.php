<?php
error_reporting(0);
ini_set('display_errors', 0);

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

function responseAndExit($message) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    echo $message;
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;
$group = isset($input['group']) ? (int)$input['group'] : 0;
$haslo = generateRandomString();

if ($id > 0 && $connection && $group > 0) {
    $stmt = $connection->prepare("SELECT * FROM oczekujace WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result) {
        $idRodzica = 0;

        $sqlCzyIstnieje = "SELECT ID, typ FROM uzytkownicy WHERE login = '" . $connection->real_escape_string($result['email']) . "'";
        $queryCheck = $connection->query($sqlCzyIstnieje);

        if ($queryCheck->num_rows > 0) {
            $row = $queryCheck->fetch_assoc();
            $idRodzica = (int)$row['ID'];
            $permissionId = (int)$row['typ'];
            $sql = "UPDATE uprawnienia SET rodzic = 1 WHERE id = " . $permissionId;
            $connection->query($sql);
        } else {
            if ($connection->query("INSERT INTO uprawnienia(rodzic, nauczyciel, dyrektor) VALUES(1,0,0)")) {
                $permissionId = $connection->insert_id;
                $cryptedPassword = password_hash($haslo, PASSWORD_BCRYPT);
                $stmtParent = $connection->prepare("INSERT IGNORE INTO uzytkownicy (imie, nazwisko, typ, numerTelefonu, login, haslo, firstLogin) VALUES (?, ?, ?, ?, ?, ?, 1)");
                $stmtParent->bind_param("ssisss", $result['imieRodzica'], $result['nazwiskoRodzica'],$permissionId, $result['numerTelefonu'], $result['email'], $cryptedPassword);
                $stmtParent->execute();

                $idRodzica = $connection->insert_id;
                $stmtParent->close();
            }
        }

        if ($idRodzica > 0) {
            $stmtChild = $connection->prepare("INSERT INTO dzieci (imie, nazwisko, pesel, adres, grupa, img, IDRodzica) VALUES (?, ?, ?, ?, ?, 'brak', ?)");
            $stmtChild->bind_param("ssssii", $result['imieDziecka'], $result['nazwiskoDziecka'], $result['pesel'], $result['adres'], $group, $idRodzica);
            $stmtChild->execute();
            $stmtChild->close();

            $stmtDel = $connection->prepare("DELETE FROM oczekujace WHERE id = ?");
            $stmtDel->bind_param("i", $id);

            if ($stmtDel->execute()) {
                try {
                    sendTempPassword($haslo, $result['imieDziecka'], $result['nazwiskoDziecka'], $result['email']);
                } catch (Exception $e) {
                }

                $stmtDel->close();
                responseAndExit("OK");
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