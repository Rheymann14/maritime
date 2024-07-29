<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="../assets/js/sweetaler2.js"></script>
    <!-- Favicons -->
    <link href="../assets/img/finallogo.png" rel="icon">
    <link href="../assets/img/finallogo.png" rel="apple-touch-icon">
</head>
<body>
<?php
session_start();
$_SESSION['default_ip'] = "192.168.1.128:8000";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fields = json_encode([
        'username' => $username,
        'password' => $password
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $_SESSION['default_ip']."/api/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => $fields
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {     
        displayError($err);
    } else if ($response == 'Invalid Credentials'){
        displayError($response);
    } else {
        $data = json_decode($response, 1);

        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $data['user']['id'];
        $_SESSION['role_id'] = $data['user']['role']['id'];
        $_SESSION['token'] = $data['token'];

        $user_level = $data['user']['role']['id'];

        header("Location: ../index");
        exit();
    }
}

function displayError($message) {
    echo "<script src='../assets/js/sweetalert2.all.min.js'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: '$message',
            text: 'Please contact your admin',
            showConfirmButton: false,
            timer: 2200
        }).then(function () {
            window.location.href = '../login';
        });
    </script>";
}
?>
</body>
</html>
