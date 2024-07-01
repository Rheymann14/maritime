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
</style>
<div class="pagetitle">
  <!-- <h1>PCG STAFF</h1> -->
  <!-- <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Table</li>
      <li class="breadcrumb-item active">PCG STAFF</li>
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
          <h5 class="card-title">PCG STAFF List</h5>
          <div class="d-flex justify-content-between">
            <div>
              <a href="export_users.php" type="button" class="btn btn-primary">
                <i class="ri-file-excel-2-line"></i> Export
              </a>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#import_user">
                <i class="bx bxs-file-import"></i> Import
              </button>
            </div>
            <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#add_pcg_staff">
                <i class="bx bxs-plus-circle"></i> Add
            </button>
          </div>
          <div class="table-responsive" style="margin-top:2%;">
            <table class="hover cell-border" id="pcgStaffTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>PCG STAFF Name</th>
                  <th>Gender</th>
                  <th>Email Address</th>
                  <th>Username</th>
                  <th>Rank</th>
                  <th>Unit Assigned</th>
                  <th>Unit Address</th>
                  <th>Contact Number</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  session_start();
                  $curl = curl_init();
                  curl_setopt_array($curl, [
                      CURLOPT_URL => "http://127.0.0.1:8000/api/pcg-staffs",
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
                    $pcg_staffs = json_decode($response, 1);
                    foreach ($pcg_staffs as $i => $pcg_staff) {
                      $i++;
                      echo "<tr>";
                        echo "<td>{$i}</td>
                        <td>{$pcg_staff['user']['name']}</td>
                        <td>MALE</td>
                        <td>{$pcg_staff['user']['email']}</td>
                        <td>{$pcg_staff['user']['username']}</td>
                        <td>{$pcg_staff['rank']}</td>
                        <td>{$pcg_staff['unit_assigned']}</td>
                        <td>{$pcg_staff['unit_address']}</td>
                        <td>{$pcg_staff['contact_number']}</td>";
                        $status = $pcg_staff['user']['status'];
                        if ($status == 'ACTIVE') {
                          echo "<td><span class='badge rounded-pill bg-success'>{$status}</span></td>";
                        } elseif ($status == 'INACTIVE') {
                          echo "<td><span class='badge rounded-pill bg-danger'>{$status}</span></td>";
                        } else {
                          echo "<td><span class='badge rounded-pill bg-secondary'>No Status</span></td>";
                        }
                        echo
                        "<td align='center' style='text-align: center;'>                  
                          <a href='php/delete_users.php?id=" . $pcg_staff['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a>
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
                  <h5 class="modal-title">Import PCG STAFF</h5>
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
          <div class="modal fade" id="add_pcg_staff" tabindex="-1" aria-labelledby="add_pcg_staffLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_pcg_staffLabel">Add PCG STAFF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="pcgStaffFormAdd" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                      <div class="d-flex justify-content-start mb-3">
                        <div class="me-3">
                          <label class="form-label">Gender: </label>
                        </div>
                        <div class="me-3">
                            <input type="radio" id="genderMale" name="gender" value="MALE" required>
                            <label for="genderMale">Male</label>
                        </div>
                        <div class="me-3">
                            <input type="radio" id="genderFemale" name="gender" value="FEMALE" required>
                            <label for="genderFemale">Female</label>
                        </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="nameAdd" class="form-label">PCG STAFF Name</label>
                      <input type="text" class="form-control" id="nameAdd" name="name" required>
                    </div>
                    <div class="mb-3">
                      <label for="emailAddressAdd" class="form-label">Email Address</label>
                      <input type="email" class="form-control" id="emailAddressAdd" name="emailAddress" required>
                    </div>
                    <div class="mb-3">
                      <label for="usernameAdd" class="form-label">Username</label>
                      <input type="text" class="form-control" id="usernameAdd" name="username" required>
                    </div>
                    <div class="mb-3">
                      <label for="rankAdd" class="form-label">Rank</label>
                      <input type="text" class="form-control" id="rankAdd" name="rank" required>
                    </div>
                    <div class="mb-3">
                      <label for="unitAssignedAdd" class="form-label">Unit Assigned</label>
                      <input type="text" class="form-control" id="unitAssignedAdd" name="unitAssigned" required>
                    </div>
                    <div class="mb-3">
                      <label for="unitAddressAdd" class="form-label">Unit Address</label>
                      <input type="text" class="form-control" id="unitAddressAdd" name="unitAddress" required>
                    </div>
                    <div class="mb-3">
                      <label for="contactNumberAdd" class="form-label">Contact Number</label>
                      <input type="text" class="form-control" id="contactNumberAdd" name="contactNumber" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="submitAdd">Add PCG STAFF</button>
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
    $('#pcgStaffTable').DataTable();
    $('#pcgStaffFormAdd').on('submit', function(event) {
      // $('#loader').show();
      event.preventDefault(); // Prevent the default form submission

      var formData = new FormData(this); // Create a FormData object

      $.ajax({
          url: 'php/add_pcg_staff.php', // Update with the correct URL of your PHP script
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
              // $('#add_pcg_staff').modal('hide');
              $('#main').load('list_views/list_pcg_staff.php'); // Reload the page to update the table
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