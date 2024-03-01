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
    <main>
        <div class="row gx-0 my-3">
            <h1 class="text-center mb-4">Δημιουργία Ημερομηνίας για Προγράμμα</h1>
            <div class="col-6 offset-3">
                <form action="../../../utils/submitData.php" method="POST">
                    <input type="hidden" name="formType" value="calendarCreate">
                    <div class='mb-4'>
                        <p>Τα προγράμματα έχουν μηνιαία ισχύ. Με βάση την ημέρα και ημερομηνία που θα επιλέξετε, το πρόγραμμα θα παραγματοποιείται την μέρα που επιλέξατε για έναν μήνα.
                            Συγκεκριμένα για να δημιουργήσετε ημερομηνία για πρόγραμμα θα πρέπει: </p>
                        <ul style="list-style: decimal;">
                            <li>να επιλέξετε το πρόγραμμα που επιθμείτε. Σε περίπτωση που είναι ομαδικό, πρέπει να επιλέξετε και γυμναστή.</li>
                            <li>να επιλέξετε ποιά μέρα θα διεξάγεται το πρόγραμμα αυτό. </li>
                            <li>να επιλέξετε την ημερομηνία απο την οποία το πρόγραμμα θα είναι σε ισχύ.</li>
                            <li>να επιλέξετε την ώρα διεξαγωγής του προγράμματος (από τις 10:00 - 22:00).</li>
                            <li>να καταχωρήσεις τον αριθμό χωρητικότητας. </li>
                            <li>να αποθήκευστε την ημερομηνία ώστε να βγει το μηνιαίο πρόγραμμα. </li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="program">Πρόγραμμα</label>
                        <?php

                        $url = "http://localhost:9999/GymWService/rest/programs";

                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);
                        $program = json_decode($response);

                        if ($program == null) {
                            echo "<script>const x = true;</script>";
                            echo '<div id="programAlert" class="alert alert-danger alert-dismissible fade show" role="alert""><strong>Δεν υπάρχουν προγράμματα στο σύστημα!</strong></div>';
                        } else {
                            echo "<select class='form-select' id='program' name='program_pid' onchange='getOption();' required >";
                            echo "<option selected disabled value=''>Επιλέξτε Πρόγραμμα</option>";
                            foreach ($program as $post) {
                                echo "<option value='", $post->pid, "'>", $post->program_name, "</option>";
                            }
                            echo "</select>";
                        }

                        ?>
                    </div>
                    <div id='trainerSelect' class="mb-3" style="display:none;">
                        <label class='form-label' for='trainer'>Γυμναστής/στρια</label>
                        <select class='form-select trainer' id='trainer' name='tid' required>
                            <option disabled selected value=''>Επιλέξτε Γυμναστή/στρια</option>
                        </select>
                        <div id="trainersAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                            <strong>Δεν υπάρχουν γυμναστές για αυτό το πρόγραμμα!</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="day">Ημέρα Προγράμματος</label>
                        <select class="form-select" id="day" name="day" required>
                            <option selected disabled>Επιλέξτε Μέρα</option>
                            <option value="Monday">Δευτέρα</option>
                            <option value="Tuesday">Τρίτη</option>
                            <option value="Wednesday">Τετάρτη</option>
                            <option value="Thursday">Πέμπτη</option>
                            <option value="Friday">Παρασκευή</option>
                            <option value="Saturday">Σάββατο</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="date">Ημερομηνία Έναρξης Μηνιαίου Προγράμματος </label>
                        <input type="date" class="form-control" id="date" aria-label="date" aria-describedby="date-label" name="date" min="<?php echo date('Y-m-d') ?>" required />

                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="time">Ώρα</label>
                        <input class="form-control" type="time" id="time" name="hour" min="10:00" max="22:00" required />
                    </div>
                    <div class=" mb-3">
                        <label class="form-label" for="capacity">Χωρητικότητα</label>
                        <input class="form-control" type="number" id="capacity" name="capacity" required />
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-info" id="submitButton" type="submit">Αποθήκευση Ημερομηνίας</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container text-center">
            <a href="index.php" class="btn btn-success my-4">Επιστροφή στην Διαχείριση Ημερομηνίων</a>
        </div>

    </main>
    <script>
        const alert = document.getElementById("programAlert");

        //If no programs disable the button
        if (x == true) {
            disableButton();
        }

        //Function that disables the submit button
        function disableButton() {
            const button = document.getElementById("submitButton");
            button.setAttribute("disabled", "");
        }

        //Function that gets the option from the program select
        function getOption() {
            const button = document.getElementById("submitButton");
            const trainerAlert = document.getElementById("trainersAlert");
            const program = document.getElementById("program").value;
            const trainers = document.getElementById("trainerSelect");
            const select = document.querySelectorAll(".trainer");

            // Send an AJAX request to fetch info for the selected program
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../../../utils/get_info.php?program=" + program, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.response;
                    if (response == 2) {
                        //The program is a group program
                        document.getElementById("program").setAttribute("name", "group_program_pid");
                        //Display the trainers select
                        trainers.style.display = "";
                        select.forEach((element) => element.removeAttribute("disabled"));
                        // Send an AJAX request to fetch trainers for the selected program
                        var request = new XMLHttpRequest();
                        request.open("GET", "../../../utils/get_info.php?pt=true&program=" + program, true);
                        request.onreadystatechange = function() {
                            if (request.readyState == 4 && request.status == 200) {
                                const trainers = request.responseText;
                                //There aren't trainers for this programs therefore display alert and disable button
                                if (trainers == "") {
                                    trainerAlert.style.display = "";
                                    button.setAttribute("disabled", "");
                                } else {
                                    //Fill the trainer select with options with the trainers of this program
                                    document.getElementById("trainer").innerHTML = request.responseText;
                                }

                            }
                        };
                        request.send();
                    } else {
                        //The programm is a solo program, so hide the trainer select
                        document.getElementById("program").setAttribute("name", "program_pid");
                        trainers.style.display = "none";
                        select.forEach((element) => element.setAttribute("disabled", ""));
                        button.removeAttribute("disabled");
                    }
                }
            };
            xhr.send();
        }
    </script>
</body>

</html>