<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $shipping_company_id = $_POST['shipping_company_id'];
    $imo_number = $_POST['imoNumber'];
    $registry_number = $_POST['registryNumber'];
    $vessel_name = $_POST['name'];
    $port_of_registry = $_POST['registry'];
    $vessel_type = $_POST['vesselType'];
    $route = $_POST['route'];
    $grt = $_POST['grt'];
    $kw = $_POST['kw'];
    $flag = $_POST['flag'];
    $logo = $_POST['logo'];
    $port_origin = $_POST['origin'];
    $port_destination = $_POST['destination'];

    // Handle file upload
    if ($logo && $logo['tmp_name']) {
        $logoPath = 'uploads/' . basename($logo['name']);
        move_uploaded_file($logo['tmp_name'], $logoPath);
    } else {
        $logoPath = null;
    }

    // Prepare data for API
    $postData = [
        'imo_number' => $imo_number,
        'registry_number' => $registry_number,
        'vessel_name' => $vessel_name,
        'port_of_registry' => $port_of_registry,
        'vessel_type' => $vessel_type,
        'route' => $route,
        'grt' => $grt,
        'kw' => $kw,
        'flag' => $flag,
        'logo_file' => $logo,
        'port_origin' => $port_origin,
        'port_destination' => $port_destination,
        'shipping_company_id' => $shipping_company_id,
    ];

    // Convert to JSON
    $jsonPostData = json_encode($postData);

    // cURL to post data to API
    $ch = curl_init('http://127.0.0.1:8000/api/vessel'); // Replace with your API endpoint
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
        echo json_encode(['status' => 'success', 'message' => "Vessel added successfully!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add Vessel', 'response' => $response]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>