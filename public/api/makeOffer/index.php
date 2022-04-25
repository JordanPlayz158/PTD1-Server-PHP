<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_POST['offerIds'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Pokemon offer id(s) must be supplied'
        ]);
        return;
    }

    if(!isset($_POST['requestIds'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Pokemon request id(s) must be supplied'
        ]);
        return;
    }

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $email = getEmail($config);

    $offerData = checkOfferValidity($conn, $email, Offer::Offering);
    $requestData = checkOfferValidity($conn, $email, Offer::Requesting);

    if(!$offerData || !$requestData) {
        return;
    }

    $saveUuidStmt = $conn->prepare("SELECT uuid FROM saves WHERE saves.email = ? AND saves.num = ?");
    $saveUuidStmt->bind_param('si', $offerData[0], $offerData[1]);
    $saveUuidStmt->execute();
    $offerSave = $saveUuidStmt->get_result()->fetch_row()[0];

    $saveUuidStmt->bind_param('si', $requestData[0], $requestData[1]);
    $saveUuidStmt->execute();
    $requestSave = $saveUuidStmt->get_result()->fetch_row()[0];

    $offerExists = $conn->prepare("SELECT EXISTS(SELECT id FROM offers WHERE offers.offerSave = ? AND offers.offerIds = ? AND offers.requestSave = ? AND offers.requestIds = ?)");
    $offerExists->bind_param('isis', $offerSave, $_POST['offerIds'], $requestSave, $_POST['requestIds']);
    $offerExists->execute();
    if($offerExists->get_result()->fetch_row()[0] === 1) {
        echo json_encode([
            'success' => false,
            'error' => 'You can not submit the same trade twice'
        ]);
        return;
    }

    $offerRequestStmt = $conn->prepare("INSERT INTO offers VALUES (0, ?, ?, ?, ?)");
    // Supply the save and offer ids from the checkOfferValidity populated variables
    $offerRequestStmt->bind_param('isis', $offerSave, $_POST['offerIds'], $requestSave, $_POST['requestIds']);
    if(!$offerRequestStmt->execute()) {
        if($offerRequestStmt->errno === 1062) {
            echo json_encode([
                'success' => false,
                'error' => 'You can not submit the same trade twice'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Unknown error occurred when adding offer to offers table'
            ]);
        }
        return;
    }

    echo json_encode([
        'success' => true
    ]);
} else {
    http_response_code(405);
}

class Offer {
    const Offering = 0;
    const Requesting = 1;
}

function checkOfferValidity(mysqli $conn, string $email, int $check) : false|array {
    $prepend = ['Offered', 'offer', 'You must own the pokemon you offer for a trade... Nice try though', 0, 'not '];

    if($check === 1) {
        $prepend = ['Requested', 'request', 'The pokemon you request a trade for must all be owned by the same person', 1, ''];
        $email = null;
    }

    $pokeUuidStmt = $conn->prepare("SELECT email, num, id FROM pokes WHERE pokes.uuid = ?");
    $pokeExistsStmt = $conn->prepare("SELECT EXISTS(SELECT num FROM trades WHERE trades.id = ? AND trades.email = ? AND trades.num = ?)");

    $save = null;
    foreach (explode(',', $_POST[$prepend[1] . 'Ids']) as $id) {
        $pokeUuidStmt->bind_param('i', $id);
        $pokeUuidStmt->execute();

        if($pokeUuidStmt->affected_rows === 0) {
            echo json_encode([
                'success' => false,
                'error' => $prepend[0] . ' pokemon ids must be valid'
            ]);
            return false;
        }

        $pokeUuidResult = $pokeUuidStmt->get_result()->fetch_row();
        $pokeRequestEmail = $pokeUuidResult[0];
        $pokeRequestSave = $pokeUuidResult[1];

        if($email === null) {
            $email = $pokeRequestEmail;
        }

        if($save === null) {
            $save = $pokeRequestSave;
        }

        // Ensure that the save is also the same for all on both sides
        // To accurately obtain the save Id for both parties
        if($pokeRequestEmail !== $email || $pokeRequestSave !== $save) {
            echo json_encode([
                'success' => false,
                'error' => $prepend[2] . ' (All pokemon ' . $prepend[1] . 'ed must also be on same save (for now))'
            ]);
            return false;
        }

        $pokeExistsStmt->bind_param('isi', $pokeUuidResult[2], $pokeUuidResult[0], $pokeUuidResult[1]);
        $pokeExistsStmt->execute();

        $pokeExistsResult = $pokeExistsStmt->get_result();
        $pokeExistsRow = $pokeExistsResult->fetch_row();

        // If offering, pokemon must not be currently up for trade
        // If requesting, pokemon must be up for trade
        if($pokeExistsRow[0] !== $prepend[3]) {
            echo json_encode([
                'success' => false,
                'error' => $prepend[0] . ' Pokemon(s) must ' . $prepend[4] . 'be currently up for trade'
            ]);
            return false;
        }
    }

    $pokeUuidStmt->close();
    $pokeExistsStmt->close();

    return [$email, $save];
}