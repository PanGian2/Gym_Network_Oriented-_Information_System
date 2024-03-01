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
            <h1 class="text-center my-2">Δημιουργία Χρήστη</h1>
            <div class="col-6 offset-3">
                <form action="../../../utils/submitData.php" method="POST">
                    <input type="hidden" name="formType" value="userCreate" />
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" type="text" id="username" name="username" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-group">
                            <span class="input-group-text" id="email-label">@</span>
                            <input type="text" class="form-control" id="email" aria-label="email" aria-describedby="email-label" name="email" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="name">Ονομα</label>
                        <input class="form-control" type="text" id="name" name="name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="surname">Επιθετο</label>
                        <input class="form-control" type="text" id="surname" name="last_name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="countrySelect">Χωρα</label>
                        <select name='country' class='form-select' id='countrySelect' onchange='getOption();' required>
                            <option selected value=''>Επέλεξε Χώρα</option>
                            <?php
                            $url = "https://countriesnow.space/api/v0.1/countries";

                            $curl = curl_init($url);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $response = curl_exec($curl);
                            $data = json_decode($response, true);

                            $country = $data["data"];

                            foreach ($country as $post) {
                                echo "<option value=", $post["country"], ">", $post["country"], "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class=" mb-3">
                        <label class="form-label" for="citySelect">Πόλη</label>
                        <select class='form-select' name="city" id="citySelect" required>
                            <option selected value=''>Επέλεξε Πόλη</option>";
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="address">Διευθυνση</label>
                        <input class="form-control" type="text" id="address" name="address" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="type">Τύπος</label>
                        <select class="form-select" id="type" name="type" required>
                            <option selected disabled>Επέλεξε Τύπο</option>
                            <option value="simple">Απλός</option>
                            <option value="admin">Διαχειριστής</option>
                        </select>
                    </div>
                    <div class="my-4">
                        <button class="btn btn-info" type="submit">Δημιουργία Χρήστη</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container text-center">
            <a href="show.php" class="btn btn-success my-4">Επιστροφή στην Διαχείριση Χρηστών</a>
        </div>
    </main>
    <script>
        //Function to get option from the country select
        function getOption() {

            var country = document.getElementById("countrySelect").value;

            // Send an AJAX request to fetch cities for the selected country

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../../../utils/get_cities.php?country=" + country, true);
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