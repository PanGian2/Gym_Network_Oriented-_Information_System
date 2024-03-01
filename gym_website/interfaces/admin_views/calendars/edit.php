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

        <?php
        // Get link from the url
        if (isset($_GET["id"])) {
            $cid = $_GET["id"];
        }
        $url = "http://localhost:9999/GymWService/rest/calendars/" . $cid;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $data = json_decode($response);

        //Check if the current calendar has bookings
        $url = "http://localhost:9999/GymWService/rest/bookings";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $bookings = json_decode($response);
        $hasBookings = false;
        if ($bookings != null) {
            foreach ($bookings as $b) {
                if ($b->calendarid == $cid && $b->status == "Reserved") {
                    $hasBookings = true;
                    break;
                }
            }
        }

        ?>

        <div class="row gx-0 my-3">
            <h1 class="text-center mt-3 mb-4">Επεξεργασία Ημερομηνίας Προγράμματος</h1>
            <div class="col-6 offset-3">
                <form action="../../../utils/submitData.php" method="POST">
                    <input type="hidden" name="formType" value="calendarUpdate">
                    <input type="hidden" name="id" value="<?php echo $cid; ?>">
                    <div class="mb-3">
                        <label class="form-label" for="capacity">Χωρητικότητα</label>
                        <input class="form-control" type="number" id="capacity" name="capacity" value="<?php echo $data->capacity ?>" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="hour">Ώρα</label>
                        <?php
                        if ($hasBookings == true) {
                            echo "<input disabled class='form-control' type='time' id='hour' name='hour' value='", $data->hour, "' min='10:00' max='22:00' required />";
                            echo "<div id='trainersAlert' class='alert alert-warning alert-dismissible fade show' role='alert'>
                                    <strong>Έχουν γίνει κρατήσεις για αυτή την ημερομηνία, επομένως δεν μπορείτε να τροποποιήσετε την ώρα!</strong>
                                </div>";
                        } else {
                            echo "<input class='form-control' type='time' id='hour' name='hour' value='", $data->hour, "' min='10:00' max='22:00' required />";
                        }
                        ?>

                    </div>
                    <div class="mb-3">
                        <button class="btn btn-info" id="submitButton" type="submit">Ενημέρωση Ημερομηνίας</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container text-center">
            <?php
            if ($data->program_pid != null) {
                echo '<a href="show.php?program_pid=', $data->program_pid, '" class="btn btn-success my-4">Επιστροφή στην Προβολή Ημερομηνιών</a>';
            } else {
                echo '<a href="show.php?program_pid=', $data->g_program_pid, '&trainer_tid=', $data->trainer_id, '" class="btn btn-success my-4">Επιστροφή στην Προβολή Ημερομηνιών</a>';
            }
            ?>

        </div>

    </main>
</body>

</html>