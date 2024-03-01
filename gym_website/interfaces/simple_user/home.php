<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Link of favicon -->
    <link rel="shortcut icon" href="../../imgs/arm_11878800.png" type="image/x-icon">
    <!-- Goggle font links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../../styles/yellow_button.css">
    <link rel="stylesheet" href="..\..\styles\home.css">


</head>

<body class="d-flex flex-column vh-100">

    <?php include('../partials/navbar.php'); ?>
    <?php
    require_once("../partials/flash_messages.php");
    //Depending on the occasion, display the appropriate flash messages on top of the page
    if (isset($_SESSION["alreadyLoggedIn"]) && $_SESSION["alreadyLoggedIn"] == true) {
        flash("LoggedIn");
        $_SESSION["alreadyLoggedIn"] = false;
    }
    if (isset($_SESSION["notLoggedIn"]) && $_SESSION["notLoggedIn"] == true) {
        flash("notLoggedIn");
        $_SESSION["notLoggedIn"] = false;
    }
    if (isset($_SESSION["notApproved"]) && $_SESSION["notApproved"] == true) {
        flash("notApproved");
        $_SESSION["notApproved"] = false;
    }
    if (isset($_SESSION["unauthorized"]) && $_SESSION["unauthorized"] == true) {
        flash("notAdmin");
        $_SESSION["unauthorized"] = false;
    }
    if (isset($_SESSION["createSuccess"]) && $_SESSION["createSuccess"] == true) {
        flash("createSuccess");
        $_SESSION["createSuccess"] = false;
    }
    if (isset($_SESSION["createError"]) && $_SESSION["createError"] == true) {
        flash("createError");
        $_SESSION["createError"] = false;
    }
    if (isset($_SESSION["updateSuccess"]) && $_SESSION["updateSuccess"] == true) {
        flash("updateSuccess");
        $_SESSION["updateSuccess"] = false;
    }
    if (isset($_SESSION["updateError"]) && $_SESSION["updateError"] == true) {
        flash("updateError");
        $_SESSION["updateError"] = false;
    }
    if (isset($_SESSION["cancel"]) && $_SESSION["cancel"] == true) {
        flash("notCancel");
        $_SESSION["cancel"] = false;
    }
    ?>
    <main>
        <section id="announcement">

            <div id="carouselExampleCaptions" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../../imgs/danielle-cerullo-CQfNt66ttZM-unsplash.jpg" class=" d-block" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class='fs-3'>Μοναδική Εμπειρία</h1>
                            <p>Γυμνάσου τώρα με τα πιο σύγχρονα μηχανήματα στις πιο σύγχρονες εγκαταστάσεις.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../../imgs/sven-mieke-MsCgmHuirDo-unsplash.jpg" class="d-block " alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class='fs-3'>Βρείτε μας στον καινούριο χώρο!</h1>
                            <p>Γυμναστείτε με ασφάλεια και ανακαλύψτε την μαγεία της άσκησης.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../../imgs/bruce-mars-oLStrTTMz2s-unsplash.jpg" class="d-block " alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class='fs-3'>Ανακαλύψτε τη δύναμη της ομαδικής άσκησης!</h1>
                            <p>"Ελάτε σήμερα για να ενισχύσετε την υγεία σας, να ενεργοποιήσετε το κοινωνικό σας δίκτυο και να διασκεδάσετε με την παρέα μας!</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section id="aboutUs" class="container-fluid">
            <div class=" row" id="gradient">
            </div>
            <div class=" row align-items-center py-3" style="background-color: black;">

                <div class=" col-md-6 order-2 order-md-1">
                    <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?q=80&w=1375&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" class="img-fluid">
                </div>

                <div class="col-md-6 text-center order-1 order-md-2" style="color: white;">
                    <div class="row justify-content-center">
                        <div class="col-10 col-lg-8 blurb mb-5 mb-md-0">
                            <h2>Σχετικά με μας</h2>
                            <p class="lead">Στο DS GYM, αφιερωνόμαστε στην προώθηση της υγείας και της ευεξίας μέσω της φυσικής δραστηριότητας.
                                Είμαστε ένα κέντρο που προσφέρει υψηλής ποιότητας προγράμματα γυμναστικής, εξειδικευμένα μαθήματα
                                και φιλικό περιβάλλον για όλους όσους αναζητούν έναν υγιεινό τρόπο ζωής.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="container-fluid">
            <?php
            $url = "http://localhost:9999/GymWService/rest/programs";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $data = json_decode($response);
            //Display only two of our programs
            $x = 0;
            if ($data != null) {
                foreach ($data as $post) {
                    if ($x == 0) {
                        echo "<div class='row align-items-center py-2' style='background-color: black;'>
                        <div class='col-md-6 text-center'>
                            <div class='row justify-content-center'>
                                <div class='col-10 col-lg-8 blurb mb-5 mb-md-0'>
                                <a href='programs.php?id=", $post->pid, "' style='text-decoration:none; color:yellow; font-style: bold; font-size: xx-large;'>", $post->program_name, "</a>
                                    
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <img src='", $post->img_url, "' alt='imagaOfProgram' class='img-fluid'>
                        </div>
                    </div>";
                        $x++;
                    } elseif ($x == 1) {
                        echo "<div class='row align-items-center py-2' style='background-color: black;'>
                        <div class='col-md-6 order-2 order-md-1 style:'color: white;'>
                            <img src='", $post->img_url, "' alt='imageOfProgram' class='img-fluid'>
                        </div>
        
                        <div class='col-md-6 text-center order-1 order-md-2' style='color: black;'>
                            <div class='row justify-content-center'>
                                <div class='col-10 col-lg-8 blurb mb-5 mb-md-0'>
                                <a href='programs.php?id=", $post->pid, "' style='text-decoration:none; color:yellow; font-style: bold; font-size: xx-large;'>", $post->program_name, "</a>
                                </div>
                            </div>
                        </div>
                    </div>";
                        $x++;
                    }
                }
            }
            ?>


        </section>
        <section id="category" class="container-fluid text-center mb-3">
            <div class="row my-3 text-center gx-3 gy-2">
                <h2 class="mb-4">Τελευταία Νέα</h2>
                <?php
                $url = "http://localhost:9999/GymWService/rest/announcements";

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                $data = json_decode($response);
                $x = 0;
                //Display last four news
                if ($data == null) {
                    echo "<p class='text-center'>Δεν υπάρχουν ανακοινώσεις!</p>";
                } else {
                    foreach ($data as $post) {
                        if ($x < 4) {
                            echo "<div class='col-lg-3 col-md-6'>
                                <div class='p-1 border' style='height: 100%'>
                                    <h3>", $post->title, "</h3>
                                    <p>", $post->content, "</p>
                                </div>
                            </div>";
                            $x++;
                        }
                    }
                }
                ?>
            </div>
            <a class="y_button btn" href="announcements.php">ΔΕΙΤΕ ΟΛΑ ΤΑ ΝΕΑ</a>
        </section>
    </main>
    <?php include '../partials/footer.php' ?>
</body>

</html>