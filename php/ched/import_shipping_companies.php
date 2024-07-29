<?php
    require '../../vendor_office/autoload.php';
    
    use PhpOffice\PhpSpreadsheet\IOFactory;


    // $host = 'localhost'; // Database host
    // $dbname = 'your_database'; // Database name
    // $user = 'your_username'; // Database username
    // $pass = 'your_password'; // Database password

    // // Connect to the database
    // $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_mhei'])) {
        $file = $_FILES['excel_mhei']['tmp_name'];
        
        try {
            session_start();
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            // Prepare the SQL statement
            // $stmt = $pdo->prepare("INSERT INTO users (name, age, address) VALUES (:name, :age, :address)");

            // Start from row 1 assuming row 0 has headers
            for ($i = 1; $i < count($data); $i++) {
                $row = $data[$i];
                $postData = [
                    'institutional_code' => htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8'),
                    'school_name' => htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8'),
                    'school_type' => strtoupper(htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8')),
                    'region' => htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8'),
                    'address' => htmlspecialchars($row[4], ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($row[5], ENT_QUOTES, 'UTF-8'),
                    'contact_number' => htmlspecialchars($row[6], ENT_QUOTES, 'UTF-8'),
                    'logo_file' => null
                ];

                // Convert to JSON
                $jsonPostData = json_encode($postData);
                echo $jsonPostData."\n\n";

                // cURL to post data to API
                $ch = curl_init($_SESSION['default_ip']."/api/mhei"); // Replace with your API endpoint
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonPostData),
                    "Authorization: Bearer " .$_SESSION['token']
                ]);

                $response = curl_exec($ch);
                echo $response."\n\n";
                $mhei = json_decode($response, 1);
                $username = $mhei['mhei']['institutional_code'];
                $password = $mhei['default_password'];
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                echo $username."\n\n";
                echo $password."\n\n";
            }

            echo "Data successfully imported into the database!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>
