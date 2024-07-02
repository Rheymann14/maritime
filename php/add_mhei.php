<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $institutional_code = $_POST['institutionalCode'];
    $school_name = $_POST['name'];
    $school_type = strtoupper($_POST['type']);
    $region = $_POST['region'];
    $address = $_POST['address'];
    $email = $_POST['emailAddress'];
    $contact_number = $_POST['contactNumber'];
    $logo = $_POST['logo'];

    // Handle file upload
    if ($logo && $logo['tmp_name']) {
        $logoPath = 'uploads/' . basename($logo['name']);
        move_uploaded_file($logo['tmp_name'], $logoPath);
    } else {
        $logoPath = null;
    }

    // Prepare data for API
    $postData = [
        'institutional_code' => $institutional_code,
        'school_name' => $school_name,
        'school_type' => $school_type,
        'region' => $region,
        'address' => $address,
        'email' => $email,
        'contact_number' => $contact_number,
        'logo_file' => $logoPath
    ];

    // Convert to JSON
    $jsonPostData = json_encode($postData);

    // cURL to post data to API
    $ch = curl_init('https://maritimeobt.com/api/mhei'); // Replace with your API endpoint
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonPostData),
        "Authorization: Bearer " .$_SESSION['token']
    ]);

    $response = curl_exec($ch);
    $mhei = json_decode($response, 1);
    $username = $mhei['mhei']['institutional_code'];
    $password = $mhei['default_password'];
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 201) {
        echo json_encode(['status' => 'success', 'message' => "MHEI added successfully! The MHEI's default credentials have been emailed.<br><br>Username: MHEI-".$username."<br>Password: ".$password]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add MHEI', 'response' => $response]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>