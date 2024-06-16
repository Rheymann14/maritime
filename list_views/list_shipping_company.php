<link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
<script src="assets/js/jquery.dataTables.min.js"></script>

<div class="pagetitle">
  <!-- <h1>Shipping Company</h1> -->
  <!-- <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Table</li>
      <li class="breadcrumb-item active">Shipping Company</li>
    </ol>
  </nav> -->
</div><!-- End Page Title -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Shipping Company List</h5>
          <a href="export_users.php" type="button" class="btn btn-primary" ><i class="ri-file-excel-2-line"></i> Export</a>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#import_user"><i class="bx bxs-file-import"></i> Import</button>
          <div class="modal fade" data-bs-backdrop='static' id="import_user" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Import Shipping Company</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="import_users.php" method="POST" enctype="multipart/form-data">  
                  <div class="modal-body">
                    <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Import File</label>
                      <div class="col-sm-10">
                        <input type="file" class="form-control" id="evidence" name="evidence" required>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Import</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="table-responsive" style="margin-top:2%;">
            <table class="hover cell-border" id="mheiTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>logo</th>
                  <th>Shipping Company Name</th>
                  <th>Address</th>
                  <th>Email Address</th>
                  <th>Contact Number</th>
                  <th>Region</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  session_start();
                  $curl = curl_init();
                  curl_setopt_array($curl, [
                      CURLOPT_URL => "http://127.0.0.1:8000/api/shipping-companys",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_CUSTOMREQUEST => "GET",
                      CURLOPT_HTTPHEADER => [
                          "Accept: application/json",
                          "Content-Type: application/json",
                          "Authorization: Bearer " .$_SESSION['token']
                      ]
                  ]);
                
                  $response = curl_exec($curl);
                  $err = curl_error($curl);
                
                  curl_close($curl);
                
                  if ($err) {     
                    displayError($err);
                  } else {
                    $shippingCompanies = json_decode($response, 1);
                    foreach ($shippingCompanies as $i => $shippingCompany) {
                      $i++;
                      echo "<tr>";
                        echo "<td>{$i}</td>";
                        $logo = !empty($shippingCompany['logo']) ? $shippingCompany['logo'] : 'mhei_logo/default.jpg';
                        echo "<td>
                          <a href='{$logo}' target='_blank'>
                              <img src='{$logo}' alt='School Logo' style='width:50px; height:auto;'>
                          </a>
                        </td>   
                        <td>{$shippingCompany['company_name']}</td>
                        <td>{$shippingCompany['address']}</td>
                        <td></td>
                        <td>{$shippingCompany['contact_number']}</td>
                        <td>{$shippingCompany['region']}</td>
                        <td></td>";
                        // $status = $shippingCompany['status'];
                        // if ($status == 'ENABLED') {
                        //   echo "<td><span class='badge rounded-pill bg-success'>{$status}</span></td>";
                        // } elseif ($status == 'DISABLED') {
                        //   echo "<td><span class='badge rounded-pill bg-danger'>{$status}</span></td>";
                        // } else {
                        //   echo "<td><span class='badge rounded-pill bg-secondary'>No Status</span></td>";
                        // }
                        echo
                        "<td align='center' style='text-align: center;'>                  
                          <a href='php/delete_users.php?id=" . $shippingCompany['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a>
                        </td>";
                      echo "</tr>";
                    }
                  }
                ?>   
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    $('#mheiTable').DataTable();
  });
</script>