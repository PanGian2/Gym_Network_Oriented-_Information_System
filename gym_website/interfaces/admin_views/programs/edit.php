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
            $pid = $_GET["id"];
        }
        $url = "http://localhost:9999/GymWService/rest/programs/" . $pid;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $data = json_decode($response);
        ?>

        <div class="row gx-0 my-3">
            <h1 class="text-center my-2">Edit Program</h1>
            <div class="col-6 offset-3">
                <form action="../../../utils/submitData.php" method="POST">
                    <input type="hidden" name="formType" value="programUpdate">
                    <input type="hidden" name="id" value="<?php echo $pid; ?>">
                    <input type="hidden" name="type" value="<?php echo $data->type; ?>">
                    <div class="mb-3">
                        <div class="row mt-4 gx-2 align-items-start">
                            <div class="col-md-4">
                                <img src="<?php echo $data->img_url; ?>" class="img-thumbnail w-100 p-0" alt="Image of program">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label" for="image">URL Εικόνας</label>
                                <input class="form-control" type="url" pattern="https://.*" id="image" name="img_url" value="<?php echo $data->img_url; ?>" required />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="name">Όνομα Προγράμματος</label>
                        <input class="form-control" type="text" id="name" name="program_name" value="<?php echo $data->program_name; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="text1">Τι είναι?</label>
                        <textarea class="form-control" rows="6" type="text" id="text1" name="whatdescription" required><?php echo $data->whatdescription; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="text2">Γιατί να το επιλέξεις?</label>
                        <textarea class="form-control" rows="6" type="text" id="text2" name="whydescription" required><?php echo $data->whydescription; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="time">Διάρκεια</label>
                        <input class="form-control" type="number" id="time" name="duration" value="<?php echo $data->duration; ?>" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="type">Τύπος</label>
                        <select class="form-select" id="type" name="type" required disabled>
                            <?php
                            if ($data->type == 1) {
                                echo "<option selected value='1'>Ατομικό</option>";
                                echo "<option value='2'>Ομαδικό</option>";
                            } else {
                                echo "<option selected value='2'>Ομαδικό</option>";
                                echo "<option value='1'>Ατομικό</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    $count = 0;
                    if ($data->type == 2) {
                        $url = "http://localhost:9999/GymWService/rest/program_trainer/programs/" . $pid . "/trainers";
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);
                        $program_trainers = json_decode($response);
                        curl_close($curl);

                        $url = "http://localhost:9999/GymWService/rest/trainers";
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);
                        $trainers = json_decode($response);
                        curl_close($curl);

                        echo "<div id='trainerSelect'>";
                        echo "<div id='trainerDiv' class='mb-3'>";

                        echo "<script>var pt = 0;</script>";
                        $pt_count = 0;
                        foreach ($program_trainers as $pt) {
                            $pt_count++;
                            echo "<script>pt++</script>";
                            echo "<label class='form-label' for='trainer'>Γυμναστής/στρια</label>";
                            echo "<select class='form-select trainer' id='trainer' name='tid[]' required>";
                            echo "<option selected value='", $pt->tiid, "'>", $pt->name, " ", $pt->last_name, "</option>";
                            $count = 0;
                            echo "<script>var tr = 0</script>";
                            foreach ($trainers as $tr) {
                                $count++;
                                echo "<script>tr++</script>";
                                if ($tr->tiid !== $pt->tiid) {
                                    echo "<option value='", $tr->tiid, "'>", $tr->name, " ", $tr->last_name, "</option>";
                                }
                            }
                            echo "</select>";
                            echo "<input type='hidden' name='trainer[]' value='", $pt->tiid, "'>";

                            echo "<div class='form-check my-2'>
                                    <input class='form-check-input' type='checkbox' id='removeTrainer' name='removeTrainer[]' value='", $pt->tiid, "'>
                                    <label class='form-check-label' for='removeTrainer'>Αφαίρεση Γυμναστή</label>";
                            echo "</div>";
                        }
                        echo "<input type='hidden' name='pt_count' value=", $pt_count, ">";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                    <?php
                    //If more than one trainers display the duplicate button
                    if ($count > 1) {
                        echo "<div class='row my-3 gy-1 '>";
                        echo "<div class='col-md-6 text-md-start text-center'>";
                        echo '<button class="btn btn-info" id="submitButton" type="submit">Ενημέρωση Προγράμματος</button>
                                </div>';
                        echo "<div class='col-md-6 text-md-end text-center'>
                                    <button id='duplButton' class='btn btn-dark' type='button' onclick='duplicate()'>Προσθήκη Γυμναστή</button>
                                </div>";
                    } else {
                        echo "<div class='mb-3'>";
                        echo '<button class="btn btn-info" id="submitButton" type="submit">Ενημέρωση Προγράμματος</button>
                             </div>';
                    }
                    ?>
                </form>
            </div>
        </div>
        <div class="container text-center">
            <a href="show.php?id=<?php echo $pid; ?>" class="btn btn-success my-4">Επιστροφή στo Πρόγραμμα</a>
        </div>

    </main>
    <script>
        var i = 1;

        //The variable pt has been defined 
        if (typeof pt !== "undefined") {
            //If it is one then disable the remove trainer checkbox
            if (pt == 1) {
                const removeTrainer = document.getElementById("removeTrainer");
                removeTrainer.setAttribute("disabled", "");
            }
            //If the pt is equal to the max number of trainers then disable the duplication button
            if (pt == tr) {
                const duplButton = document.getElementById("duplButton");
                duplButton.setAttribute("disabled", "");
            }
        }

        //Function that duplicates the trainerSelect
        function duplicate() {
            const original = document.getElementById('trainerSelect');
            const duplButton = document.getElementById("duplButton");
            const div = document.getElementById("trainerDiv");
            const clone = div.cloneNode(true); // "deep" clone
            clone.childNodes[3].setAttribute("name", "new_tid[]");
            original.insertAdjacentElement("beforeend", clone);

            //Duplicate as long there are trainers left
            if (i == tr - 1) {
                duplButton.setAttribute("disabled", "");
            }
            i++;

        }
    </script>
</body>

</html>