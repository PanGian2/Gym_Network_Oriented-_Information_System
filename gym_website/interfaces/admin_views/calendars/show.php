<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../../imgs/arm_11878800.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../styles/yellow_button.css">
</head>

<body>
    <?php require_once("../../partials/authenticateAdmin.php"); ?>
    <?php include("../../partials/admin_navbar.php"); ?>
    <?php require("../../partials/flash_messages.php");

    //Handle the "delete" request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $calendarId = trim($_POST["calendarId"]);
        $url = "http://localhost:9999/GymWService/rest/calendars/" . $calendarId;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_HEADER, true);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpcode == 500) {
            //If the returned code is 500 then it means that there are bookings for this calendar
            flash("deleteError", "Δεν μπορείτε να διαγράψετε αυτή την ημερομηνία καθώς υπάρχουν κρατήσεις για αυτή!", FLASH_ERROR);
            $_SESSION["deleteError"] = true;
            echo "<script>location.href = 'index.php';</script>";
        } elseif ($httpcode != 200) {
            //Something went wrong
            //The returned message is after the GMT in the initial response
            $message =   substr($response, strpos($response, "GMT") + 3);
            flash("deleteError", $message, FLASH_ERROR);
            $_SESSION["deleteError"] = true;
            echo "<script>location.href = 'index.php';</script>";
        } else {
            //All good
            //The returned message is after the GMT in the initial response
            $message =   substr($response, strpos($response, "GMT") + 3);
            flash("deleteSuccess", $message, FLASH_SUCCESS);
            $_SESSION["deleteSuccess"] = true;
            echo "<script>location.href = 'index.php';</script>";
        }
    }

    //Depending on the occasion, display the appropriate flash messages on top of the page
    if (isset($_SESSION["updateSuccess"]) && $_SESSION["updateSuccess"] == true) {
        flash("updateSuccess");
        $_SESSION["updateSuccess"] = false;
    }
    if (isset($_SESSION["updateError"]) && $_SESSION["updateError"] == true) {
        flash("UpdateError");
        $_SESSION["updateError"] = false;
    }
    ?>
    <main class="container mt-5">
        <?php
        //Based on the queries in the url call the appropriate endpoint
        if (isset($_GET["program_pid"]) && isset($_GET["trainer_tid"])) {
            $programId = $_GET["program_pid"];
            $trainerId = $_GET["trainer_tid"];
            $url = "http://localhost:9999/GymWService/rest/calendars?group_program=" . $programId . "&trainer=" . $trainerId;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $data = json_decode($response);

            $url = "http://localhost:9999/GymWService/rest/programs/" . $programId;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $program = json_decode($response);
        } elseif (isset($_GET["program_pid"])) {
            $programId = $_GET["program_pid"];

            $url = "http://localhost:9999/GymWService/rest/calendars?program=" . $programId;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $data = json_decode($response);

            $url = "http://localhost:9999/GymWService/rest/programs/" . $programId;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $program = json_decode($response);
        }

        ?>

        <h1 class="text-center"><?php echo $program->program_name; ?></h1>
        <?php
        if ($data == null) {
            echo "<h2 class='text-center my-4'>Δεν υπάρχουν ημερομηνίες για αυτό το πρόγραμμα!</h2>";
        }

        ?>
        <table class="table table-light table-striped table-bordered text-center">
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


        <?php
        if ($data != null) {
            echo "<p>Χωρητικότητα
                <ul>";
            foreach ($data as $post) {

                echo "<li class='my-2'>";
                echo $post->capacity;
                echo " άτομα στις ";
                $d = date("d-m-Y", strtotime($post->date));
                echo $d;
                echo " <a class='btn btn-warning py-1' href='edit.php?id=", $post->calendarid, "'>Edit</a>";
                echo " <form class='d-inline me-0' action=", htmlspecialchars($_SERVER["PHP_SELF"]), " method='POST'>
                        <input type='hidden' name='calendarId' value='",  $post->calendarid, "'>
                        <button class='btn btn-danger py-1', type='submit'>Delete</button></form>";
                echo "</li>";
            }
            echo "</ul>
            </p>";
        }

        ?>


        <div class="container text-center">
            <a href="index.php" class="btn btn-success my-4">Επιστροφή στην Διαχείριση Ημερομηνιών</a>
        </div>
    </main>
    <script>
        //Function to add some data on a specific cell of the table
        function addToTable(row, day, data) {
            const cell = document.getElementById(row).getElementsByClassName(day)[0];
            cell.innerHTML = data;
        }
        <?php
        //For each entry in calendars take the hour, the date and add them to table
        foreach ($data as $post) {
            $hour = date("H", strtotime($post->hour));
            $h = date("H:i", strtotime($post->hour));
            $due = date("H:i", strtotime('+ ' . $program->duration . ' minutes', strtotime($post->hour)));
            $day = date("l", strtotime($post->date));
            $value = "<p>" . $program->program_name . "</p><p>" . $h . " - " . $due . "</p>";
            echo "addToTable(", $hour, ",'", $day, "','", $value, "');";
        }
        ?>
    </script>
</body>

</html>