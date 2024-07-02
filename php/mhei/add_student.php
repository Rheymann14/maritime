<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Convert to JSON
    $jsonPostData = json_encode($_POST);

    // cURL to post data to API
    $ch = curl_init('http://127.0.0.1:8000/api/student'); // Replace with your API endpoint
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonPostData),
        "Authorization: Bearer " .$_SESSION['token']
    ]);

    $response = curl_exec($ch);
    $student = json_decode($response, 1);
    $username = $student['user']['username'];
    $password = $student['default_password'];
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 201) {
        echo json_encode(['status' => 'success', 'message' => "Student added successfully! The Student's default credentials have been emailed.<br><br>Username: ".$username."<br>Password: ".$password]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add Student', 'response' => $response]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>