<link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="./assets/js/sweetaler2.js"></script>

<?php
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $text = $_GET['text'];
  }
  else {
    $id = 0;
    $text = 'Vessel List';
  }
?>

<div class="pagetitle">
  <!-- <h1>Vessel</h1> -->
  <?php if ($id > 0): ?>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="btn btn-sm back"><i class='ri-arrow-left-line'></i>Back</a></li>
    </ol>
  </nav>
  <?php endif; ?>
</div><!-- End Page Title -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><?php echo json_encode($id)==0?'':htmlspecialchars($text).'\'s '; ?>Vessel List</h5>
          <div class="d-flex justify-content-between">
            <div>
              <a href="export_users.php" type="button" class="btn btn-primary">
                <i class="ri-file-excel-2-line"></i> Export
              </a>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#import_user">
                <i class="bx bxs-file-import"></i> Import
              </button>
            </div>
            <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#add_vessel">
                <i class="bx bxs-plus-circle"></i> Add
            </button>
          </div>
          <div class="table-responsive" style="margin-top:2%;">
            <table class="hover cell-border" id="vesselTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>logo</th>
                  <th>Imo Number/Registry Number</th>
                  <th>Vessel Name</th>
                  <th>Port of Registry</th>
                  <th>Vessel Type</th>
                  <th>Gross Tonage</th>
                  <th>Kilowatt</th>
                  <th>Flag</th>
                  <th>Route</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  session_start();
                  $curl = curl_init();
                  curl_setopt_array($curl, [
                      CURLOPT_URL => "http://127.0.0.1:8000/api/vessels",
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
                    $vessels = json_decode($response, 1);
                    foreach ($vessels as $i => $vessel) {
                      $i++;
                      echo "<tr>";
                        echo "<td>{$i}</td>";
                        $logo = !empty($vessel['image']) ? $vessel['image'] : 'mhei_logo/default.jpg';
                        echo "<td>
                          <a href='{$logo}' target='_blank'>
                              <img src='{$logo}' alt='School Logo' style='width:50px; height:auto;'>
                          </a>
                        </td>";
                        $imo_registry_number = $vessel['imo_number'].($vessel['imo_number'] != '' && $vessel['registry_number'] != ''?'/':'').$vessel['registry_number'];
                        echo"<td>{$imo_registry_number}</td>
                        <td>{$vessel['vessel_name']}</td>
                        <td>{$vessel['port_of_registry']}</td>
                        <td>{$vessel['vessel_type']}</td>
                        <td>{$vessel['grt']}</td>
                        <td>{$vessel['kw']}</td>
                        <td>{$vessel['flag']}</td>
                        <td>{$vessel['route']}</td>";
                        // $status = $vessel['status'];
                        // if ($status == 'ENABLED') {
                        //   echo "<td><span class='badge rounded-pill bg-success'>{$status}</span></td>";
                        // } elseif ($status == 'DISABLED') {
                        //   echo "<td><span class='badge rounded-pill bg-danger'>{$status}</span></td>";
                        // } else {
                        //   echo "<td><span class='badge rounded-pill bg-secondary'>No Status</span></td>";
                        // }
                        echo
                        "<td align='center' style='text-align: center;'>                  
                          <a href='php/delete_users.php?id=" . $vessel['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a>
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
                  <h5 class="modal-title">Import Vessel</h5>
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
          <div class="modal fade" id="add_vessel" tabindex="-1" aria-labelledby="add_vesselLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="add_vesselLabel">Add Vessel</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form id="vesselFormAdd" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                          <label for="imoNumberAdd" class="form-label">IMO Number</label>
                          <input type="text" class="form-control" id="imoNumberAdd" name="imoNumber" required>
                        </div>
                        <div class="mb-3">
                          <label for="registryNumberAdd" class="form-label">Registry Number</label>
                          <input type="text" class="form-control" id="registryNumberAdd" name="registryNumber" required>
                        </div>
                        <div class="mb-3">
                          <label for="nameAdd" class="form-label">Vessel Name</label>
                          <input type="text" class="form-control" id="nameAdd" name="name" required>
                        </div>
                        <div class="mb-3">
                          <label for="registryAdd" class="form-label">Port of Registry</label>
                          <input type="text" class="form-control" id="registryAdd" name="registry" required>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="vesselTypeAdd" class="form-label">Vessel Type</label>
                            <input type="text" class="form-control" id="vesselTypeAdd" name="vesselType" required>
                          </div>
                          <div class="col-md-6">
                            <label for="routeAdd" class="form-label">Route</label>
                            <input type="text" class="form-control" id="routeAdd" name="route" required>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label for="originAdd" class="form-label">Port of Origin</label>
                            <input type="text" class="form-control" id="originAdd" name="origin" required>
                          </div>
                          <div class="col-md-6">
                            <label for="destinationAdd" class="form-label">Port of Destination</label>
                            <input type="text" class="form-control" id="destinationAdd" name="destination" required>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-4">
                            <label for="grtAdd" class="form-label">Gross Tonage</label>
                            <input type="text" class="form-control" id="grtAdd" name="grt" required>
                          </div>
                          <div class="col-md-4">
                            <label for="kwAdd" class="form-label">Kilowatt</label>
                            <input type="text" class="form-control" id="kwAdd" name="kw" required>
                          </div>
                          <div class="col-md-4">
                            <label for="flagAdd" class="form-label">Flag</label>
                            <input type="text" class="form-control" id="flagAdd" name="flag" required>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label for="imageAdd" class="form-label">Image</label>
                          <input type="file" class="form-control" id="imageAdd" name="image">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submitAdd" data-bs-dismiss="modal">Add Vessel</button>
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
    $('#vesselTable').DataTable();
    $(document).on('click', '.back', function(event) {
      $('#main').load('list_views/list_shipping_company.php');
    });
    $('#vesselFormAdd').on('submit', function(event) {
      id = <?php echo json_encode($id); ?>;
      text = <?php echo json_encode($text); ?>;
      event.preventDefault(); // Prevent the default form submission

      var formData = new FormData(this); // Create a FormData object

      if (id > 0) {
        formData.append('shipping_company_id', <?php echo json_encode($id); ?>);
      }

      $.ajax({
          url: 'php/add_vessel.php', // Update with the correct URL of your PHP script
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
              // $('#add_vessel').modal('hide');
              if (id > 0) {
                var GoTourl = 'list_views/list_vessel.php?id=' + id + '&text=' + encodeURIComponent(text);
              }
              else {
                var GoTourl = 'list_views/list_vessel.php';
              }
              $('#main').load(GoTourl);
            });
          },
          error: function(xhr, status, error) {
              console.log(xhr.responseText); // Log any error response in the console
          }
      });
    });
  });
</script>