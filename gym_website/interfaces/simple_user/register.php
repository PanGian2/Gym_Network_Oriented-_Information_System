<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
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

<body style="background-color: #343a40;">
    <?php include '../partials/navbar.php'; ?>
    <?php
    require_once("../partials/flash_messages.php");

    $username = $password = $email = $name = $last_name = $country = $city = $address =  "";
    $username_err = $password_err = $email_err = $name_err = $last_name_err = $country_err = $city_err = $address_err = $register_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if username is empty
        if (empty(trim($_POST["username"]))) {
            $username_err = "Παρακαλώ εισάγετε το username σας";
        } else {
            $username = trim($_POST["username"]);
        }

        if (empty(trim($_POST["password"]))) {
            $password_err = "Παρακαλώ εισάγετε το password σας";
        } else {
            $password = trim($_POST["password"]);
        }

        // Check if password is empty
        if (empty(trim($_POST["email"]))) {
            $email_err = "Παρακαλώ εισάγετε το email σας";
        } else {
            $email = trim($_POST["email"]);
        }

        // Check if name is empty
        if (empty(trim($_POST["name"]))) {
            $name_err = "Παρακαλώ εισάγετε το όνομά σας";
        } else {
            $name = trim($_POST["name"]);
        }

        // Check if last name is empty
        if (empty(trim($_POST["last_name"]))) {
            $last_name_err = "Παρακαλώ εισάγετε το επίθετό σας";
        } else {
            $last_name = trim($_POST["last_name"]);
        }

        // Check if country is empty
        if (empty(trim($_POST["country"]))) {
            $country_err = "Παρακαλώ εισάγετε τη χώρα σας";
        } else {
            $country = trim($_POST["country"]);
        }

        // Check if city is empty
        if (empty(trim($_POST["city"]))) {
            $city_err = "Παρακαλώ εισάγετε τη πόλη σας";
        } else {
            $city = trim($_POST["city"]);
        }

        // Check if address is empty
        if (empty(trim($_POST["address"]))) {
            $address_err = "Παρακαλώ εισάγετε τη διεύθυνσή σας.";
        } else {
            $address = trim($_POST["address"]);
        }


        if (empty($username_err) && empty($password_err) && empty($email_err) && empty($name_err) && empty($last_name_err) && empty($country_err) && empty($city_err) && empty($address_err)) {

            $url = "http://localhost:9999/GymWService/rest/users";

            //Json array containing all user data. The password is being hashed
            $User = array(
                "username" => $username,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "email" => $email,
                "name" => $name,
                "last_name" => $last_name,
                "country" => $country,
                "city" => $city,
                "address" => $address,
                "type" => "noRole",
                "status" => "pending",
            );
            $jsonUser = json_encode($User, JSON_UNESCAPED_UNICODE);
            //Create user
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonUser);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpcode != 200) {
                //Something went wrong
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["createError"] = true;
                flash("createError", $message, FLASH_ERROR);
                echo "<script>location.href = 'home.php';</script>";
            } else {
                //All went well
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["createSuccess"] = true;
                flash("createSuccess", $message, FLASH_SUCCESS);
                echo "<script>location.href = 'home.php';</script>";
            }
        }
    }
    ?>
    <main>
        <div class="container align-items-center mt-5">
            <div class="row justify-content-center my-3">
                <div class="card mb-3 px-0 pb-0 w-100" style="max-width: 1200px;">
                    <div class="row g-0 mx-0">
                        <div class="col-md-8">
                            <div class="card-body">
                                <h1 class="card-title fs-3 mb-3">Εγγραφή</h1>
                                <?php
                                if (!empty($register_err)) {
                                    echo '<div class="alert alert-danger">' . $register_err . '</div>';
                                }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="floatingInput" name="username" placeholder="Username">
                                        <label for="floatingInput">Username</label>
                                        <span class="invalid-feedback"><?php echo $username_err; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="floatingPassword" name="password" placeholder="Password">
                                        <label for="floatingPassword">Password</label>
                                        <span class="invalid-feedback"><?php echo $password_err; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="emailId" name="email" placeholder="Email">
                                        <label for="emailId">Email</label>
                                        <span class="invalid-feedback"><?php echo $email_err; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" id="nameId" name="name" placeholder="Όνομα">
                                        <label for="nameId">Όνομα</label>
                                        <span class="invalid-feedback"><?php echo $name_err; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" id="lastnameId" name="last_name" placeholder="Επώνυμο">
                                        <label for="lastnameId">Επώνυμο</label>
                                        <span class="invalid-feedback"><?php echo $last_name_err; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select name='country' class='form-select <?php echo (!empty($country_err)) ? 'is-invalid' : ''; ?>' id='countrySelect' onchange='getOption();'>
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
                                        <span class='invalid-feedback'><?php echo $country_err ?>;
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class='form-select <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>' name="city" id="citySelect">
                                            <option selected value=''>Επέλεξε Πόλη</option>";
                                        </select>
                                        <span class="invalid-feedback"><?php echo $city_err; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" name="address" id="addressId" placeholder="Διεύθυνση">
                                        <label for="addressId">Διεύθυνση</label>
                                        <span class="invalid-feedback"><?php echo $address_err; ?>
                                    </div>
                                    <button type="submit" class="y_button btn">Εγγραφή</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 px-0 text-center">
                            <img src="https://images.unsplash.com/photo-1549476464-37392f717541?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NDB8fGd5bXxlbnwwfHwwfHx8MA%3D%3D" class="img-fluid rounded-end" style="height: 100%;" alt="...">
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>
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