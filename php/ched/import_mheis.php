<?php
    require '../../vendor_office/autoload.php';
    
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_mhei'])) {
        $file = $_FILES['excel_mhei']['tmp_name'];
        
        try {
            session_start();
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            $drawings = $sheet->getDrawingCollection();

            for ($i = 1; $i < count($data); $i++) {
                $row = $data[$i];
                echo "THIS IS ". $i;

                $logoFile = '';
                foreach ($drawings as $drawing) {
                    if ($drawing instanceof Drawing) {
                        $coordinates = $drawing->getCoordinates();
                        if ($coordinates === 'H' . ($i + 1)) {
                            $tempDir = sys_get_temp_dir();
                            $tempFilePath = $tempDir . DIRECTORY_SEPARATOR . uniqid('logo_', true) . '.' . $drawing->getExtension();
                            file_put_contents($tempFilePath, file_get_contents($drawing->getPath()));
                            $logoFile = $tempFilePath;
                            break;
                        }
                    }
                }
                
                $postData = [
                    'institutional_code' => htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8'),
                    'school_name' => htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8'),
                    'school_type' => strtoupper(htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8')),
                    'region' => htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8'),
                    'address' => htmlspecialchars($row[4], ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($row[5], ENT_QUOTES, 'UTF-8'),
                    'contact_number' => htmlspecialchars($row[6], ENT_QUOTES, 'UTF-8'),
                ];

                $ch = curl_init($_SESSION['default_ip']."/api/mhei");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                $postData['logo_file'] = new CURLFile($logoFile);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer " .$_SESSION['token']
                ]);

                $response = curl_exec($ch);
                echo $response;
                $mhei = json_decode($response, 1);
                $username = $mhei['mhei']['institutional_code'];
                $password = $mhei['default_password'];
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if (file_exists($logoFile)) {
                    unlink($logoFile);
                }
            }

            echo "Data successfully imported into the database!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>
