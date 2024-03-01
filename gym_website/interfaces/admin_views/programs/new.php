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
            <h1 class="text-center my-2">Νέο Πρόγραμμα</h1>
            <div class="col-6 offset-3">
                <form action="../../../utils/submitData.php" method="POST">
                    <input type="hidden" name="formType" value="programCreate">
                    <div class="mb-3">
                        <label class="form-label" for="image">URL Εικόνας</label>
                        <input class="form-control" type="url" pattern="https://.*" id="image" name="img_url" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="name">Όνομα Προγράμματος</label>
                        <input class="form-control" type="text" id="name" name="program_name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="text1">Τι είναι?</label>
                        <textarea class="form-control" rows="6" type="text" id="text1" name="whatdescription" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="text2">Γιατί να το επιλέξεις?</label>
                        <textarea class="form-control" rows="6" type="text" id="text2" name="whydescription" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="time">Διάρκεια</label>
                        <input class="form-control" type="number" id="time" name="duration" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="type">Τύπος</label>
                        <select class="form-select" id="type" name="type" onchange='getOption()' required>
                            <option selected disabled value=''>Επιλέξτε τύπο προγράμματος</option>
                            <option value='1'>Ατομικό</option>
                            <option value='2'>Ομαδικό</option>
                        </select>
                    </div>
                    <div id='trainerSelect' style="display:none;">
                        <div id="trainerDiv" class="mb-3">
                            <label class='form-label' for='trainer'>Γυμναστής/στρια</label>
                            <select class='form-select trainer' id='trainer' name='tid[]' required>
                                <option disabled selected value=''>Επιλέξτε Γυμναστή/στρια</option>
                                <?php
                                $url = "http://localhost:9999/GymWService/rest/trainers";
                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($curl);
                                $trainers = json_decode($response);
                                curl_close($curl);

                                $count = 0;
                                $noTrainers = false;
                                if ($trainers == null) {
                                    echo "</select>";
                                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert""><strong>Δεν υπάρχουν γυμναστές στο σύστημα!</strong></div>';
                                    echo "</div>";
                                } else {
                                    echo "<script>var count = 0</script>";
                                    foreach ($trainers as $tr) {
                                        $count++;
                                        echo "<script>count++</script>";
                                        echo "<option value='", $tr->tiid, "'>", $tr->name, " ", $tr->last_name, "</option>";
                                    }
                                    echo "</select>";
                                    echo "</div>";
                                }

                                ?>

                        </div>

                        <?php
                        if ($count > 1) {
                            echo "<div class='row my-3 gy-1 '>";
                            echo "<div class='col-md-6 text-md-start text-center'>";
                            echo '<button class="btn btn-info" id="submitButton" type="submit">Δημιουργία Προγράμματος</button>
                                        </div>';
                            echo "<div class='col-md-6 text-md-end text-center'>
                                    <button id='duplButton' class='btn btn-dark' type='button' style='display:none;' onclick='duplicate()'>Προσθήκη Γυμναστή</button>
                                </div>";
                        } else {
                            echo "<div class='mb-3'>";
                            echo '<button class="btn btn-info" id="submitButton" type="submit">Δημιουργία Προγράμματος</button>
                                    </div>';
                        }
                        ?>
                </form>
            </div>
        </div>
        <div class="container text-center">
            <a href="index.php" class="btn btn-success my-4">Επιστροφή στην Διαχείριση Προγρμμάτων</a>
        </div>

    </main>
    <script>
        var i = 1;

        //function to get the option from the type select
        function getOption() {

            const type = document.getElementById("type").value;
            const trainers = document.getElementById("trainerSelect");
            const select = document.querySelectorAll(".trainer");
            var alert = document.querySelector(".alert");
            const button = document.getElementById("submitButton");
            const duplButton = document.getElementById("duplButton");
            if (type == 2) {
                //The program is a group program
                trainers.style.display = "";
                select.forEach((element) => element.removeAttribute("disabled"));
                //if the aler is displayed, disable the submit button
                if (alert != null) {
                    if (alert.style.display != "none") {
                        button.setAttribute("disabled", "");
                    }

                }

                duplButton.style.display = "";
            } else {
                //The program is a solo program
                trainers.style.display = "none";
                select.forEach((element) => element.setAttribute("disabled", ""));
                button.removeAttribute("disabled");
                duplButton.style.display = "none"
            }
        }

        //Function that duplicates the trainerSelect
        function duplicate() {
            const original = document.getElementById('trainerSelect');
            const duplButton = document.getElementById("duplButton");
            const div = document.getElementById("trainerDiv");
            const clone = div.cloneNode(true); // "deep" clone
            original.insertAdjacentElement("beforeend", clone);

            //Duplicate as long there are trainers left
            if (i == count - 1) {
                duplButton.setAttribute("disabled", "");
            }
            i++;

        }
    </script>
</body>

</html>