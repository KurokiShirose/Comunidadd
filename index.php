<?php
  include("connection.php");
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>COMUNIDAD | Connecting Filipino Talents</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

   <!-- Style CSS -->
   <link rel="stylesheet" href="css/style-profile.css">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css">
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>



</head>
<body style="background-color: #ffffff;">

    <div class="rt-container">
      <div class="col-rt-12">
        <div class="Scriptcontent">
        <nav class="navbar navbar-expand-sm bg-faded first-nav navbar-light fixed-top px-4 py-2">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a href="index.php"><img src="assets/img/1x/logo-only-black.png" width='40' height='40'  alt=""></a>
          <div class="navbar-collapse collapse px-4" id="navbar1">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
              <li><a class=" px-3" href="#hero"><b>Home</b></a></li>
              <li><a class=" px-3" href="#services"><b>Services</b></a></li>
              <li><a class=" px-3" href="#about"><b>About</b></a></li>
              <li><a class=" px-3" href="#FAQs"><b>FAQs</b></a></li>
              <?php
            if(isset($_SESSION["username_s"])){
              echo "
                <li><a href='page-browse.php' class='px-3'>
              ";

              $username = $_SESSION["username_s"];
              $sql = "SELECT profile_pic FROM user_profile_t WHERE username = '$username'";
              $query = mysqli_query($conn, $sql);
              $result = mysqli_fetch_array($query);

              if($result[0] != NULL){
                echo "<img src='./assets/img/profile/".$result[0]."' width='30' height='30' class='rounded-circle' style='object-fit:cover;'>";
              }
              else{
                echo "<img src='assets/img/SVG/profile-icon.svg' width='30' height='30' class='rounded-circle' style='object-fit:cover;'>";

              }
              
              echo "</a></li>  "; 
            }
            else{
              echo "<li><a href='page-login.php' class='px-3'><b>Log-in</b></a></li>
                      <li class='px-3'>
                        <form action='page-signup.php'>
                          <button type='submit' class='btn save-btn'>Join</button>
                        </form>
                      </li>";
            }
          ?>
            </ul>
          </div>
        </nav>
        </div>
      </div>
    </div>
  </section>

  <div class="d-none d-sm-block">
  <section id="hero" class=" d-flex align-items-center justify-content-center ">
    <div class="container" data-aos="zoom-out" data-aos-delay="50">
      <a href="index.php"><img src="assets/img/SVG/comunidad-text-logo.svg" class="d-none d-sm-block my-2" alt=""></a>
      <h1>Connecting Filipino Talents</h1>
      <h2 class="d-none d-sm-block">Hire the best Filipino Artists that suits your needs</h2>
        <?php if(isset($_SESSION["username_s"])){
          echo "<div class='row d-flex align-items-center justify-content-center'>
                  <a href='page-browse.php'class='btn btn-create'>BROWSE WORKS</a>
                </div>";
        }
        else{
          echo "<form action='page-signup.php'>
                  <div class='row d-flex align-items-center justify-content-center'>
                    <button type='submit' class='btn btn-create'>CREATE ACCOUNT</button>
                  </div>
                </form>";
        }
        ?>
      </div>
  </section>
  </div>

  <div class="d-block d-sm-none">
  <section id="hero" class=" d-flex align-items-start flex-column ">
    <div class="container pt-5" data-aos="zoom-out" data-aos-delay="50">
      <h1 class="pt-5 mt-5">comunidad <span style="color:orange;">.</span></h1>
      <h2 class="">Connecting Filipino Talents</h2>
        <?php if(isset($_SESSION["username_s"])){
          echo "<div class='row d-flex align-items-center justify-content-center'>
                  <a href='page-browse.php'class='btn btn-create'>BROWSE WORKS</a>
                </div>";
        }
        else{
          echo "<form action='page-signup.php'>
                  <div class='row d-flex align-items-center justify-content-center'>
                    <button type='submit' class='btn btn-create'>CREATE ACCOUNT</button>
                  </div>
                </form>";
        }
        ?>
      </div>
  </section>
  </div>

  <section id="services" class="services-bg services">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Services</h2>
        <h3 class="text-white">Check our <span>Categories</span></h3>
        <p class="text-white">Find and hire various artists on our listings</p>
      </div>

      <div class="row">
        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
          <div class="icon-box">
          <i class="bi bi-bezier fs-1"></i>
            <h4><a>Graphics & Design</a></h4>
            <p>Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
          <div class="icon-box">
            <i class="bi bi-camera-fill fs-1"></i>
            <h4><a>Photography</a></h4>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore</p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
          <div class="icon-box">
            <i class="bi bi-camera-reels fs-1"></i>
            <h4><a>Video & Animation</a></h4>
            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia</p>
          </div>
        </div>      
      </div>

    </div>
  </section>
  <section id="about" class="about-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>About</h2>
          <h3>Find Out<span> More</span></h3>
        </div>

        <div class="row">
          <div class="col-lg-12 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">
            
          </div>
        </div>

      </div>
    </section> 

  <section id="FAQs" class="faq section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>F.A.Qs</h2>
          <h3>Frequently Asked <span>Questions</span></h3>
        </div>

        <div class="row justify-content-center">
          <div class="col-xl-10">
            <ul class="faq-list">

              <li>
                <div data-bs-toggle="collapse" class="collapsed question" href="#faq1">I have created an account and I am already a member of Comunidad, am I allowed to create another account that is a different type from what I currently have? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq1" class="collapse" data-bs-parent=".faq-list">
                  <p>
                  Comunidad presents two (2) different account types upon account registration, Artist or Client account. Each account types cater to different sets of people
                   according to what functionalities they may need. In order to avoid account confusion and any data privacy issues and concerns, we limit each user's email to
                    have one (1) account type assigned to it. However, a user may register to another account with the same details as long as the user type is not the same as 
                    the first one.They may choose whichever account type they desire according to what they need. 
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq2" class="collapsed question">I have my Portfolio section built up as an artist, can I offer my uploaded works for commission? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq2" class="collapse" data-bs-parent=".faq-list">
                  <p>
                  There is a Portfolio section on an artist's profile page where they can upload their completed works for clients to see. However, this section is for showcase only and there is no option for opening commission requests for any uploaded works. 
                  The artist and client may communicate with each other if a uploaded work is open for negotiations or not.
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq3" class="collapsed question">Where can I see all my requested commissions? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq3" class="collapse" data-bs-parent=".faq-list">
                  <p>
                  In the user profile page of Artist and Client accounts, there is a Dashboard section which allows the user to see the status and details of any commissions from the past to newly requested commissions subject for approval. 
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq4" class="collapsed question">How can I send/receive payment for my commissioned work? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq4" class="collapse" data-bs-parent=".faq-list">
                  <p>
                  Comunidad doesn't handle any transactions inside the platform but will handle any other payment information between artist and client for easier communication. Both parties may handle payment transactions in whatever method they agreed upon.
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq5" class="collapsed question">What is the difference between a verified and unverified Comunidad account? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq5" class="collapse" data-bs-parent=".faq-list">
                  <p>
                  An unverified account has limitations which are maximum of three (3) work uploads and maximum of five (5) profile visits & unavailable contact details. A verified account doesn't have any limitations.
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq6" class="collapsed question">How can I have a verified account? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq6" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    There are three (3) easy steps to get a verified account:
                      <p class="px-5 mx-5">
                      1. Take a picture of your Identification Card (ID) covering all necessary details.
                      </p>
                      <p class="px-5 mx-5">
                        2. Upload the picture of your Identification Card (ID).
                      </p>
                      <p class="px-5 mx-5">
                      3. Wait until your verification request is processed.
                      </p>
                  </p>
                </div>
              </li>
              <li>
                <div data-bs-toggle="collapse" href="#faq7" class="collapsed question">What are the IDs I can submit to get verified? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq7" class="collapse" data-bs-parent=".faq-list">
                  <p>
                  You may present any ID you have as long as it's legal or recognized by the government and higher authorities. Make sure that the details in the ID are clear and readable as the submission will be subject for approval.
                  <p class="mx-5">
                    <b>Primary Valid IDs</b>
                    <p class="mx-5 px-5">
                    a.	Philippine Passport from Department of Foreign Affairs <br>
                    b.	PhilSys or Nationl ID System <br>
                    c.	SSS ID  <br>
                    d.	GSIS UMID Card <br> 
                    e.	Driver's License <br>
                    f.	PRC ID <br>
                    g.	Voter's ID <br>
                    h.	Persons with Disabilities (PWD) ID <br>
                    i.	NBI Clearance <br>
                    j.	Alien Certification of Registration <br>
                    k.	PhilHealth ID (digitized PVC) <br>
                    l.	Government Office and GOCC ID  <br>
                    m.	School ID (for students) from the current School or University <br>
                    n.	For applicants based overseas, they may use their host government issued IDs showing their Philippine citizenship. (Example: Residence Card) <br>
                    </p>
                  </p>
                  <p class="mx-5">
                    <b>Secondary Valid ID</b>
                    <p class="px-5 mx-5">
                    a.	TIN ID <br>
                    b.	Postal ID (issued 2015 onwards) <br>
                    c.	Barangay Certification <br>
                    d.	Government Service Insurance System (GSIS) e-Card <br>
                    e.	Police Clearance <br>
                    f.	Barangay Clearance <br>
                    g.	Cedula or Community Tax Certificate <br>
                    h.	Government Service Record <br>

                    </p>
                  </p>
                  </p>
                </div>
              </li>

            </ul>
          </div>
        </div>

      </div>
  </section>

  <!-- ======= Footer ======= -->
  <footer class="d-none d-sm-block footer-custom fixed-bottom" style="height:35px; padding:0px 10px;">
    <div class="container py-2 ">
        <div class="row ">
            <div class="col-4">
                <strong><span>Comunidad</span></strong>
            </div>
            <div class="col-2 footer-link-float">
                <p>Help and Support</p>
            </div>
            <div class="col-2 footer-link-float">
                <p>Terms of Service</p>
            </div>
            <div class="col-2 footer-link-float">
                <a href="privacy-policy.html">Privacy Policy</a>
            </div>
            <div class="col-2 footer-link-float">
                <p>Community Standards</p>
            </div>
        </div>
    </div>
  </footer>
  


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>   
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>