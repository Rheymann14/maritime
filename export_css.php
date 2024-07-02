<?php
require 'vendor_pdf/vendor/autoload.php';
use Dompdf\Dompdf;



$path = 'assets/img/header.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$html = '<div style="text-align:center;  margin-bottom:2%;">
            <img src="' . $base64 . '" alt="Logo" style="max-width:210mm;width:100%;height:auto;">
         </div>';
         
// Letter Content
$html .= '

    <style>
        .spacing-top-2 td {
            padding-top: 2%;
        }
        .spacing-top-1 td {
            padding-top: 1%;
        }
     
        .spacing-top-4 td {
            padding-top: 4%;
        }
        .line-spacing {
            line-height: 2.0; /* Adjust the line height as needed */
        }

        .column {
        float: left;
        width: 50%;
        padding: 10px;
        height: 300px; 
        }
        .row:after {
        content: "";
        display: table;
        clear: both;
        }



        
            
    </style>

    <table style="width:100%;">
        <thead>
            <tr>     
                <th style="width:100%; font-weight:bold; align-text:center;">CERTIFICATE OF SEA SERVICE</th>               
            </tr>  
        </thead><tbody>';

$html .= '<tr class="spacing-top-2">
            <td>Sir / Madam:</td>
          </tr>

          <tr class="spacing-top-1">
            <td class="line-spacing">This certifies that <span style="text-decoration: underline; font-weight:bold;">RHEYMANN A. CUARTOCRUZ</span> had actually served under our Supervisor onboard <span style="font-weight:bold; text-decoration: underline;">Fastcat M15</span>,
            <span style="text-decoration: underline; font-weight:bold;">Passenger</span> of <span style="text-decoration: underline; font-weight:bold;">Philippines</span> with Registry No. 
            <span style="text-decoration: underline; font-weight:bold;">09-0000123</span> vessel of <span style="text-decoration: underline; font-weight:bold;">900</span> KW and 
            <span style="text-decoration: underline; font-weight:bold;">650</span> Gross Tonnage for a period of <span style="text-decoration: underline; font-weight:bold;">1   12   5</span> from 
            <span style="text-decoration: underline; font-weight:bold;">06/25/2024</span> as <span style="text-decoration: underline; font-weight:bold;">Supervisor</span>.</td>
          </tr>';

$html .= '<tr class="spacing-top-1">
           <td class="line-spacing">This further certifies that the above-stated data are true and that any false statement/s made herein shall be ground for criminal prosecution.</td>
          </tr>';

$html .= '<tr class="spacing-top-1">
          <td>Issued this <span style="text-decoration: underline; font-weight:bold;">25th</span> day of <span style="text-decoration: underline; font-weight:bold;">2024</span> at <span style="text-decoration: underline; font-weight:bold;">Zamboanga City</span>.</td>
         </tr>';

    
         
         $html .='<div class="row line-spacing" >
                    <div class="column">
                    <span>_______________________________</span><br>
                    <span>Master - Signature above Printed Name</span> <br>
               <span><span class="label">Nationality :</span> <span class="underline">______________________</span></span> <br>
            <span><span class="label">Kind of License :</span> <span class="underline">_____________________</span></span> <br>
            <span><span class="label">Registration No. :</span> <span class="underline">_____________________</span></span> <br>
            <span><span class="label">Date of Registration :</span> <span class="underline">_________________</span></span> <br>
            <span><span class="label">Expiry Date :</span> <span class="underline">_____________________</span></span>
                    </div>
                    <div class="column">
                    <span style="font-weight:bold;">SEAL OF VESSEL</span>
                    </div>                    
                </div>';
        
         $html .='<span>Note:</span>
                 <div class="row line-spacing" style="margin-top:2%;">
                    <span>This form is good for only one (1) vessel. This form can be reproduced. Any erasure/obliteration may be a ground for denial.</span>
                 </div>';


$html .= '</tbody></table>';

// Initialize Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (force download)
$dompdf->stream("CSS.pdf", array("Attachment" => 1));
?>