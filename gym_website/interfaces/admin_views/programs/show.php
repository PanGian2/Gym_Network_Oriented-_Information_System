<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../../imgs/arm_11878800.png" type="image/x-icon">
    <!-- Google fonts links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../../../styles/yellow_button.css">

</head>

<body>
    <?php require_once("../../partials/authenticateAdmin.php") ?>
    <?php include("../../partials/admin_navbar.php") ?>
    <?php
    require("../../partials/flash_messages.php");
    //Handle the "delete" request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $programId = trim($_POST["programId"]);
        $type = $_POST["type"];
        if ($type == 1) {
            $url = "http://localhost:9999/GymWService/rest/programs/" . $programId;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode == 500) {
                //If status is 500 then it means that there are calendars for this program
                flash("Error", "Δεν μπορείτε να διαγράψετε αυτό το πρόγραμμα καθώς υπάρχουν ημερομηνίες για αυτό. Δοκιμάστε να διαγράψετε πρώτα αυτές τις ημερομηνίες και ύστερα ξαναπροσπαθήστε!", FLASH_ERROR);
                flash("Error");
            } elseif ($httpcode != 200) {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message =   substr($response, strpos($response, "GMT") + 3);
                flash("Error", $message, FLASH_ERROR);
                flash("Error");
            } else {
                //All good
                //The returned message is after the GMT in the initial response
                $message =   substr($response, strpos($response, "GMT") + 3);
                flash("Success", $message, FLASH_SUCCESS);
                $_SESSION["deleteSuccess"] = true;
                echo "<script>location.href = 'index.php';</script>";
            }
        } else {
            //If the program is a group program then the associated trainers must be deleted too from the program_trainer table
            $url = "http://localhost:9999/GymWService/rest/program_trainer/programs/" . $programId;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode == 500) {
                //If status is 500 then it means that there are calendars for this program
                flash("Error", "Δεν μπορείτε να διαγράψετε αυτό το πρόγραμμα καθώς υπάρχουν ημερομηνίες για αυτό. Δοκιμάστε να διαγράψετε πρώτα αυτές τις ημερομηνίες και ύστερα ξαναπροσπαθήστε!", FLASH_ERROR);
                flash("Error");
            } elseif ($httpcode != 200) {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message =   substr($response, strpos($response, "GMT") + 3);
                flash("Error", $message, FLASH_ERROR);
                flash("Error");
            } else {
                $url = "http://localhost:9999/GymWService/rest/programs/" . $programId;
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message =   substr($response, strpos($response, "GMT") + 3);
                    flash("Error", $message, FLASH_ERROR);
                    flash("Error");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message =   substr($response, strpos($response, "GMT") + 3);
                    flash("deleteSuccess", $message, FLASH_SUCCESS);
                    $_SESSION["deleteSuccess"] = true;
                    echo "<script>location.href = 'index.php';</script>";
                }
            }
        }
    }

    //Depending on the occasion, display the appropriate flash messages on top of the page
    if (isset($_SESSION["updateSuccess"]) && $_SESSION["updateSuccess"] == true) {
        flash("updateSuccess");
        $_SESSION["updateSuccess"] = false;
    }
    if (isset($_SESSION["updateError"]) && $_SESSION["updateError"] == true) {
        flash("updateError");
        $_SESSION["updateError"] = false;
    }

    ?>
    <main>
        <?php
        //Get id from the url
        if (isset($_GET["id"])) {
            $programId = $_GET["id"];
        }

        $url = "http://localhost:9999/GymWService/rest/programs/" . $programId;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $data = json_decode($response);
        ?>
        <div class="container-fluid  ">
            <div class="row justify-content-center my-4">
                <img src="<?php echo $data->img_url; ?>" class="img-fluid rounded-start w-50" alt="Image of the program">
            </div>
            <div class="row justify-content-center">
                <div class="card mb-3 " style="width: 65%;">
                    <div class="row">
                        <div class="card-body ">
                            <h1 class="card-title text-center fs-2"><?php echo $data->program_name; ?></h1>
                            <h2 class="fs-3">Τι είναι;</h2>
                            <p class="card-text"><?php echo $data->whatdescription; ?></p>
                            <h2 class="fs-3">Γιατί να το επιλέξεις;</h2>
                            <p class="card-text"><?php echo $data->whydescription; ?></p>
                            <h2 class="fs-3">Tύπος: <?php echo $data->type; ?></h2>
                            <h2 class="fs-3">Διάρκεια: <?php echo $data->duration; ?></h2>
                            <?php
                            if ($data->type == 2) {
                                $url = "http://localhost:9999/GymWService/rest/program_trainer/programs/" . $programId . "/trainers";

                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($curl);
                                $trainers = json_decode($response);
                                echo "<h3 class='fs-4'>Γυμναστής/τρια:</h3>";
                                if ($trainers == null) {
                                    echo "<p class='card-text text-body-secondary'>Δεν έχει οριστεί ακόμα</p>";
                                } else {
                                    echo "<ul>";
                                    foreach ($trainers as $tr) {
                                        echo "<li class='card-text text-body-secondary'>", $tr->name, "</li>";
                                    }
                                    echo "</ul>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-5">
            <a class="btn my-1" href="edit.php?id=<?php echo $programId; ?>" style="background-color: #ffdd00;">Edit</a>
            <form class="d-inline me-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);  ?>" method="POST">
                <input type='hidden' name='programId' value='<?php echo $programId; ?>'>
                <input type='hidden' name='type' value='<?php echo $data->type; ?>'>
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        </div>
        <div class="container text-center">
            <a href="index.php" class="btn btn-success my-4">Επιστροφή στην Διαχείριση Προγρμμάτων</a>
        </div>


    </main>
</body>

</html>