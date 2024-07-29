<?php
session_start();

if(!isset($_SESSION['username'])) {
    header("location: login");
    exit();
}

  $curl = curl_init();
  curl_setopt_array($curl, [
      CURLOPT_URL => $_SESSION['default_ip']."/api/user/".$_SESSION['user_id'],
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
      $data = json_decode($response, 1);
      $username = $data["username"];
      $email = $data["email"];
      $title = $data["role"]['title'];
      $user_level = $data["role"]['id'];
  }


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>OBTIMCS</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/finallogo.png" rel="icon">
  <link href="assets/img/finallogo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  
  <script src="assets/js/jquery-3.6.0.min.js"></script>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index" class="logo d-flex align-items-center">
        <img src="assets/img/finallogo.png" alt="">
        <span class="d-none d-lg-block">OBTIMCS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <!-- <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a> -->
          <!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li> 

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <!-- <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a> -->
          <!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/finallogo.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?=$username?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?=$username?></h6>
              <span><?=$title?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
                <a class="dropdown-item d-flex align-items-center" id="profile-link" style="cursor:pointer;">
                    <i class="bi bi-person"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <!-- <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li> -->

            <!-- <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li> -->
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="php/logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <?php if ($user_level == 1): ?>
        <li class="nav-item">
          <a class="nav-link"  id="dashboard-link" href="index">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
          </a>
        </li><!-- End Dashboard Nav -->


        <li class="nav-item">
          <a class="nav-link collapsed" id="users-link" style="cursor:pointer;">
            <i class="bi bi-person"></i>
            <span>Users</span>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link collapsed" id="users-link" style="cursor:pointer;">
            <i class="bi bi-file-pdf"></i>
            <span>SO Requests</span>
          </a>
        </li> -->
      <?php elseif ($user_level == 2): ?>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" id= "management-link">
            <i class="bi bi-menu-button-wide"></i><span>Management</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a id="maritime-programs-link" style="cursor:pointer;" class="">
                <i class="bi bi-circle"></i><span>Maritime Programs</span>
              </a>
            </li>
            <li>
              <a id="students-link" style="cursor:pointer;" class="">
                <i class="bi bi-circle"></i><span>Students</span>
              </a>
            </li>
            <li>
              <a id="ots-link" style="cursor:pointer;" class="">
                <i class="bi bi-circle"></i><span>On-Board Training Supervisors</span>
              </a>
            </li>
          </ul>
        </li><!-- End Components Nav -->
      <?php elseif ($user_level == 3): ?>
        <li class="nav-item">
          <a id="mhei-link" style="cursor:pointer;" class="nav-link">
            <i class="bi bi-buildings"></i><span>Maritime Higher Education Institutions</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" id= "management-link">
            <i class="bi bi-menu-button-wide"></i><span>MARINA Accreditted</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a id="shipping-company-link" style="cursor:pointer;" class="">
                <i class="bi bi-circle"></i><span>Shipping Companies</span>
              </a>
            </li>
            <li>
              <a id="vessel-link" style="cursor:pointer;" class="">
                <i class="bi bi-circle"></i><span>Vessels</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a id="pcg-staff-link" style="cursor:pointer;" class="nav-link collapsed">
            <i class="bi bi-people"></i><span>Philippine Coast Guard Accounts</span>
          </a>
        </li>
        <li class="nav-item">
          <a id="pcg-staff-link" style="cursor:pointer;" class="nav-link collapsed">
            <i class="bi bi-map"></i><span>Vessel Navigation</span>
          </a>
        </li>
      <!-- </li>End Components Nav -->
      <?php elseif ($user_level == 4): ?>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" id= "management-link">
            <i class="bi bi-menu-button-wide"></i><span>Management</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a id="vessel-link" style="cursor:pointer;" class="">
                <i class="bi bi-circle"></i><span>Vessels</span>
              </a>
            </li>
            <li>
              <a id="cadets-link" style="cursor:pointer;" class="">
                <i class="bi bi-circle"></i><span>Cadets</span>
              </a>
            </li>
      
    
          </ul>
        </li><!-- End Components Nav -->
      <?php endif; ?>

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

 
  <div class="d-flex justify-content-center align-items-center">
    <div id="loader" class="spinner-border text-primary" role="status" style="display: none;">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'includes/footer.php' ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script>
    function updateUI() {
        $('#loader').hide();
        $('#dashboard-link').addClass('collapsed');
        $('#users-link').addClass('collapsed');
        $('#mhei-link').addClass('collapsed');
        $('#shipping-company-link').removeClass('active'); 
        $('#vessel-link').removeClass('active'); 
        $('#pcg-staff-link').addClass('collapsed');
        $('#maritime-programs-link').removeClass('active'); 
        $('#students-link').removeClass('active'); 
        $('#ots-link').removeClass('active'); 
        $('#cadets-link').removeClass('active'); 
    }
    
    $(document).ready(function() {
        // Check if 'load' parameter exists in the URL
        var urlParams = new URLSearchParams(window.location.search);
        var loadPage = urlParams.get('load');

        $('#loader').show();
        $('#main').load('list_views/ched/list_mhei.php', function() {
          updateUI();
          $('#mhei-link').removeClass('collapsed');
        });

        if (loadPage) {
            $('#loader').show();
            $('#main').load(loadPage + '.php', function() {
                $('#loader').hide();
                if (loadPage === 'users') {
                  $('#dashboard-link').addClass('collapsed');
                  $('#users-link').removeClass('collapsed');
                  $('#management-link').removeClass('collapsed');
                }
            });
        }

        $('#profile-link').click(function(event) {
            event.preventDefault(); // Prevent the default link behavior
            $('#loader').show();
            $('#main').load('profile.php', function() {
                $('#loader').hide();
            });
        });

        $('#dashboard-link').click(function(event) {
            updateUI();
            if ($('#components-nav').hasClass('show')) {
              $('#management-link').removeClass('collapsed');
              $('#components-nav').collapse('hide'); // Collapse if it's currently shown
            }
            $('#dasboard-link').removeClass('collapsed');
        });

        $('#users-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('users.php', function() {
              updateUI();
              if ($('#components-nav').hasClass('show')) {
                $('#management-link').removeClass('collapsed');
                $('#components-nav').collapse('hide'); // Collapse if it's currently shown
              }
              $('#users-link').removeClass('collapsed');
            });
        });

        $('#management-link').click(function(event) {
            updateUI();
            if ($('#components-nav').hasClass('show')) {
              $('#management-link').removeClass('collapsed');
              $('#components-nav').collapse('hide'); // Collapse if it's currently shown
            }
        });

        $('#mhei-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/ched/list_mhei.php', function() {
              updateUI();
              if ($('#components-nav').hasClass('show')) {
                $('#management-link').removeClass('collapsed');
                $('#components-nav').collapse('hide'); // Collapse if it's currently shown
              }
              $('#mhei-link').removeClass('collapsed');
            });
        });
        $('#shipping-company-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/ched/list_shipping_company.php', function() {
              updateUI();
              $('#shipping-company-link').addClass('active'); 
            });
        });
        $('#vessel-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/ched/list_vessel.php', function() {
              updateUI();
              $('#vessel-link').addClass('active'); 
            });
        });
        $('#pcg-staff-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/ched/list_pcg_staff.php', function() {
              updateUI();
              if ($('#components-nav').hasClass('show')) {
                $('#management-link').removeClass('collapsed');
                $('#components-nav').collapse('hide'); // Collapse if it's currently shown
              }
              $('#pcg-staff-link').removeClass('collapsed');
            });
        });
        $('#maritime-programs-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/mhei/list_maritime_programs.php', function() {
              updateUI();
              $('#maritime-programs-link').addClass('active'); 
            });
        });
        $('#students-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/mhei/list_students.php', function() {
              updateUI();
              $('#students-link').addClass('active'); 
            });
        });
        $('#ots-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/mhei/list_pcg_staff.php', function() {
              updateUI();
              $('#ots-link').addClass('active'); 
            });
        });
        $('#cadets-link').click(function(event) {
            event.preventDefault();
            $('#loader').show();
            $('#main').load('list_views/shipping_company/list_cadets.php', function() {
              updateUI();
              $('#cadets-link').addClass('active'); 
            });
        });
    });
  </script>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>