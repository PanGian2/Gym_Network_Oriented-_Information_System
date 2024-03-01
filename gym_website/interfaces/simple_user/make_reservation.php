<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Google font links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Styles & Favicon -->
    <link rel="stylesheet" href="../../styles/yellow_button.css">
    <link rel="shortcut icon" href="../../imgs/arm_11878800.png" type="image/x-icon">
    <link rel="stylesheet" href="../../styles/make_reservation.css">

</head>

<body>
    <?php require('../partials/authenticateUser.php'); ?>
    <?php include('../partials/navbar.php') ?>

    <?php
    //Get data based on the current user
    $url = "http://localhost:9999/GymWService/rest/users/" . $_SESSION["id"];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $data = json_decode($response);
    //User has made 2 cancellations, therefore he can't make another reservation and he is being redirected to home page
    if ($data->cancellations >= 2) {
        header('X-PHP-Response-Code: 403', true, 403);
        $_SESSION["cancel"] = true;
        require_once("../partials/flash_messages.php");
        flash("notCancel", "Δεν μπορείτε να κλείσετε καιρνούργιο ραντεβού καθώς έχετε πραγματοποιήσει ήδη δύο ακυρώσεις αυτή την εβδομάδα", FLASH_ERROR);
        header("Location: home.php");
    }
    ?>
    <main>
        <div class="container justify-content-center align-items-center">
            <div class="row px-0">
                <div class="card text-center w-75 mx-auto" style="margin-top: 5%; background-color: #ffe135;">
                    <div class="card-body">
                        <h1 class="card-title" style="margin-top: 5%;"> Κάνε Κράτηση</h1>
                        <form id="regForm" action="../../utils/submitData.php" method="post">
                            <!-- One "tab" for each step in the form: -->
                            <input type="hidden" name="formType" value="bookingCreate">
                            <input type="hidden" id="calendarInput" name="calendarid">
                            <input type="hidden" id="capacity" name="capacity">
                            <input type="hidden" name="userId" value="<?php echo $_SESSION["id"]; ?>">
                            <div class="tab">
                                <h2 style="margin: 3% 0; font-size: x-large;">Πρoγράμματα</h2>
                                <select class="form-select w-50 mx-auto" aria-label="Select Program" name="pid" id="program" required>
                                    <option selected disabled value="">Επιλέξτε Πρόγραμμα</option>
                                    <?php
                                    $url = "http://localhost:9999/GymWService/rest/calendars";

                                    $curl = curl_init($url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                                    $response = curl_exec($curl);
                                    $calendar = json_decode($response);
                                    //If no programs pop an alert
                                    if ($calendar == null) {
                                        echo "</select>";
                                        echo "<script>const noCalendar=true;</script>";
                                        echo '<div id="calendarAlert" class="alert alert-danger alert-dismissible fade show" role="alert""><strong>Δεν υπάρχουν ακόμα διαθέσιμα προγράμματα!</strong></div>';
                                    } else {
                                        $programs = [];
                                        foreach ($calendar as $post) {
                                            //Depending on the calendar call the appropriate endpoint
                                            if ($post->program_pid != null) {
                                                $url = "http://localhost:9999/GymWService/rest/programs/" . $post->program_pid;

                                                $curl = curl_init($url);
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                $response = curl_exec($curl);
                                                $program = json_decode($response);
                                                //If the name of the program is already on the array, don't display it
                                                if (in_array($program->program_name, $programs)) {
                                                    continue;
                                                } else {
                                                    array_push($programs, $program->program_name);
                                                    echo "<option value='", $program->pid, "'>", $program->program_name, "</option>";
                                                }
                                            } elseif ($post->g_program_pid != null) {
                                                $url = "http://localhost:9999/GymWService/rest/programs/" . $post->g_program_pid;

                                                $curl = curl_init($url);
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                $response = curl_exec($curl);
                                                $program = json_decode($response);
                                                //If the name of the program is already on the array, don't display it
                                                if (in_array($program->program_name, $programs)) {
                                                    continue;
                                                } else {
                                                    array_push($programs, $program->program_name);
                                                    echo "<option value='", $program->pid, "'>", $program->program_name, "</option>";
                                                }
                                            }
                                        }
                                        echo "</select>";
                                    }
                                    ?>
                            </div>

                            <div class="tab" id='trainerSelect'>
                                <h2 style="margin-bottom: 3%; font-size: x-large;">Γυμναστής/στρια</h2>

                                <select class='form-select trainer w-50 mx-auto' id='trainer' name='tid' aria-label="Select Trainer" required>
                                    <option disabled selected value=''>Επιλέξτε Γυμναστή/στρια</option>
                                </select>
                                <div id="trainersAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                                    <strong>Δεν υπάρχουν γυμναστές για αυτό το πρόγραμμα!</strong>
                                </div>
                            </div>

                            <div class="tab">
                                <h2 style="margin-bottom: 3%; font-size: x-large;">Πρόγραμμα Προγράμματος</h2>
                                <div class="table-responsive">
                                    <table class="table table-light table-striped table-bordered table-sm mx-auto mb-3 text-center">
                                        <thead>
                                            <tr class="table-dark">
                                                <th scope="col">Ώρες</th>
                                                <th scope="col">Δευτέρα</th>
                                                <th scope="col">Τρίτη</th>
                                                <th scope="col">Τετάρτη</th>
                                                <th scope="col">Πέμπτη</th>
                                                <th scope="col">Παρασκευή</th>
                                                <th scope="col">Σάββατο</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            for ($i = 10; $i < 23; $i++) {
                                                echo "<tr id=", $i, ">";
                                                echo "<td>", $i, ":00</td>";
                                                echo "<td class='Monday'/>";
                                                echo "<td class='Tuesday'/>";
                                                echo "<td class='Wednesday'/>";
                                                echo "<td class='Thursday'/>";
                                                echo "<td class='Friday'/>";
                                                echo "<td class='Saturday'/>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row my-4 gy-2">
                                    <div class="col-lg-6 col-md-12">
                                        <select class="form-select w-75 mx-auto" id="dateSelect" aria-label="Select Day" required>
                                            <option selected disabled value="">Επιλέξτε Ημερομηνία</option>

                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <select class="form-select w-75 mx-auto" id="hourSelect" aria-label="Select Hour" required>
                                            <option selected disabled value="">Επιλέξτε Ώρα</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p>Διαθεσιμότητα: <span id="capacity_val"></span></p>
                                    <div id="capacityAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                                        <strong>Δεν υπάρχουν διαθέσιμες θέσεις για αυτή την μέρα και ώρα! Δοκιμάστε κάποια άλλη ώρα.</strong>
                                    </div>
                                </div>


                            </div>

                            <div style="overflow:auto; margin-top: 4%;">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-dark" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                    <button type="button" class="btn btn-dark" id="nextBtn" onclick="nextPrev(1)">Next</button>
                                </div>
                            </div>

                            <!-- Circles which indicates the steps of the form: -->
                            <div style="text-align:center;margin-top:40px;">
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>

                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>


    </main>
    <script src="../../utils/bookingValidation.js"></script>
    <script>
        //If there are no programs, prevent user from going to the next step
        if (typeof noCalendar !== "undefined") {
            nextBtn = document.getElementById("nextBtn");
            disableButton(nextBtn);
        }

        function disableButton(button) {
            button.setAttribute("disabled", "");
        }
    </script>
</body>

</html>