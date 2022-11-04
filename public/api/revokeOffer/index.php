<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../MySQL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../Utils.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_POST['offerId'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Offer id must be supplied'
        ]);
        return;
    }

    $config = require($_SERVER['DOCUMENT_ROOT'] . '/../config.php');
    $mysql = new MySQL($config);
    $conn = $mysql->conn;

    $email = getEmail($config);

    $offerExists = $conn->prepare("SELECT offerSave FROM offers WHERE id = ? LIMIT 1");
    $offerExists->bind_param('i', $_POST['offerId']);
    $offerExists->execute();

    $offerResult = $offerExists->get_result();

    if($offerResult->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'error' => 'The offer must exist if you wish to revoke it'
        ]);
        return;
    }

    $saveUuidStmt = $conn->prepare("SELECT email FROM saves WHERE uuid = ? LIMIT 1");
    $saveUuidStmt->bind_param('i', $offerResult->fetch_row()[0]);
    $saveUuidStmt->execute();
    $offerEmail = $saveUuidStmt->get_result()->fetch_row()[0];

    if($offerEmail !== $email) {
        echo json_encode([
            'success' => false,
            'error' => 'You must be the owner of the save to revoke the offer.'
        ]);
        return;
    }

    $offerRequestStmt = $conn->prepare("DELETE FROM offers WHERE id = ?");
    // Supply the save and offer ids from the checkOfferValidity populated variables
    $offerRequestStmt->bind_param('i', $_POST['offerId']);
    $offerRequestStmt->execute();

    echo json_encode([
        'success' => true
    ]);
} else {
    http_response_code(405);
}