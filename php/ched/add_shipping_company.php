<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $company_name = $_POST['name'];
    $username = 'SC-'.$_POST['username'];
    $region = $_POST['region'];
    $address = $_POST['address'];
    $email = $_POST['emailAddress'];
    $contact_number = $_POST['contactNumber'];
    $logo = $_POST['logo'];
    $gender = 'MALE';

    // Handle file upload
    if ($logo && $logo['tmp_name']) {
        $logoPath = 'uploads/' . basename($logo['name']);
        move_uploaded_file($logo['tmp_name'], $logoPath);
    } else {
        $logoPath = null;
    }

    // Prepare data for API
    $postData = [
        'company_name' => $company_name,
        'username' => $username,
        'gender' => $gender,
        'region' => $region,
        'address' => $address,
        'email' => $email,
        'contact_number' => $contact_number,
        'logo_file' => $logoPath
    ];

    // Convert to JSON
    $jsonPostData = json_encode($postData);

    // cURL to post data to API
    $ch = curl_init('http://127.0.0.1:8000/api/shipping-company'); // Replace with your API endpoint
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonPostData),
        "Authorization: Bearer " .$_SESSION['token']
    ]);

    $response = curl_exec($ch);
    $shipping_company = json_decode($response, 1);
    $password = $shipping_company['default_password'];
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 201) {
        echo json_encode(['status' => 'success', 'message' => "Shipping Company added successfully! The Shipping Company's default credentials have been emailed.<br><br>Username: ".$username."<br>Password: ".$password]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add Shipping Company', 'response' => $response]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>