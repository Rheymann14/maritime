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
  <!-- <h1>Cadet</h1> -->
  <!-- <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Table</li>
      <li class="breadcrumb-item active">Cadet</li>
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
          <h5 class="card-title">Cadet List</h5>
          <div class="d-flex justify-content-between">
            <div>
              <a href="export_users.php" type="button" class="btn btn-primary">
                <i class="ri-file-excel-2-line"></i> Export
              </a>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#import_user">
                <i class="bx bxs-file-import"></i> Import
              </button>
            </div>
            <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#add_cadet">
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
                  <th>Course</th>
                  <th>MHEI</th>
                  <th>Vessel</th>
                  <th>No. of days onboard</th>
                  <th>Remaining number of days</th>
                  <th>Status
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  session_start();
                  $curl = curl_init();
                  curl_setopt_array($curl, [
                      CURLOPT_URL => $_SESSION['default_ip']."/api/cadets",
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
                    $cadets = json_decode($response, 1);
                    foreach ($cadets as $i => $cadet) {
                      $i++;
                ?>
                      <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $cadet['student']['student_number']?></td>
                        <td><?php echo $cadet['student']['user']['name']?></td>
                        <td><?php echo $cadet['student']['maritime_program']['course']?></td>
                        <td><?php echo $cadet['student']['maritime_program']['mhei']['school_name']?></td>
                        <td><?php echo $cadet['vessel']['vessel_name']?></td>
                        <td><?php echo $cadet['days_onboard']?></td>
                        <td><?php echo $cadet['days_remaining']?></td>
                        <td>ONBOARD</td>
                        <?php
                          echo
                          "<td align='center' style='text-align: center; width: 10%'>                  
                            <a href='php/delete_users.php?id=" . $cadet['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a>";
                        ?>
                        <a class='btn btn-success btn-sm' id='generatePDF' data-toggle='tooltip' title='Generate CSS'
                            data-name="<?php echo $cadet['student']['user']['name']; ?>"
                            data-vessel="<?php echo $cadet['vessel']['vessel_name']; ?>"
                            data-vesselType="<?php echo $cadet['vessel']['type']; ?>"
                            data-registry="<?php echo $cadet['vessel']['registry_number']; ?>"
                            data-kw="<?php echo $cadet['vessel']['kw']; ?>"
                            data-grt="<?php echo $cadet['vessel']['grt']; ?>"
                            data-days="<?php echo $cadet['current_vessel']['days_onboard']; ?>"
                            data-embarkation="<?php echo $cadet['current_vessel']['embarkation_date']; ?>"
                            data-disembarkation="<?php echo $cadet['current_vessel']['disembarkation_date']; ?>"
                        >
                            <i class='ri-file-download-line'></i>
                        </a>
                        </td>
                      </tr>
                <?php 
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
                  <h5 class="modal-title">Import Cadet</h5>
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
          <div class="modal fade" id="add_cadet" tabindex="-1" aria-labelledby="add_cadetLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_cadetLabel">Add Cadet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="pcgStaffFormAdd" method="POST" enctype="multipart/form-data">
                    <div class="d-flex justify-content-start mb-3">
                      <div class="me-3">
                        <label for="courseAdd" class="form-label">Course</label>
                        <input type="text" class="form-control" id="courseAdd" name="course" required>
                      </div>
                      <div class="me-3">
                        <label for="descriptionAdd" class="form-label">Description</label>
                        <input type="text" class="form-control" id="descriptionAdd" name="description" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="submitAdd">Add Cadet</button>
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
    
    $('#generatePDF').click(function(){
        $.ajax({
            url: 'export_css.php',
            type: 'POST',
            data: {
              name: $(this).data('name'),
              vessel: $(this).data('vessel'),
              vesselType: $(this).data('vesselType'),
              registry: $(this).data('registry'),
              kw: $(this).data('kw'),
              grt: $(this).data('grt'),
              days: $(this).data('days'),
              embarkation: $(this).data('embarkation'),
              disembarkation: $(this).data('disembarkation'),
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var blob = new Blob([response], { type: 'application/pdf' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "CSS.pdf";
                link.click();
            },
            error: function(xhr, status, error) {
                console.error("Error: " + status + " - " + error);
            }
        });
    });

    $('#pcgStaffFormAdd').on('submit', function(event) {
      // $('#loader').show();
      event.preventDefault(); // Prevent the default form submission

      var formData = new FormData(this); // Create a FormData object

      $.ajax({
          url: 'php/mhei/add_cadet.php', // Update with the correct URL of your PHP script
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
              $('#add_cadet').modal('hide');
              $('#main').load('list_views/mhei/list_cadets.php'); // Reload the page to update the table
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