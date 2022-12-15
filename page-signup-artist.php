<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Profile Setup</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">
  

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- BS Stepper -->
    <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">

  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <link href="plugins/toastr/toastr.css" rel="stylesheet"/>
    <script src="plugins/toastr/toastr.min.js"></script>

</head>

<?php
  include("connection.php");
  session_start();

  $artistID = $_SESSION["artist_id_s"];

  if(isset($_POST["submit"])){
    $expertise = $_POST["expertise"];
    $mainCategory1 = $_POST["mainCategory1"];
    $mainCategory2 = $_POST["mainCategory2"];
    $mainCategory3 = $_POST["mainCategory3"];

    $photographyCat1 = $_POST["photographyCat1"];
    $photographyCat2 = $_POST["photographyCat2"];
    $photographyCat3 = $_POST["photographyCat3"];

    $gadCat1 = $_POST["gadCat1"];
    $gadCat2 = $_POST["gadCat2"];
    $gadCat3 = $_POST["gadCat3"];

    $vaaCat1 = $_POST["vaaCat1"];
    $vaaCat2 = $_POST["vaaCat2"];
    $vaaCat3 = $_POST["vaaCat3"];

    $mainCategoryTotal = $mainCategory1 + $mainCategory2 + $mainCategory3;
    $servicesTotal = $photographyCat1 + $photographyCat2 + $photographyCat3 + $gadCat1 + $gadCat2 + $gadCat3 + $vaaCat1 + $vaaCat2 + $vaaCat3;
    $pts = 0;
    $msg = "";

    if($mainCategoryTotal < 5){
        $pts = 5 - $mainCategoryTotal;
        $msg = "Looks like you haven't use all your scores in Categories. You have $pts point/s left. Try again.";

        echo "<script type='text/javascript'>
                $(document).ready(function(){
                $('#totalErrorModal').modal('toggle');
                });
            </script>";
    }
    elseif($mainCategoryTotal > 5){
        $pts = 5 - $mainCategoryTotal;
        $msg = "Looks like you've exceeded the maximum scores in Categories. You are ".abs($pts)." points over. Try again.";

        echo "<script type='text/javascript'>
                $(document).ready(function(){
                $('#totalErrorModal').modal('toggle');
                });
            </script>";
    }
    elseif($servicesTotal < 15){
        $pts = 15 - $servicesTotal;
        $msg = "Looks like you haven't use all your scores in Services. You have $pts point/s left. Try again.";

        echo "<script type='text/javascript'>
                $(document).ready(function(){
                $('#totalErrorModal').modal('toggle');
                });
            </script>";
    }
    elseif($servicesTotal > 15){
        $pts = 15 - $servicesTotal;
        $msg = "Looks like you've exceeded the maximum scores in Services. You are ".abs($pts)." points over. Try again.";

        echo "<script type='text/javascript'>
                $(document).ready(function(){
                $('#totalErrorModal').modal('toggle');
                });
            </script>";
    }
    else{
        $sql = "UPDATE artist_profile_t SET expertise = '$expertise' WHERE artist_id = '$artistID'";
        $query = mysqli_query($conn, $sql);

        $ncf = ($mainCategory1 + $mainCategory2 + $mainCategory3) / 3;
        $nsf = ($photographyCat1 + $photographyCat3 + $gadCat2 + $vaaCat1 + $vaaCat3) / 5;
        $n = ($ncf * 0.60) + ($nsf * 0.40);

        $sql = "INSERT INTO profile_score_t(artist_id, cf1, cf2, cf3, sf1, sf2, sf3, sf4, sf5, sf6, sf7, sf8, sf9, ncf, nsf, n, update_date) VALUES('$artistID', '$mainCategory1', '$mainCategory2', '$mainCategory3', '$photographyCat1', '$photographyCat2', '$photographyCat3', '$gadCat1', '$gadCat2', '$gadCat3', '$vaaCat1', '$vaaCat2', '$vaaCat3', '$ncf', '$nsf', '$n', NOW())";
        $query = mysqli_query($conn, $sql);

        $_SESSION["idToCluster_s"] = $artistID;

        header("Location: callPython.php");
    }

    
  }

