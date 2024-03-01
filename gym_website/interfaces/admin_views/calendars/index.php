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
    //Depending on the occasion, display the appropriate flash messages on top of the page
    if (isset($_SESSION["createSuccess"]) && $_SESSION["createSuccess"] == true) {
        flash("createSuccess");
        $_SESSION["createSuccess"] = false;
    }
    if (isset($_SESSION["createError"]) && $_SESSION["createError"] == true) {
        flash("createError");
        $_SESSION["createError"] = false;
    }
    if (isset($_SESSION["deleteError"]) && $_SESSION["deleteError"] == true) {
        flash("deleteError");
        $_SESSION["deleteError"] = false;
    }
    if (isset($_SESSION["deleteSuccess"]) && $_SESSION["deleteSuccess"] == true) {
        flash("deleteSuccess");
        $_SESSION["deleteSuccess"] = false;
    }
    ?>
    <main>

        <section id="form_tables" class="table-responsive  align-items-center my-3 mx-2">
            <h1 class="text-center">Όλες οι ημερομηνίες των προγραμμάτων</h1>
            <div>
                <a href="new.php" class="btn btn-success my-2">Προσθήκη Ημερομηνίας</a>
            </div>
            <table class="table table-secondary table-hover caption-top text-center">
                <caption>Λίστα με όλες τις ημερομηνίες των προγραμμάτων</caption>
                <thead>
                    <tr class="table-dark">
                        <th></th>
                        <th scope="col">Πρόγραμμα</th>
                        <th scope="col">Tύπος</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $url = "http://localhost:9999/GymWService/rest/calendars";

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                    $response = curl_exec($curl);
                    $calendar = json_decode($response);
                    if ($calendar == null) {
                        echo "</tbody></table><p class='mt-4 text-center'>Δεν υπάρχουν ημερολόγια προγραμμάτων</p>";
                    } else {
                        $programs = [];
                        $uniqueEntries = [];
                        //Display calendars by program name and if exists by trainer id
                        foreach ($calendar as $post) {

                            //Solo program
                            if ($post->program_pid != null) {
                                $url = "http://localhost:9999/GymWService/rest/programs/" . $post->program_pid;

                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($curl);
                                $program = json_decode($response);
                                if (in_array($program->program_name, $programs)) {
                                } else {
                                    echo "<tr>
                                    <td scope='row' style='width: 14%;'>
                                    <a class='btn my-1' href='show.php?program_pid=", $post->program_pid, "' style='background-color: #ffdd00;'>Show</a>
                                    </td>";
                                    array_push($programs, $program->program_name);
                                    echo "<td>", $program->program_name, "</td>";
                                    echo "<td>Ατομικό</td>";
                                    echo "</tr>";
                                }
                            } elseif ($post->g_program_pid != null) {
                                //Group program

                                //Get the key from program id and trainer id
                                $key = $post->g_program_pid . '_' .  $post->trainer_id;
                                $url = "http://localhost:9999/GymWService/rest/programs/" . $post->g_program_pid;

                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($curl);
                                $program = json_decode($response);
                                $url = "http://localhost:9999/GymWService/rest/program_trainer/programs/" . $post->g_program_pid . "/trainers";

                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($curl);
                                $program_trainer = json_decode($response);

                                //The key mustn't be in the uniqueEntries array in order to display the row
                                if ($program_trainer != null) {
                                    foreach ($program_trainer as $pt) {
                                        if ($pt->tiid != $post->trainer_id) {
                                        } else {
                                            if (in_array($key, $uniqueEntries)) {
                                            } else {
                                                echo "<tr>
                                                <td scope='row' style='width: 14%;'>
                                                <a class='btn my-1' href='show.php?program_pid=", $post->g_program_pid, "&trainer_tid=", $pt->tiid, "' style='background-color: #ffdd00;'>Show</a>
                                                </td>";
                                                array_push($uniqueEntries, $key);
                                                echo "<td>", $program->program_name, "</td>";
                                                echo "<td>Oμαδικό - ", $pt->name, "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    ?>
                </tbody>
            </table>
        </section>

    </main>
</body>

</html>