<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../imgs/arm_11878800.png" type="image/x-icon">
    <!-- Google fonts links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../../styles/yellow_button.css">
</head>

<body>
    <?php require_once("../partials/authenticateAdmin.php") ?>
    <?php include("../partials/admin_navbar.php"); ?>
    <?php
    require("../partials/flash_messages.php");
    // Handle the "delete" request or the "update" request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["formType"])) {
        $formType = $_POST["formType"];
        if ($formType == "deleteUser") {
            //Delete user request
            $userId = trim($_POST["userId"]);
            $url = "http://localhost:9999/GymWService/rest/users/" . $userId;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpcode == 200) {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                flash("deleteSuccess", $message, FLASH_SUCCESS);
                flash("deleteSuccess");
            } else {
                //All good
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                flash("deleteError", $message, FLASH_ERROR);
                flash("deleteError");
            }
        } elseif ($formType == "setStatus") {
            //Update user status and type request
            $userId = trim($_POST["userId"]);
            $type = trim($_POST["type"]);
            $User = array(
                "type" => $type,
                "status" => "approved"
            );
            $jsonUser = json_encode($User);
            $url = "http://localhost:9999/GymWService/rest/users/" . $userId . "/updateStatus";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonUser);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode == 200) {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                flash("updateSuccess", $message, FLASH_SUCCESS);
                flash("updateSuccess");
            } else {
                //All good
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                flash("updateError", $message, FLASH_ERROR);
                flash("updateError");
            }
        }
    }
    ?>
    <main>

        <section id="form_tables" class="table-responsive  align-items-center m-3">
            <h1 class="text-center">Αιτήματα Εγγραφής</h1>
            <table class="table table-secondary table-hover caption-top">
                <caption>Λίστα από τα αιτήματα εγγραφής</caption>
                <thead>
                    <tr class="table-dark">
                        <th scope="col"></th>
                        <th scope="col">Κατάσταση</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Όνομα</th>
                        <th scope="col">Επίθετο</th>
                        <th scope="col">Χώρα</th>
                        <th scope="col">Πόλη</th>
                        <th scope="col">Διεύθυνση</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $url = "http://localhost:9999/GymWService/rest/users";

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    $data = json_decode($response);
                    $count = true;
                    foreach ($data as $post) {
                        if ($post->status == "pending") {
                            $count = false;
                            echo "<tr>";
                            echo "<td>
                            <div>
                            <input type='radio' class='btn-check' name='options-outlined' id='success-outlined1' autocomplete='off' value='valid' onclick='handleRadio(this)'>
                            <label class='btn btn-outline-success my-1' for='success-outlined1'>Valid</label>
                            <form class='d-inline me-0' action=", htmlspecialchars($_SERVER["PHP_SELF"]), " method='POST'>
                            <input type='hidden' name='userId' value='", $post->userId, "'>
                            <input type='hidden' name='formType' value='deleteUser'>
                            <button class='btn btn-outline-danger' type='submit'>Invalid</button>
                            </form>
                            </div>
                            <form action=", htmlspecialchars($_SERVER["PHP_SELF"]), " method='post'>
                            <input type='hidden' name='userId' value='", $post->userId, "'>
                            <input type='hidden' name='formType' value='setStatus'>
                            <select class='form-select w-75' aria-label='Select user type' name='type' style='display: none;' onchange='form.submit()'>
                                <option selected value=''>Επίλεξε τύπο χρήστη</option>
                                <option value='simple'>Απλός</option>
                                <option value='admin'>Διαχειριστής</option>
                            </select>
                            </form>
                            </td>";
                            echo "<td>", $post->status . "</td>";
                            echo "<td>", $post->username . "</td>";
                            echo "<td>", $post->email . "</td>";
                            echo "<td>", $post->name . "</td>";
                            echo "<td>", $post->last_name . "</td>";
                            echo "<td>", $post->country . "</td>";
                            echo "<td>", $post->city . "</td>";
                            echo "<td>", $post->address . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
            if ($count == true) {
                echo "<p class='text-center'>Δεν υπάρχουν Αιτήματα Εγγραφής!</p>";
            } ?>
        </section>

    </main>
    <script>
        //Function to manipulate the first row of the table
        function handleRadio(radio) {
            var row = radio.closest('tr');
            var selectInput = row.querySelector('select');

            if (radio.value === 'valid') {
                // Εάν επιλέγεται το 'valid', εμφανίζουμε το text input και κρύβουμε τα radio
                selectInput.style.display = 'block';
                var f = radio.closest('div');
                f.style.display = 'none';
            } else if (radio.value === 'invalid') {
                // Εάν επιλέγεται το 'invalid', διαγράφουμε ολόκληρη τη σειρά
                row.remove();
            }
        }
    </script>
</body>

</html>