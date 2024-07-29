<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $gender = $_POST['gender'];
    $name = $_POST['name'];
    $email = $_POST['emailAddress'];
    $username = $_POST['username'];
    $rank = $_POST['rank'];
    $unit_assigned = $_POST['unitAssigned'];
    $unit_address = $_POST['unitAddress'];
    $contact_number = $_POST['contactNumber'];

    // Prepare data for API
    $postData = [
        'gender' => $gender,
        'name' => $name,
        'email' => $email,
        'username' => $username,
        'rank' => $rank,
        'unit_assigned' => $unit_assigned,
        'unit_address' => $unit_address,
        'contact_number' => $contact_number,
    ];

    // Convert to JSON
    $jsonPostData = json_encode($postData);

    // cURL to post data to API
    $ch = curl_init($_SESSION['default_ip']."/api/pcg-staff"); // Replace with your API endpoint
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonPostData),
        "Authorization: Bearer " .$_SESSION['token']
    ]);

    $response = curl_exec($ch);
    $pcg_staff = json_decode($response, 1);
    $username = $pcg_staff['user']['username'];
    $password = $pcg_staff['default_password'];
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 201) {
        echo json_encode(['status' => 'success', 'message' => "PCG STAFF added successfully! The PCG STAFF's default credentials have been emailed.<br><br>Username: PCG STAFF-".$username."<br>Password: ".$password]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add PCG STAFF', 'response' => $response]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>