<link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="./assets/js/sweetaler2.js"></script>
<style>
    /* Inline CSS for loader positioning */
    #loaderOverlay {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        text-align: center;
        align-items: center;
        justify-content: center;
        display: flex;
    }
    .filter-row {
        margin-bottom: 10px;
    }
    .filter-label {
        margin-right: 10px;
        margin-top: 10px;
    }
</style>
<div class="pagetitle">
  <!-- <h1>MHEI</h1> -->
  <!-- <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Table</li>
      <li class="breadcrumb-item active">MHEI</li>
    </ol>
  </nav> -->
</div><!-- End Page Title -->
<section class="section">
  <!-- <div id="loaderOverlay" class="d-flex justify-content-center align-items-center">
      <div class="spinner-border text-primary" role="status"></div>
  </div> -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">MHEI List</h5>
          <div class="d-flex justify-content-between mb-3">
            <div class="d-flex">
              <!-- <a href="export_users.php" type="button" class="btn btn-primary me-2">
                <i class="ri-file-excel-2-line"></i> Export
              </a> -->
              <div class="dropdown">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bxs-file-import"></i> Import
                </button>
                <ul class="dropdown-menu">
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#import_mhei">
                          Upload Excel
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="files/mheis-template.xlsx" download>
                          Download Template
                      </a>
                    </li>
                </ul>
              </div>
            </div>
            <div>
              <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#add_mhei">
                  <i class="bx bxs-plus-circle"></i> Add
              </button>
            </div>
          </div>
          <div class="table-responsive" style="margin-top:2%;">
            <table class="hover cell-border" id="mheiTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>logo</th>
                  <th>MHEI Institutional Code</th>
                  <th>MHEI Name</th>
                  <th>MHEI Type</th>
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
                      CURLOPT_URL => $_SESSION['default_ip']."/api/mheis",
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
                    $mheis = json_decode($response, 1);
                    foreach ($mheis as $i => $mhei) {
                      echo "<tr>";
                        echo "<td></td>";
                        $logo = !empty($mhei['logo']) ? $mhei['logo'] : 'mhei_logo/default.jpg';
                        echo "<td>
                          <a href='{$logo}' target='_blank'>
                              <img src='{$logo}' alt='School Logo' style='width:50px; height:auto;'>
                          </a>
                        </td>
                        <td>{$mhei['institutional_code']}</td>   
                        <td>{$mhei['school_name']}</td>
                        <td>{$mhei['school_type']}</td>
                        <td>{$mhei['address']}</td>
                        <td>{$mhei['email']}</td>
                        <td>{$mhei['contact_number']}</td>
                        <td>{$mhei['region']}</td>";
                        $status = $mhei['status'];
                        if ($status == 'ENABLED') {
                          echo "<td><span class='badge rounded-pill bg-success'>{$status}</span></td>";
                        } elseif ($status == 'DISABLED') {
                          echo "<td><span class='badge rounded-pill bg-danger'>{$status}</span></td>";
                        } else {
                          echo "<td><span class='badge rounded-pill bg-secondary'>No Status</span></td>";
                        }
                        echo
                        "<td align='center' style='text-align: center;'>                  
                          <a href='php/delete_users.php?id=" . $mhei['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a>
                        </td>";
                      echo "</tr>";
                    }
                  }
                ?>   
              </tbody>
            </table>
          </div>
          <div class="modal fade" data-bs-backdrop='static' id="import_mhei" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Import MHEI</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="php/ched/import_mheis.php" method="POST" enctype="multipart/form-data">  
                  <div class="modal-body">
                    <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Import File</label>
                      <div class="col-sm-10">
                        <input type="file" class="form-control" id="excel_mhei" name="excel_mhei" required>
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
          <div class="modal fade" id="add_mhei" tabindex="-1" aria-labelledby="add_mheiLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="add_mheiLabel">Add MHEI</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form id="mheiFormAdd" method="POST" enctype="multipart/form-data">
                      <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="typeAdd" class="form-label">MHEI Type</label>
                            <select class="form-select" id="typeAdd" name="type" required>
                              <option value="public">PUBLIC</option>
                              <option value="private">PRIVATE</option>
                            </select>
                          </div>
                          <div class="col-md-6">
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
                        </div>
                        <div class="mb-3">
                          <label for="institutionalCodeAdd" class="form-label">Institutional Code</label>
                          <input type="text" class="form-control" id="institutionalCodeAdd" name="institutionalCode" required>
                        </div>
                        <div class="mb-3">
                          <label for="nameAdd" class="form-label">MHEI Name</label>
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
                          <label for="contactNumberAdd" class="form-label">Contact Number</label>
                          <input type="text" class="form-control" id="contactNumberAdd" name="contactNumber" required>
                        </div>
                        <div class="mb-3">
                          <label for="logoAdd" class="form-label">Logo</label>
                          <input type="file" class="form-control" id="logoAdd" name="logo">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submitAdd">Add MHEI</button>
                        </div>
                    </form>
                  </div>
              </div>
            </div>
          </div>
          <!-- <div class="d-flex justify-content-center align-items-center">
            <div id="loader" class="spinner-border text-primary" role="status" style="display: none;">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    $('#mheiTable').DataTable({
        "drawCallback": function(settings) {
            var api = this.api();
            x = 0;
            api.rows().every(function(rowIdx, tableLoop, rowLoop) {
              x++;
                // Set the No column with the original row index + 1
                $(api.cell(rowIdx, 0).node()).html(x);
            });
        }
    });
    $('#mheiFormAdd').on('submit', function(event) {
      // $('#loader').show();
      event.preventDefault(); // Prevent the default form submission

      var formData = new FormData(this); // Create a FormData object

      $.ajax({
          url: 'php/ched/add_mhei.php', // Update with the correct URL of your PHP script
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
              $('#add_mhei').modal('hide');
              $('#main').load('list_views/ched/list_mhei.php'); // Reload the page to update the table
              // $('#loader').hide();
            });
          },
          error: function(xhr, status, error) {
              console.log(xhr.responseText); // Log any error response in the console
          }
      });
    });
  });
</script>