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
  <!-- <h1>Student</h1> -->
  <!-- <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Table</li>
      <li class="breadcrumb-item active">Student</li>
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
          <h5 class="card-title">Student List</h5>
          <div class="d-flex justify-content-between">
            <div>
              <a href="export_users.php" type="button" class="btn btn-primary">
                <i class="ri-file-excel-2-line"></i> Export
              </a>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#import_user">
                <i class="bx bxs-file-import"></i> Import
              </button>
            </div>
            <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#add_student">
                <i class="bx bxs-plus-circle"></i> Add
            </button>
          </div>
          <div class="table-responsive" style="margin-top:2%;">
            <table class="hover cell-border" id="pcgStaffTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Student Number</th>
                  <th>Name</th>
                  <th>Gender</th>
                  <th>Course</th>
                  <th>Year CAR</th>
                  <th>Days On Board</th>
                  <th>Days Remaining</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  session_start();
                  $curl = curl_init();
                  curl_setopt_array($curl, [
                      CURLOPT_URL => $_SESSION['default_ip']."/api/students",
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
                    $students = json_decode($response, 1);
                    foreach ($students as $i => $student) {
                      $i++;
                      echo "<tr>";
                        echo "<td>{$i}</td>
                        <td>{$student['student_number']}</td>
                        <td>{$student['user']['name']}</td>
                        <td>{$student['gender']}</td>
                        <td>{$student['maritime_program']['course']}
                        <td>{$student['year_car']}</td>
                        <td>{$student['days_onboard']}
                        <td>{$student['days_remaining']}</td>";
                        $status = $student['status'];
                        if ($status == 'OFFERED') {
                          echo "<td><span class='badge rounded-pill bg-success'>{$status}</span></td>";
                        } elseif ($status == 'NOT OFFERED') {
                          echo "<td><span class='badge rounded-pill bg-danger'>{$status}</span></td>";
                        } else {
                          echo "<td><span class='badge rounded-pill bg-secondary'>No Status</span></td>";
                        }
                        echo
                        "<td align='center' style='text-align: center;'>                  
                          <a href='php/delete_users.php?id=" . $student['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a>
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
                  <h5 class="modal-title">Import Student</h5>
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
          <div class="modal fade" id="add_student" tabindex="-1" aria-labelledby="add_studentLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_studentLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="pcgStaffFormAdd" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="maritime_program_idAdd" class="form-label">Course</label>
                        <select class="form-select" id="maritime_program_idAdd" name="maritime_program_id" required>
                            <?php
                                session_start();
                                $curl = curl_init();
                                curl_setopt_array($curl, [
                                    CURLOPT_URL => $_SESSION['default_ip']."/api/maritime-programs",
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
                                    echo "<option value=''>Error: $err</option>";
                                } else {
                                    $maritimePrograms = json_decode($response, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        if (is_array($maritimePrograms)) {
                                            foreach ($maritimePrograms as $maritimeProgram) {
                                                echo "<option value='{$maritimeProgram['id']}'>{$maritimeProgram['course']}</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Error: Unexpected API response format</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Error: Invalid JSON response</option>";
                                    }
                                }
                            ?>
                          </select>
                      </div>
                      <div class="col-md-6">
                        <label for="genderAdd" class="form-label">Gender</label>
                        <select class="form-select" id="genderAdd" name="gender" required>
                          <option value="MALE">Male</option>
                          <option value="FEMALE">Female</option>
                        </select>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="student_numberAdd" class="form-label">Student Number</label>
                        <input type="text" class="form-control" id="student_numberAdd" name="student_number" required>
                      </div>
                      <div class="col-md-6">
                        <label for="year_carAdd" class="form-label">CAR Achieved Year</label>
                        <input type="text" class="form-control" id="year_carAdd" name="year_car" required>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="nameAdd" class="form-label">Name</label>
                      <input type="text" class="form-control" id="nameAdd" name="name" required>
                    </div>
                    <div class="mb-3">
                      <label for="emailAdd" class="form-label">Email</label>
                      <input type="email" class="form-control" id="emailAdd" name="email" required>
                    </div>
                    <div class="mb-3">
                      <label for="contact_numberAdd" class="form-label">Contact Number</label>
                      <input type="tel" class="form-control" id="contact_numberAdd" name="contact_number" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="submitAdd">Add Student</button>
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
          url: 'php/mhei/add_student.php', // Update with the correct URL of your PHP script
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
              $('#add_student').modal('hide');
              $('#main').load('list_views/mhei/list_students.php'); // Reload the page to update the table
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