?>

<body class="login-bg" >

        <div class="modal fade" id="totalErrorModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Adjust your scoring!</h4>
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#totalErrorModal').modal('toggle');">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php echo $msg ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#totalErrorModal').modal('toggle');">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container p-0 vh-100 d-flex align-content-center flex-wrap">
            <div class="d-flex align-content-center flex-wrap vw-100 vh-100" style="width:100%; height:100%;">
                <section class="col-lg-6 d-flex align-items-center p-0">
                    <div class="text-white d-none d-md-block">
                        <h1><b>Just steps away!</b></h1>
                        <p style="line-height: 1.25;">To continue with your account creation,<br> please identify your expertise level on <br> various categories and services listed.</p>
                        <p style="line-height: 1.25"><em>Note: </em><br> You have <strong style="color:#f69312">5 points for categories </strong>and <strong style="color:#f69312">15 points for the services</strong>.</p>
                    </div>
                </section>
                <section class="col-lg-6 px-5 py-0 bg-white" style="border-radius:5px;" >
                    <div class="card" style="box-shadow:none;">
                        <div class="bs-stepper">
                            <div class="bs-stepper-header" role="tablist" style="visibility:hidden;" >
                                <div class="step" data-target="#expertise-part" >
                                    <button type="button" class="step-trigger p-0 btn-sm" role="tab" aria-controls="expertise-part" id="expertise-part-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    </button>
                                </div>
                                <div class="step" data-target="#p-part">
                                    <button type="button" class="step-trigger p-0 btn-sm" role="tab" aria-controls="p-part" id="p-part-trigger">
                                    <span class="bs-stepper-circle" style="background-color:orange;">2</span>
                                    </button>
                                </div>
                                <div class="step" data-target="#ga-part">
                                    <button type="button" class="step-trigger p-0 btn-sm" role="tab" aria-controls="ga-part" id="ga-part-trigger">
                                    <span class="bs-stepper-circle" style="background-color:orange;">2</span>
                                    </button>
                                </div>
                                <div class="step" data-target="#va-part">
                                    <button type="button" class="step-trigger p-0 btn-sm" role="tab" aria-controls="va-part" id="va-part-trigger">
                                    <span class="bs-stepper-circle" style="background-color:orange;">2</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content p-0">
                                <form action="page-signup-artist.php" method="post">
                                    <div id="expertise-part" class="content p-0" role="tabpanel" aria-labelledby="expertise-part-trigger" style="margin:auto;">
                                        <div class="">
                                            <h6 class="text-secondary"><b>Step 1 of 4</b></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a style="font-size:xx-large;"><i class="bi bi-file-plus-fill"></i><b>Artist Expertise</b></a>
                                                <p class="text-muted m-0 pb-4"><small>Choose your field of expertise on the categories below.</small></p>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-2 px-0">
                                            <label>What's your expertise?</label>
                                            <select name="expertise" id="expertise" onchange="scoreMain()" class="form-control">
                                                <option class="hidden"  selected disabled></option>
                                                <option value="Photography">Photography</option>
                                                <option value="Graphics and Design">Graphics and Design</option>
                                                <option value="Video and Animation">Video and Animation</option>
                                            </select>
                                        </div>
                                        <label >Rate your skills!</label>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <input type="text" name="category1" class="form-control" value="Photography" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="category2" class="form-control" value="Graphics and Design" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="category3" class="form-control" value="Video and Animation" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <select name="mainCategory1" id="mainCategory1" onchange="scoreMain()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="mainCategory2" id="mainCategory2" onchange="scoreMain()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="mainCategory3" id="mainCategory3" onchange="scoreMain()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="py-5">
                                            <a class="btn disabled" id="proceed1Btn" onclick="stepper.next()" style="background-color:#447271; float:right; color:white;">Next</a>
                                        </div>
                                    </div>
                                    <div id="p-part" class="content" role="tabpanel" aria-labelledby="p-part-trigger" style="margin:auto;">
                                        <div class="">
                                            <h6 class="text-secondary"><b>Step 2 of 4</b></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a style="font-size:x-large;"><i class="bi bi-file-plus-fill"></i><b>Services: Photography</b></a>
                                                <p class="text-muted m-0 pb-4"><small>Rate your skill on the following services given </small></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <input type="text" name="photography1" class="form-control" value="Commercial" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="photgoraphy2" class="form-control" value="Events" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="photography3" class="form-control" value="Sports and Travel" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <select name="photographyCat1" id="photographyCat1" onchange="scorePhotography()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="photographyCat2" id="photographyCat2" onchange="scorePhotography()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="photographyCat3" id="photographyCat3" onchange="scorePhotography()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="py-5">
                                            <a class="btn disabled" id="proceed2Btn" onclick="stepper.next()" style="background-color:#447271; float:right; color:white;">Next</a>
                                        </div>
                                    </div>
                                    <div id="ga-part" class="content" role="tabpanel" aria-labelledby="p-part-trigger" style="margin:auto;">
                                        <div class="">
                                            <h6 class="text-secondary"><b>Step 3 of 4</b></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a style="font-size:x-large;"><i class="bi bi-file-plus-fill"></i><b>Services: Graphics and Design</b></a>
                                                <p class="text-muted m-0 pb-4"><small>Rate your skill on the following services given </small></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <input type="text" name="gad1" class="form-control" value="Art & Illustration" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="gad2" class="form-control" value="Logo & Brand Identity" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="gad3" class="form-control" value="Web & App Design" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <select name="gadCat1" id="gadCat1" onchange="scoreGad()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="gadCat2" id="gadCat2" onchange="scoreGad()"  class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="gadCat3" id="gadCat3" onchange="scoreGad()"  class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="py-5">
                                            <a class="btn" id="proceed3Btn" onclick="stepper.next()" style="background-color:#447271; float:right; color:white;">Next</a>
                                        </div>
                                    </div>
                                    <div id="va-part" class="content" role="tabpanel" aria-labelledby="p-part-trigger" style="margin:auto;">
                                        <div class="">
                                            <h6 class="text-secondary"><b>Step 4 of 4</b></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a style="font-size:x-large;"><i class="bi bi-file-plus-fill"></i><b>Services: Video and Animation</b></a>
                                                <p class="text-muted m-0 pb-4"><small>Rate your skill on the following services given </small></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <input type="text" name="vam1" class="form-control" value="Video Editing" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="vam2" class="form-control" value="Animation" readonly>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" name="vam3" class="form-control" value="Visual Effects" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-2">
                                                    <select name="vaaCat1" id="vaaCat1" onchange="scoreVaa()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="vaaCat2" id="vaaCat2" onchange="scoreVaa()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="vaaCat3" id="vaaCat3" onchange="scoreVaa()" class="form-control" required>
                                                        <option value="0" selected>0 - No Skill</option>
                                                        <option value="1">1 - Beginner</option>
                                                        <option value="2">2 - Middle Level</option>
                                                        <option value="3">3 - Expert</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="py-5">
                                            <button type="submit" name="submit" id="submit" class="btn disabled" style="background-color: #203C3B; color:white; float:right;">Submit</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            <div>
        <div>



  <div id="preloader"></div>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<script>
    var mainCategoryTotal;

    function scoreMain(){
        var mainCategory1 = document.getElementById("mainCategory1").value ? parseInt(document.getElementById("mainCategory1").value) : 0;
        var mainCategory2 = document.getElementById("mainCategory2").value ? parseInt(document.getElementById("mainCategory2").value) : 0;
        var mainCategory3 = document.getElementById("mainCategory3").value ? parseInt(document.getElementById("mainCategory3").value) : 0;
        var e = document.getElementById("proceed1Btn");
        var expertise = document.getElementById("expertise");

        mainCategoryTotal = mainCategory1 + mainCategory2 + mainCategory3;
        var pts = 5 - mainCategoryTotal;

        if(mainCategoryTotal > 5){
            e.classList.add("disabled");
            toastr.error("You're " + (mainCategoryTotal-5) + " points over.");
        }
        else if(mainCategoryTotal == 5 && expertise.value.length > 0){
            e.classList.remove("disabled");
        }
        else{
            e.classList.add("disabled");
            toastr.info(pts + " points remaining.");
        }
    }

    function scorePhotography(){
        var photographyCat1 = document.getElementById("photographyCat1").value ? parseInt(document.getElementById("photographyCat1").value) : 0;
        var photographyCat2 = document.getElementById("photographyCat2").value ? parseInt(document.getElementById("photographyCat2").value) : 0;
        var photographyCat3 = document.getElementById("photographyCat3").value ? parseInt(document.getElementById("photographyCat3").value) : 0;
        var e = document.getElementById("proceed2Btn");

        photographyCategoryTotal = photographyCat1 + photographyCat2 + photographyCat3;
        var pts = 5 - photographyCategoryTotal;

        if(photographyCategoryTotal > 5){
            e.classList.add("disabled");
            toastr.error("You're " + (photographyCategoryTotal-5) + " points over.");
        }
        else if(photographyCategoryTotal == 5){
            e.classList.remove("disabled");
        }
        else{
            e.classList.add("disabled");
            toastr.info(pts + " points remaining.");
        }
    }

    function scoreGad(){
        var gadCat1 = document.getElementById("gadCat1").value ? parseInt(document.getElementById("gadCat1").value) : 0;
        var gadCat2 = document.getElementById("gadCat2").value ? parseInt(document.getElementById("gadCat2").value) : 0;
        var gadCat3 = document.getElementById("gadCat3").value ? parseInt(document.getElementById("gadCat3").value) : 0;
        var e = document.getElementById("proceed3Btn");

        gadCategoryTotal = gadCat1 + gadCat2 + gadCat3;
        var pts = 5 - gadCategoryTotal;

        if(gadCategoryTotal > 5){
            e.classList.add("disabled");
            toastr.error("You're " + (gadCategoryTotal-5) + " points over.");
        }
        else if(gadCategoryTotal == 5){
            e.classList.remove("disabled");
        }
        else{
            e.classList.add("disabled");
            toastr.info(pts + " points remaining.");
        }
    }

    function scoreVaa(){
        var vaaCat1 = document.getElementById("vaaCat1").value ? parseInt(document.getElementById("vaaCat1").value) : 0;
        var vaaCat2 = document.getElementById("vaaCat2").value ? parseInt(document.getElementById("vaaCat2").value) : 0;
        var vaaCat3 = document.getElementById("vaaCat3").value ? parseInt(document.getElementById("vaaCat3").value) : 0;
        var e = document.getElementById("submit");

        vaaCategoryTotal = vaaCat1 + vaaCat2 + vaaCat3;
        var pts = 5 - vaaCategoryTotal;

        if(vaaCategoryTotal > 5){
            e.classList.add("disabled");
            toastr.error("You're " + (vaaCategoryTotal-5) + " points over.");
        }
        else if(vaaCategoryTotal == 5){
            e.classList.remove("disabled");
        }
        else{
            e.classList.add("disabled");
            toastr.info(pts + " points remaining.");
        }
    }
    


</script>



<script>
    $(function () {
      bsCustomFileInput.init();
    });
    </script>
<script>
      // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })
</script>
</body>

</html>