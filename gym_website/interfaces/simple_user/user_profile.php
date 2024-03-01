<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Google fonts links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Styles & Favicon -->
    <link rel="stylesheet" href="../../styles/yellow_button.css">
    <link rel="stylesheet" href="../../styles/css1.css">
    <link rel="shortcut icon" href="../../imgs/arm_11878800.png" type="image/x-icon">

</head>

<body>
    <?php require_once('../partials/authenticateUser.php'); ?>
    <?php include '../partials/navbar.php'; ?>

    <?php require("../partials/flash_messages.php");
    if (isset($_SESSION["updateSuccess"]) && $_SESSION["updateSuccess"] == true) {
        flash("updateSuccess");
        $_SESSION["updateSuccess"] = false;
    }
    if (isset($_SESSION["updateError"]) && $_SESSION["updateError"] == true) {
        flash("UpdateError");
        $_SESSION["updateError"] = false;
    }
    ?>
    <main>
        <div class="container light-style flex-grow-1">
            <h1 class="py-3 mb-4">
                Ο λογαριασμός μου
            </h1>
            <div class="row gx-0 ">
                <div class="col-lg-4 col-md-12">
                    <div class="mb-3">
                        <div class=" nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-account_general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-account_general" type="button" role="tab" aria-controls="v-pills-account_general" aria-selected="true">Ο λογαριασμός μου</button>
                            <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab" aria-controls="v-pills-password" aria-selected="false">Αλλαγή Κωδικού πρόσβασης</button>
                            <button class="nav-link" id="v-pills-reservation-tab" data-bs-toggle="pill" data-bs-target="#v-pills-reservation" type="button" role="tab" aria-controls="v-pills-reservation" aria-selected="false">Ιστορικό Κρατήσεων</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <?php
                    if (isset($_SESSION["id"])) {
                        //Get data from the current user
                        $userId = $_SESSION["id"];
                        $url = "http://localhost:9999/GymWService/rest/users/" . $userId;

                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);
                        $data = json_decode($response);
                    }
                    ?>
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-account_general" role="tabpanel" aria-labelledby="v-pills-account_general-tab" tabindex="0">
                            <div class="card-body">
                                <form action="../../utils/submitData.php" method="post">
                                    <input type="hidden" name="formType" value="userUpdate">
                                    <input type="hidden" name="source" value="profile">
                                    <input type="hidden" name="id" value="<?php echo $userId ?>">
                                    <input type="hidden" name="type" value="<?php echo $data->type ?>">
                                    <input type="hidden" name="cancellations" value="<?php echo $data->cancellations ?>">
                                    <!-- Form Group (username)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputUsername">Username</label>
                                        <input class="form-control" id="inputUsername" type="text" name="username" placeholder="Enter your username" value="<?php echo $data->username; ?>" required>
                                    </div>
                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputFirstName">Όνομα</label>
                                            <input class="form-control" id="inputFirstName" type="text" name="name" placeholder="Όνομα" value="<?php echo $data->name; ?>" required>
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputLastName">Επώνυμο</label>
                                            <input class="form-control " id="inputLastName" type="text" name="last_name" placeholder="Επώνυμο" value="<?php echo $data->last_name; ?>" required>
                                        </div>
                                    </div>
                                    <!-- Form Row        -->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (Country)-->
                                        <div class="col-md-6">
                                            <label class="form-label" for="countrySelect">Χωρα</label>
                                            <select class="form-select" id="countrySelect" name="country" onchange='getOption();' required>
                                                <option selected value='<?php echo $data->country; ?>'><?php echo $data->country; ?></option>
                                                <?php
                                                $url = "https://countriesnow.space/api/v0.1/countries";

                                                $curl = curl_init($url);
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                $response = curl_exec($curl);
                                                $result = json_decode($response, true);

                                                $country = $result["data"];

                                                foreach ($country as $post) {
                                                    echo "<option value='", $post["country"], "'>", $post["country"], "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- Form Group (city)-->
                                        <div class="col-md-6">
                                            <label class="form-label" for="citySelect">Πόλη</label>
                                            <select class='form-select' name="city" id="citySelect" required>
                                                <option selected value='<?php echo $data->city; ?>'><?php echo $data->city; ?></option>
                                                <?php
                                                $country = $data->country;

                                                $url = "https://countriesnow.space/api/v0.1/countries/cities/q?country=" . $country;

                                                $curl = curl_init($url);
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                                                $response = curl_exec($curl);
                                                $c = json_decode($response, true);
                                                $cities = $c["data"];
                                                // Output cities as options for the second select element

                                                foreach ($cities as $city) {
                                                    echo "<option value=", $city, ">", $city, "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Form Group (address)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputAddress">Διεύθυνση</label>
                                        <input class="form-control " id="inputAddress" type="text" name="address" placeholder="Διεύθυνση" value="<?php echo $data->address; ?>" required>
                                    </div>
                                    <!-- Form Group (email address)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                                        <input class="form-control " id="inputEmailAddress" type="email" name="email" placeholder="Email" value="<?php echo $data->email; ?>" required>
                                    </div>

                                    <!-- Save changes button-->
                                    <button class="btn btn-primary" type="submit">Αποθήκευση Αλλαγών</button>
                                </form>
                            </div>
                        </div>
                        <!-- Tab to change password -->
                        <div class="tab-pane fade" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab" tabindex="0">
                            <div class="card">
                                <div class="card-body pb-2">
                                    <form action="../../utils/submitData.php" method="POST">
                                        <input type="hidden" name="formType" value="passwordUpdate">
                                        <input type="hidden" name="id" value="<?php echo $userId; ?>">
                                        <input type="hidden" name="password" value="<?php echo $data->password; ?>">
                                        <div class="form-group">

                                            <label class="form-label" for="InputPassword1">Υπάρχων Κωδικός</label>
                                            <input type="password" class="form-control" id="InputPassword1" name="pass1" required>
                                            <input type="checkbox" class="form-check-input" id="check1" onclick="showPassword('InputPassword1')">
                                            <label for="check1" class="form-check-label">Εμφάνιση Κωδικού</label>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="InputPassword2">Νέος Κωδικός</label>
                                            <input type="password" class="form-control" id="InputPassword2" name="pass2" required>
                                            <input type="checkbox" class="form-check-input" id="check2" onclick="showPassword('InputPassword2')">
                                            <label for="check2" class="form-check-label">Εμφάνιση Κωδικού</label>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="InputPassword3">Επανάληψη Νέου
                                                Κωδικού</label>
                                            <input type="password" class="form-control" id="InputPassword3" name="pass3" required>
                                            <input type="checkbox" class="form-check-input" id="check3" onclick="showPassword('InputPassword3')">
                                            <label for="check3" class="form-check-label">Εμφάνιση Κωδικού</label>
                                        </div>
                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" type="submit">Αποθήκευση Αλλαγών</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Tab for displaying bookings -->
                        <div class="tab-pane fade" id="v-pills-reservation" role="tabpanel" aria-labelledby="v-pills-reservation-tab" tabindex="0">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Ημερομηνία</th>
                                                <th scope="col">Ώρα</th>
                                                <th scope="col">Πρόγραμμα</th>
                                                <th scope="col">Κατάσταση</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $url = "http://localhost:9999/GymWService/rest/bookings/user/" . $_SESSION['id'];

                                            $curl = curl_init($url);
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                            $response = curl_exec($curl);
                                            $bookings = json_decode($response);
                                            if ($bookings == null) {
                                                echo "<p>Δεν έχετε κάνει ακόμα κάποια κράτηση</p>";
                                            } else {
                                                foreach ($bookings as $post) {
                                                    echo "<tr>";
                                                    $url = "http://localhost:9999/GymWService/rest/calendars/" . $post->calendarid;

                                                    $curl = curl_init($url);
                                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                    $response = curl_exec($curl);
                                                    $calendar = json_decode($response);

                                                    echo "<td>", $calendar->date,  "</td>";
                                                    echo "<td>", $calendar->hour, "</td>";

                                                    if ($calendar->program_pid != null) {
                                                        $url = "http://localhost:9999/GymWService/rest/programs/" . $calendar->program_pid;

                                                        $curl = curl_init($url);
                                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                        $response = curl_exec($curl);
                                                        $program = json_decode($response);
                                                        echo "<td>", $program->program_name, "</td>";
                                                    } elseif ($calendar->g_program_pid != null) {
                                                        $url = "http://localhost:9999/GymWService/rest/programs/" . $calendar->g_program_pid;

                                                        $curl = curl_init($url);
                                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                        $response = curl_exec($curl);
                                                        $program = json_decode($response);
                                                        echo "<td>", $program->program_name, "</td>";
                                                    }
                                                    echo "<td>", $post->status, "</td>";
                                                    $today = date("Y-m-d");

                                                    date_default_timezone_set("Europe/Athens");
                                                    $time = date("H:i", strtotime("+2 hours"));
                                                    //If the booking status is Reserved and today is passed the booking day or today is the day of the booking and the hour is passed the booking
                                                    //disable the cancellation button
                                                    if ($post->status == 'Reserved' && (($calendar->date == $today && $calendar->hour >= $time) || ($calendar->date > $today))) {
                                                        echo "<td> 
                                                        <form action='../../utils/submitData.php' method='POST' >
                                                            <button class='btn btn-warning' type='submit'>Ακύρωση Κράτησης</button>
                                                            <input type='hidden' name='formType' value='cancelBooking'>
                                                            <input type='hidden' name='bookingid' value='", $post->bookingid, "'>
                                                            <input type='hidden' name='calendarid' value='", $post->calendarid, "'>
                                                            <input type='hidden' name='cancellations' value='", $data->cancellations, "'>
                                                            <input type='hidden' name='capacity' value='", $calendar->capacity, "'>
                                                            <input type='hidden' name='userid' value='", $_SESSION['id'], "'>
                                                        </form>
                                                    </td>";
                                                    } else {
                                                        echo "<td><button class='btn btn-dark' type='submit' disabled>Ακύρωση Κράτησης</button> </td>";
                                                    }

                                                    echo "</tr>";
                                                }
                                            }


                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="../../utils/showPassword.js"></script>
    <script>
        function getOption() {

            var country = document.getElementById("countrySelect").value;

            // Send an AJAX request to fetch cities for the selected country

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../../utils/get_cities.php?country=" + country, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update the cities dropdown with the received data
                    document.getElementById("citySelect").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</body>

</html>