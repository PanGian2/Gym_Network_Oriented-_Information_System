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
    // Handle the "delete" request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = trim($_POST["userId"]);
        $url = "http://localhost:9999/GymWService/rest/users/" . $userId;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_HEADER, true);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
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
    if (isset($_SESSION["createSuccess"]) && $_SESSION["createSuccess"] == true) {
        flash("createSuccess");
        $_SESSION["createSuccess"] = false;
    }
    if (isset($_SESSION["createError"]) && $_SESSION["createError"] == true) {
        flash("createError");
        $_SESSION["createError"] = false;
    }
    ?>
    <main>

        <section id="form_tables" class="table-responsive  align-items-center my-3 mx-2">
            <h1 class="text-center">Όλοι οι χρήστες</h1>
            <div>
                <a href="new.php" class="btn btn-success my-2">Add User</a>
            </div>
            <table class="table table-secondary table-hover caption-top">
                <caption>Λίστα με όλους τους χρήστες</caption>
                <thead>
                    <tr class="table-dark">
                        <th></th>
                        <th scope="col">Ρόλος</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Ονομα</th>
                        <th scope="col">Επιθετο</th>
                        <th scope="col">Χωρα</th>
                        <th scope="col">Πολη</th>
                        <th scope="col">Διευθυνση</th>
                        <th scope="col">Ακυρώσεις</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $url = "http://localhost:9999/GymWService/rest/users";

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    $data = json_decode($response);

                    foreach ($data as $post) {
                        echo "<tr>";
                        echo "<td scope='row' style='width: 14%;'>
                        <a class='btn my-1' href='edit.php?id=", $post->userId, "' style='background-color: #ffdd00;'>Edit</a>
                        <form class='d-inline me-0' action=", htmlspecialchars($_SERVER["PHP_SELF"]), " method='POST'>
                            <input type='hidden' name='userId' value='", $post->userId, "'>
                            <button class='btn btn-danger' type='submit'>Delete</button>
                        </form>
                        </td>";
                        echo "<td>", $post->type . "</td>";
                        echo "<td>", $post->username . "</td>";
                        echo "<td>", $post->email . "</td>";
                        echo "<td>", $post->name . "</td>";
                        echo "<td>", $post->last_name . "</td>";
                        echo "<td>", $post->country . "</td>";
                        echo "<td>", $post->city . "</td>";
                        echo "<td>", $post->address . "</td>";
                        echo "<td>", $post->cancellations . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

    </main>
</body>

</html>