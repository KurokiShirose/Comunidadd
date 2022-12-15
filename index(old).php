<?php
  include("connection.php");
  session_start();



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>COMUNIDAD | Connecting Filipino Talents GLobally</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style-landing.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: BizLand - v3.8.1
  * Template URL: https://bootstrapmade.com/bizland-bootstrap-business-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:ComunidadPhilippines@gmail.com">ComunidadPhilippines@gmail.com</a></i>
      </div>
      <div class="social-links d-none d-md-flex align-items-center">
        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
      </div>
    </div>
  </section>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo"><img src="assets/img/logo-white.png" alt=""></a>
      
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#services">Services</a></li>
          <li><a class="nav-link scrollto" href="#about">About</a></li>
          <li><a class="nav-link scrollto" href="#FAQs">FAQs</a></li>
          <!--<li><a class="nav-link scrollto" href="#contact">Contact</a></li>-->
          <?php
            if(isset($_SESSION["username_s"])){
              echo "
                <li><a href='page-browse.php'>
              ";

              $username = $_SESSION["username_s"];
              $sql = "SELECT profile_pic FROM user_profile_t WHERE username = '$username'";
              $query = mysqli_query($conn, $sql);
              $result = mysqli_fetch_array($query);

              if($result[0] != NULL){
                echo "<img src='./assets/img/profile/".$result[0]."' width='40' height='40' class='rounded-circle' style='object-fit:cover;'>";
              }
              else{
                echo "<img src='assets/img/SVG/profile-icon.svg' width='40' height='40' class='rounded-circle' style='object-fit:cover;'>";

              }
              
              echo "</a></li>  "; 
            }
            else{
              echo "<li><a href='page-login.php'>Log-in</a></li>
                      <li>
                        <form action='page-signup.php'>
                          <button type='submit' class='nbtn'>Join</button>
                        </form>
                      </li>";
            }
          ?>
          
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
    <div class="container" data-aos="zoom-out" data-aos-delay="100">
      <a href="index.php"><img src="assets/img/SVG/comunidad.svg" alt=""></a>
      <h1>Connect with Filipino Talents</h1>
      <h2>Hire the best Filipino Artists that suits your needs</h2>
      <form action="page-search.php" method="get">
        <div class="input-group mb-3">
          <input name="search" class="form-control" placeholder="Try Logo Design" aria-label="Search" required>
          <button type="submit" class="btn btn-search-item" type="button">Search</button>
        </div>  
      </form>    
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Community Tag Section ======= -->
    <section id="community-tag" class="community-tag">
      <div class="container" data-aos="fade-up">
        <!--<h2>Community of talents dedicated to <b>CREATIVE EXCELLENCE.</b></h2>-->
      </div>
    </section><!-- End Community Tag Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container" data-aos="fade-up">
      
              <div class="section-title">
                <h2>Services</h2>
                <h3>Check our <span>Categories</span></h3>
                <p>Ut possimus qui ut temporibus culpa velit eveniet modi omnis est adipisci expedita at voluptas atque vitae autem.</p>
              </div>
      
              <div class="row">
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                  <div class="icon-box">
                    <div class="icon"><i class="bx bx-pen"></i></div>
                    <h4><a href="">Graphics & Design</a></h4>
                    <p>Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
                  </div>
                </div>
      
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
                  <div class="icon-box">
                    <div class="icon"><i class="bx bx-camera"></i></div>
                    <h4><a href="">Photography</a></h4>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore</p>
                  </div>
                </div>
      
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
                  <div class="icon-box">
                    <div class="icon"><i class="bx bx-camera-movie"></i></div>
                    <h4><a href="">Video & Animation</a></h4>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia</p>
                  </div>
                </div>      
              </div>
      
            </div>
          </section><!-- End Services Section -->
          <!--
          <div class="section-title">
            <h2>Featured Artists</h2>
            <h3>Check our <span>Filipino Talents</span></h3>
            <p>Ut possimus qui ut temporibus culpa velit eveniet modi omnis est adipisci expedita at voluptas atque vitae autem.</p>
          </div>
          -->
           <!--
        <section id="about" class="testimonials">
            <div class="container" data-aos="zoom-in">

      
              <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
      
                  <div class="swiper-slide">
                    <div class="testimonial-item">
                      <img src="assets/img/darryl-yap.jpg" class="testimonial-img" alt="">
                      <h3>Darryl Yap</h3>
                      <h4>Video and Animation</h4>
                      <p>
                        <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                        Ang inuna kong major ay minor.
                        <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                      </p>
                    </div>
                  </div>
      
                  <div class="swiper-slide">
                    <div class="testimonial-item">
                      <img src="assets/img/kween-yasmin.jpg" class="testimonial-img" alt="">
                      <h3>Kween Yasmin</h3>
                      <h4>Photography</h4>
                      <p>
                        <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                        Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.
                        <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                      </p>
                    </div>
                  </div>
      
                  <div class="swiper-slide">
                    <div class="testimonial-item">
                      <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                      <h3>Maria Clara</h3>
                      <h4>Graphics and Design</h4>
                      <p>
                        <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                        Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.
                        <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                      </p>
                    </div>
                  </div>
      
                  <div class="swiper-slide">
                    <div class="testimonial-item">
                      <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                      <h3>Simoun Dela Cruz</h3>
                      <h4>Video and Animation</h4>
                      <p>
                        <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                        Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.
                        <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                      </p>
                    </div>
                  </div>
      
                  <div class="swiper-slide">
                    <div class="testimonial-item">
                      <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
                      <h3>John Larson</h3>
                      <h4>Entrepreneur</h4>
                      <p>
                        <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                        Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.
                        <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                      </p>
                    </div>
                  </div>
      
                </div>
                <div class="swiper-pagination"></div>
              </div>
      
            </div>
          </section>-->
    
          <!-- ======= About Section ======= -->
 
    <section id="about" class="about section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>About</h2>
          <h3>Find Out<span> More</span></h3>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
            <img src="assets/img/SVG/3d-logo.svg" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <h3>The Community.</h3>
            <ul>
              <li>
                <i class="bx bx-store-alt"></i>
                <div>
                  <h5>The Filipino Artists</h5>
                  <p>Communidad is a community platform dedicated for Filipino Freelance Artists in Photography, Video and Animation, and Graphics and Design with such creative excellence.</p>
                </div>
              </li>
              <li>
                <i class="bx bx-images"></i>
                <div>
                  <h5>The Objective</h5>
                  <p>Communidad will serve as an online directory of Filipino Talents to showcase their portfolio. The main objective of this platform is to provide a centralized place for multimedia 
                    practitioners including all those fresh talents and professionals.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </section> 
    <!-- End About Section -->

    <!-- ======= Team Section ======= -->
    <!--
    <section id="about" class="team section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Founder</h2>
          <h3>The<span>Team</span></h3>
          <p>A four (4) - member team from the Polytechnic University of the Philippines, Bahcelor of Science in Information Technology students.</p>
        </div>

        <div class="row">

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/jmn.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Jericho Mark C. Nieto</h4>
                <span>Project Manager</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/rg.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Raphael E. Gutierrez</h4>
                <span>Back-End Development</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/aah.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Alex Angelo P. Hervosa</h4>
                <span>Front-End Development</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="400">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/sn.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Daphnie Nicole A. Ragudo</h4>
                <span>Documentation</span>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>
    --> 
    <!-- End Team Section -->

    <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="FAQs" class="faq section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>F.A.Qs</h2>
          <h3>Frequently Asked <span>Questions</span></h3>
          <p>Ut possimus qui ut temporibus culpa velit eveniet modi omnis est adipisci expedita at voluptas atque vitae autem.</p>
        </div>

        <div class="row justify-content-center">
          <div class="col-xl-10">
            <ul class="faq-list">

              <li>
                <div data-bs-toggle="collapse" class="collapsed question" href="#faq1">Non consectetur a erat nam at lectus urna duis? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq1" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq2" class="collapsed question">Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq2" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq3" class="collapsed question">Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq3" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq4" class="collapsed question">Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq4" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq5" class="collapsed question">Tempus quam pellentesque nec nam aliquam sem et tortor consequat? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq5" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in
                  </p>
                </div>
              </li>

              <li>
                <div data-bs-toggle="collapse" href="#faq6" class="collapsed question">Tortor vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc eget lorem dolor? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq6" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque. Pellentesque diam volutpat commodo sed egestas egestas fringilla phasellus faucibus. Nibh tellus molestie nunc non blandit massa enim nec.
                  </p>
                </div>
              </li>

            </ul>
          </div>
        </div>

      </div>
    </section><!-- End Frequently Asked Questions Section -->
    <!--
  
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Contact</h2>
          <h3><span>Contact Us</span></h3>
          <p>Ut possimus qui ut temporibus culpa velit eveniet modi omnis est adipisci expedita at voluptas atque vitae autem.</p>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-6">
            <div class="info-box mb-4">
              <i class="bx bx-map"></i>
              <h3>Our Address</h3>
              <p>123 Kalye Anluwage, Manila,Philippines</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class="bx bx-envelope"></i>
              <h3>Email Us</h3>
              <p>ComunidadPhilippines@gmail.com</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class="bx bx-phone-call"></i>
              <h3>Call Us</h3>
              <p>+63 9432 334 323</p>
            </div>
          </div>

        </div>

      </div>
    </section>
          -->
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
<br>
<footer id="footer">

  <div class="footer-top">
    <div class="container">
      <div class="row">

        <div class="col-lg-4 col-md-7 footer-contact">
          <h3>Comunidad<span>.</span></h3>
          <p>
            <strong>Phone:</strong><br>
            <strong>Email:</strong> ComunidadPhilippines@gmail.com<br>
          </p>
        </div>

        <div class="col-lg-4 col-md-7 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy Policy</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of Service</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Community Standards</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Help & Support</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-7 footer-links">
          <h4>Our Categories</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Photography</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Video and Animation</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Graphics and Design</a></li>
          </ul>
        </div>
        <!--
        <div class="col-lg-3 col-md-6 footer-links">
          <h4>Our Social Networks</h4>
          <p>You can follow us on the following:</p>
          <div class="social-links mt-3">
            <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
          </div>
          -->
        </div>

      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="copyright">
      <strong><span>Comunidad.</span></strong>
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/bizland-bootstrap-business-template/ -->
      Inspired from <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </div>
</footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>