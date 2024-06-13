<link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
<link href="assets/css/select2.min.css" rel="stylesheet" />
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/select2.min.js"></script>

<div class="pagetitle">
  <h1>Users</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Table</li>
      <li class="breadcrumb-item active">Users</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Create User</h5>
        <!-- Browser Default Validation -->
        <form class="row g-3" action="php/create_user.php" method="post">
          <div class="col-md-4">
            <label for="validationDefault01" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="fullname" id="validationDefault01" placeholder="ex. Juan A. Delacruz" required>
          </div>
          <div class="col-md-4">
            <label for="validationDefault02" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="validationDefault02"  required>
          </div>
          <div class="col-md-4">
            <label for="validationDefaultUsername" class="form-label">Username</label>
            <div class="input-group">
              <span class="input-group-text" id="inputGroupPrepend2">@</span>
              <input type="text" class="form-control" name="username" id="validationDefaultUsername" aria-describedby="inputGroupPrepend2" required>
            </div>
          </div>                
          <div class="col-md-4">
              <label for="validationDefault03" class="form-label">Password</label>
              <div class="input-group">
                  <input type="password" class="form-control" name="password" id="validationDefault03" required>
                  <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                      <span id="togglePasswordIcon" class="bi bi-eye-slash"></span>
                  </button>
              </div>
          </div>
          <div class="col-md-3">
            <label for="validationDefault04" class="form-label">User Role</label>
            <select class="form-select" name="role" id="validationDefault04" required>
              <?php
                include 'includes/connection.php';
                $sql_role = "SELECT id, title FROM roles WHERE status = 'ENABLED' ";
                $result_role = $link->query($sql_role);
                echo '<option value="0" disabled selected>Choose User Role</option>';
                while ($row_role = $result_role->fetch_assoc()) {
                  echo '<option value="' . $row_role["id"] . '">' . $row_role["title"] . '</option>';
                }
                $link->close();
              ?>
            </select>
          </div>
          <div class="col-12">
            <button class="btn btn-primary" type="submit">Create</button>
          </div>
        </form>
        <!-- End Browser Default Validation -->
      </div>
    </div>

      <div class="card">
        <div class="card-body">
        
          <h5 class="card-title">Users List</h5>
          <a href="export_users.php" type="button" class="btn btn-primary" ><i class="ri-file-excel-2-line"></i> Export</a>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#import_user"><i class="bx bxs-file-import"></i> Import</button>
          
          <div class="modal fade" data-bs-backdrop='static' id="import_user" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Import Users</h5>
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
            <table class="hover cell-border" id="userTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>User Role</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include 'includes/connection.php';

                  // Execute a query to retrieve data
                  $query_user = "SELECT *, users.status AS ustatus FROM users INNER JOIN roles ON users.role_id = roles.id";
                  $result_user = mysqli_query($link, $query_user);

                  // Initialize a counter variable
                  $i = 1;

                  // Check if the query was successful
                  if ($result_user) {
                    // Loop through the result set and generate the table rows
                    while ($row = mysqli_fetch_assoc($result_user)) {
                        echo "<tr>";
                        echo "<td>{$i}</td>"; // Use the counter variable
                        echo "<td>{$row['name']}</td>";
                  
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['email']}</td>";
                        
                        echo "<td>{$row['title']}</td>";
                        $status = $row['ustatus'];
                        if ($status == 'ACTIVE') {
                            echo "<td><span class='badge rounded-pill bg-success'>{$status}</span></td>";
                        } elseif ($status == 'INACTIVE') {
                            echo "<td><span class='badge rounded-pill bg-danger'>{$status}</span></td>";
                        } else {
                            echo "<td><span class='badge rounded-pill bg-secondary'>No Status</span></td>";
                        }
                      
                        echo "<td align='center' style='text-align: center;'>                  
                        <a href='php/delete_users.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' data-toggle='tooltip' title='Delete Record' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='ri-delete-bin-2-line'></i></a></td>";

                        echo "</tr>";

                        // Increment the counter variable
                        $i++;
                    }

                    // Free the result set
                    mysqli_free_result($result_user);
                  } else {
                    echo "Error executing the query: " . mysqli_error($link);
                  }

                  // Close the database connection
                  mysqli_close($link);
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
    $('#userTable').DataTable();
    $('#validationDefault04').select2({
      placeholder: "Choose User Role",
      allowClear: false
    });
  });

  document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('validationDefault03');
    const passwordFieldType = passwordField.getAttribute('type');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    if (passwordFieldType === 'password') {
      passwordField.setAttribute('type', 'text');
      togglePasswordIcon.classList.remove('bi-eye-slash');
      togglePasswordIcon.classList.add('bi-eye');
    } else {
      passwordField.setAttribute('type', 'password');
      togglePasswordIcon.classList.remove('bi-eye');
      togglePasswordIcon.classList.add('bi-eye-slash');
    }
  });
</script>

