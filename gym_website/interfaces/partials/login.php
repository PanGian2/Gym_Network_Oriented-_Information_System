<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="shortcut icon" href="../../imgs/arm_11878800.png" type="image/x-icon">
    <link rel="stylesheet" href="../../styles/yellow_button.css">
</head>

<body style="background-color: #343a40;">
    <?php include '../partials/navbar.php'; ?>
    <?php
    $username = $password = "";
    $username_err = $password_err = $login_err = "";
    //Έλεγχος και ανακατευθυνση σε περιπτωση που ο χρηστης εχει ηδη συνδεθει
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        $_SESSION["alreadyLoggedIn"] = true;
        require_once("../partials/flash_messages.php");
        flash("LoggedIn", "Είστε ήδη συνδεδεμένος", FLASH_INFO);
        header("Location: ../simple_user/home.php");
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check if username is empty
        if (empty(trim($_POST["username"]))) {
            $username_err = "Παρακαλώ εισάγετε το username.";
        } else {
            $username = trim($_POST["username"]);
        }

        // Check if password is empty
        if (empty(trim($_POST["password"]))) {
            $password_err = "Παρακαλώ εισάγετε το password.";
        } else {
            $password = trim($_POST["password"]);
        }


        if (empty($username_err) && empty($password_err)) {
            $url = "http://localhost:9999/GymWService/rest/users/find/" . $_POST["username"];

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $data = json_decode($response);
            if ($data != null) {
                // Validate credentials
                if (password_verify($password, $data->password)) {
                    // Password is correct, so start a new session

                    if ($data->status == "approved") {
                        if ($data->type == "admin") {
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $data->userId;
                            $_SESSION["username"] = $username;
                            $_SESSION["type"] = $data->type;
                            header("location: ../admin_views/admin_home.php");
                        } elseif ($data->type == "simple") {
                            // Redirect user to welcome page
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $data->userId;
                            $_SESSION["username"] = $username;
                            $_SESSION["type"] = $data->type;
                            header("location: ../simple_user/home.php");
                        }
                        //Get current date and time
                        $today = date("Y-m-d");
                        date_default_timezone_set("Europe/Athens");
                        $time = date("H:i");

                        //If a cancellation day is set and is after today, clear cancellation day and number of cancellations
                        if ($data->cancellation_end_day != "NULL") {
                            if ($data->cancellation_end_day <= $today) {
                                $url = "http://localhost:9999/GymWService/rest/users/" . $data->userId . "/updateCancellations";

                                $Cancel = array(
                                    "cancellations" => 0,
                                    "cancellation_end_day" => "NULL"
                                );
                                $jsonCancel = json_encode($Cancel);

                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCancel);
                                curl_setopt($curl, CURLOPT_HEADER, true);
                                $response = curl_exec($curl);
                                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                            }
                        }
                        //Get all bookings of user and change the status of those that have passed the current date to Completed
                        $url = "http://localhost:9999/GymWService/rest/bookings/user/" . $data->userId;

                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);
                        $bookings = json_decode($response);

                        foreach ($bookings as $post) {
                            $url1 = "http://localhost:9999/GymWService/rest/calendars/" . $post->calendarid;
                            $curl = curl_init($url1);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $response = curl_exec($curl);
                            $calendar = json_decode($response);
                            if ($post->status == "Reserved" && (($calendar->date == $today && $calendar->hour < $time) || ($calendar->date < $today))) {
                                $Booking = array(
                                    "bookingid" => $post->bookingid,
                                    "status" => "Completed",
                                    "userid" => $data->userId,
                                    "calendarid" => $post->calendarid
                                );
                                $jsonBooking = json_encode($Booking);
                                $url = "http://localhost:9999/GymWService/rest/bookings/" . $post->bookingid;
                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBooking);
                                curl_setopt($curl, CURLOPT_HEADER, true);
                                $response = curl_exec($curl);
                                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                            }
                        }
                    } else {
                        //User's registration form hasn't been accepted yet by the admin
                        require_once("../partials/flash_messages.php");
                        flash("notApproved", "Η αίτηση εγγραφή σας δεν έχει γίνει αποδεκτή ακόμα. Παρακαλώ δοκιμάστε αργότερα!", FLASH_WARNING);
                        $_SESSION["notApproved"] = true;
                        header("location: ../simple_user/home.php");
                    }
                } else {
                    // Password is not valid
                    $login_err = "Invalid username or password.";
                }
            } else {
                // Username doesn't exist
                $login_err = "Invalid username or password.";
            }
        }
    }

    ?>
    <main>
        <div class="container align-items-center mt-5">
            <div class="row justify-content-center">
                <div class="card mb-3 w-75 px-0 pb-0">
                    <div class="row gx-0 gy-1">
                        <div class="col-md-4 order-last order-md-first text-center">
                            <img src="https://media.istockphoto.com/id/498585360/photo/fitness-is-her-number-one-priority.jpg?s=170667a&w=0&k=20&c=0Xijk3WsmshM3c4nthPO_0Mfzcw8Tez71G5iE-EoOCw=" class="img-fluid rounded-start" alt="Image of a trainer">
                        </div>
                        <div class="col-md-8 order-sm-first order-md-last">
                            <div class="card-body">
                                <h1 class="card-title fw-bold mb-3">Σύνδεση</h1>
                                <?php
                                if (!empty($login_err)) {
                                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" id="floatingInput" placeholder="Username" name="username">
                                        <label for="floatingInput">Username</label>
                                        <span class="invalid-feedback"><?php echo $username_err; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="floatingPassword" placeholder="Password" name="password">
                                        <label for="floatingPassword">Password</label>
                                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                    </div>
                                    <button type="submit" class="btn mb-3 y_button">Σύνδεση</button>
                                </form>
                                <p class="card-text">
                                    <small class="text-body-secondary">
                                        <a href="../simple_user/register.php" class="card-link ms-0" style="text-decoration: underline;">Δεν έχεις λογαριασμό; Κάνε εγγραφή τώρα!</a>
                                    </small>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </main>

</body>

</html>