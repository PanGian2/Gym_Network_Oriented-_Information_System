<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Google fonts links -->
    <link rel="stylesheet" href="../../styles/programs.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Styles & Favicon -->
    <link rel="stylesheet" href="../../styles/yellow_button.css">
    <link rel="shortcut icon" href="../../imgs/arm_11878800.png" type="image/x-icon">
</head>

<body>
    <?php include '../partials/navbar.php'; ?>
    <main>
        <?php
        //Get the id of the user from url
        if (isset($_GET["id"])) {
            $programId = $_GET["id"];
        }

        $url = "http://localhost:9999/GymWService/rest/programs/" . $programId;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $data = json_decode($response);
        //Depending on the type of the program, call the appropriate endpoint
        if ($data->type == 1) {
            $url = "http://localhost:9999/GymWService/rest/calendars?program=" . $programId;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $calendar = json_decode($response);
        } elseif ($data->type == 2) {
            $url = "http://localhost:9999/GymWService/rest/calendars?group_program=" . $programId;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $calendar = json_decode($response);
        }

        ?>
        <section id="program" class="container text-center mb-3">
            <div class="row text-center gy-2">
                <img src="<?php echo $data->img_url; ?>" alt="Image of the program">
                <h1><?php echo $data->program_name; ?></h1>
                <h2>Τι είναι;</h2>
                <p><?php echo $data->whatdescription; ?></p>
                <h2>Γιατί να το επιλέξω</h2>
                <p><?php echo $data->whydescription; ?></p>
            </div>
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
                echo '<h2>Πρόγραμμα</h2>
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
                        <tbody>';
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
                echo "</tbody>
                        </table>";
            }

            ?>
            <a href="make_reservation.php" class="y_button btn" style="color:black;">Κλείσε Ραντεβού Τώρα!</a>
        </section>
    </main>

    <?php include '../partials/footer.php'; ?>
    <script type="text/javascript">
        //Function to add some data on a specific cell of the table
        function addToTable(row, day, data) {
            const cell = document.getElementById(row).getElementsByClassName(day)[0];
            cell.innerHTML = data;
        }
        <?php
        //For each entry in calendars take the hour, the date and add them to table
        foreach ($calendar as $post) {
            $hour = date("H", strtotime($post->hour)); //hour format
            $h = date("H:i", strtotime($post->hour)); //hour and minutes format
            $due = date("H:i", strtotime('+ ' . $data->duration . ' minutes', strtotime($post->hour))); //adds to hour the duration minutes of program
            $day = date("l", strtotime($post->date)); //day format e.g Sunday
            $value = "<p>" . $data->program_name . "</p><p>" . $h . " - " . $due . "</p>"; //the content of the cell
            echo "addToTable(", $hour, ",'", $day, "','", $value, "');";
        }
        ?>
    </script>
</body>

</html>