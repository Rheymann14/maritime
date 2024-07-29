<?php
// Include PhpSpreadsheet library
require 'vendor_office/autoload.php';

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'chedro9mobt_db';

// Create a new database connection
$link = mysqli_connect($host, $username, $password, $database);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// session_start();

// if (!isset($_SESSION['username'])) {
//     header("location: ../login");
//     exit();
// }

// $username2 = $link->real_escape_string($_SESSION['username']);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\DataValidationFormula1;

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();





$query_stud = "SELECT * FROM users";

$result_stud = mysqli_query($link, $query_stud);

// Add some data
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Users');

// $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
// $drawing->setPath('img/ucfsap_header.jpg');
// $drawing->setCoordinates('E1');
// $drawing->setWorksheet($spreadsheet->getActiveSheet());
// Set the desired width and height for the image
// $drawing->setWidth(190); // Specify the width in pixels
// $drawing->setHeight(90); // Specify the height in pixels



$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'NAME');
$sheet->setCellValue('C1', 'USERNAME');
$sheet->setCellValue('D1', 'EMAIL');
$sheet->setCellValue('E1', 'STATUS');

// Apply center alignment to variable value cells
$alignmentStyle = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$alignmentStyle2 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

// Apply border styles to the table
$style = [
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
        ],
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
        ],
    ],
];

// Define the fill color
$fillColor = [
    'rgb' => 'FAD1A8'
];

// Apply cell fill color
$fillStyle = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => $fillColor,
    ],
];

// Auto size the column width for columns A to M in rows 7 and below
for ($col = 'A'; $col <= 'E'; $col++) {
    // Auto size column width
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $sheet->getStyle($col)->applyFromArray($alignmentStyle);
}



// Start displaying data from row 7
$rowIndex = 2;

if ($result_stud) {
    while ($row_stud = mysqli_fetch_assoc($result_stud)) {
        // Define the column range for the current row
        $columnRange = 'A' . $rowIndex . ':E' . $rowIndex;

        // Set cell values
        $sheet->setCellValue('A' . $rowIndex, $row_stud['id']);
        $sheet->setCellValue('B' . $rowIndex, $row_stud['name']);
        $sheet->setCellValue('C' . $rowIndex, $row_stud['username']);
        $sheet->setCellValue('D' . $rowIndex, $row_stud['email']);
        $sheet->setCellValue('E' . $rowIndex, $row_stud['status']);

        // Create a Data Validation object
        $validation = $sheet->getCell('E' . $rowIndex)->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"Active,Inactive"');

        // Apply the data validation to the cell
        $sheet->getCell('E' . $rowIndex)->setDataValidation($validation);

        // Apply styles to the current row
        $sheet->getStyle($columnRange)->applyFromArray($alignmentStyle2);
        // $sheet->getStyle($columnRange)->applyFromArray($style);

        // Increment row index for the next student
        $rowIndex++;
  
    }
} else {
    echo "Error executing the query: " . mysqli_error($link);
}

$filename = 'users-list-' . date('Y-m-d-i') . '.xlsx';
// Set header for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$filename");
header('Cache-Control: max-age=0');

// Create Excel writer
$writer = new Xlsx($spreadsheet);

// Save Excel file to output
$writer->save('php://output');

?>
