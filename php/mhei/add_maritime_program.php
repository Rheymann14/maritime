<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Convert to JSON
    $jsonPostData = json_encode($_POST);

    // cURL to post data to API
    $ch = curl_init($_SESSION['default_ip']."/api/maritime-program"); // Replace with your API endpoint
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonPostData),
        "Authorization: Bearer " .$_SESSION['token']
    ]);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 201) {
        echo json_encode(['status' => 'success', 'message' => "Maritime Program added successfully!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add Maritime Program', 'response' => $response]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>