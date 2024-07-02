<link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="./assets/js/sweetaler2.js"></script>

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
          <div class="d-flex justify-content-between">
            <div>
              <a href="export_users.php" type="button" class="btn btn-primary">
                <i class="ri-file-excel-2-line"></i> Export
              </a>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#import_user">
                <i class="bx bxs-file-import"></i> Import
              </button>
            </div>
            <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#add_shipping_company">
                <i class="bx bxs-plus-circle"></i> Add
            </button>
          </div>
          <div class="table-responsive" style="margin-top:2%;">
            <table class="hover cell-border" id="shippingCompanyTable">
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
                        "<td align='center' style='text-align: center; width: 10%'>                  
                          <a href='php/delete_users.php?id=" . $shippingCompany['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a>
                          <a class='btn btn-success btn-sm loadVessel' data-id=" . $shippingCompany['id'] . " data-text=" . $shippingCompany['company_name'] . " data-toggle='tooltip' title='Delete Record'>
                              <i class='ri-arrow-right-line'></i>
                          </a>
                        </td>";
                      echo "</tr>";
                    }
                  }
                ?>   
              </tbody>
            </table>
          </div>
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
          <div class="modal fade" id="add_shipping_company" tabindex="-1" aria-labelledby="add_shipping_companyLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="add_shipping_companyLabel">Add Shipping Company</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form id="shippingCompanyFormAdd" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                          <label for="regionAdd" class="form-label">Region</label>
                          <select class="form-select" id="regionAdd" name="region" required></select>
                          <script>
                              fetch('regions.json')
                                  .then(response => response.json())
                                  .then(data => {
                                      const regionSelect = document.getElementById('regionAdd');
                                      data.forEach(region => {
                                          const option = document.createElement('option');
                                          option.value = region.value;
                                          option.textContent = region.name;
                                          regionSelect.appendChild(option);
                                      });
                                  })
                                  .catch(error => console.error('Error fetching regions:', error));
                          </script>
                        </div>
                        <div class="mb-3">
                          <label for="nameAdd" class="form-label">Shipping Company Name</label>
                          <input type="text" class="form-control" id="nameAdd" name="name" required>
                        </div>
                        <div class="mb-3">
                          <label for="addressAdd" class="form-label">Address</label>
                          <input type="text" class="form-control" id="addressAdd" name="address" required>
                        </div>
                        <div class="mb-3">
                          <label for="emailAddressAdd" class="form-label">Email Address</label>
                          <input type="text" class="form-control" id="emailAddressAdd" name="emailAddress" required>
                        </div>
                        <div class="mb-3">
                          <label for="usernameAdd" class="form-label">Username</label>
                          <input type="text" class="form-control" id="usernameAdd" name="username" required>
                        </div>
                        <div class="mb-3">
                          <label for="contactNumberAdd" class="form-label">Contact Number</label>
                          <input type="text" class="form-control" id="contactNumberAdd" name="contactNumber" required>
                        </div>
                        <div class="mb-3">
                          <label for="logoAdd" class="form-label">Logo</label>
                          <input type="file" class="form-control" id="logoAdd" name="logo">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submitAdd">Add Shipping Company</button>
                        </div>
                    </form>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    $('#shippingCompanyTable').DataTable();
    $(document).on('click', '.loadVessel', function(event) {
      var id = $(this).data('id');
      var text = $(this).data('text');
      var url = 'list_views/ched/list_vessel.php?id=' + id + '&text=' + encodeURIComponent(text);
      // $('#main').load('list_views/ched/list_vessel.php');
      // $('#main').load('list_views/ched/list_vessel.php?id=' + id + '&text=' + encodeURIComponent(text));
      $('#main').load(url);
    });
    $('#shippingCompanyFormAdd').on('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission

      var formData = new FormData(this); // Create a FormData object

      $.ajax({
          url: 'php/ched/add_shipping_company.php', // Update with the correct URL of your PHP script
          type: 'POST',
          data: formData,
          processData: false, // Prevent jQuery from processing the data
          contentType: false, // Prevent jQuery from setting the content type
          success: function(response) {
            console.log(response); // Log the response in the console
            content = JSON.parse(response);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                html: content['message'],
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
              $('#add_shipping_company').modal('hide');
              $('#main').load('list_views/ched/list_shipping_company.php');
            });
          },
          error: function(xhr, status, error) {
              console.log(xhr.responseText); // Log any error response in the console
          }
      });
    });
  });
</script>