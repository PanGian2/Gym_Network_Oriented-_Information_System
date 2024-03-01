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
    <link rel="stylesheet" href="announcement.css">
</head>

<body>
    <?php require_once("../../partials/authenticateAdmin.php") ?>
    <?php include("../../partials/admin_navbar.php"); ?>

    <?php
    require("../../partials/flash_messages.php");
    //Handle the "delete" request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $anid = trim($_POST["anid"]);
        $url = "http://localhost:9999/GymWService/rest/announcements/" . $anid;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_HEADER, true);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode != 200) {
            //Something went wrong
            //The returned message is after the GMT in the initial response
            $message =   substr($response, strpos($response, "GMT") + 3);
            flash("Error", $message, FLASH_ERROR);
            flash("Error");
        } else {
            //All good
            //The returned message is after the GMT in the initial response
            $message =   substr($response, strpos($response, "GMT") + 3);
            flash("Success", $message, FLASH_SUCCESS);
            flash("Success");
        }
    }
    //Depending on the occasion, display the appropriate flash messages on top of the page
    if (isset($_SESSION["updateSuccess"]) && $_SESSION["updateSuccess"] == true) {
        flash("updateSuccess");
        $_SESSION["updateSuccess"] = false;
    }
    if (isset($_SESSION["updateError"]) && $_SESSION["updateError"] == true) {
        flash("updateError");
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
        <section class="container-fluid">
            <div class="row my-3 text-center gx-3 gy-2">
                <h1 class="mb-4">Τελευταία Νέα</h1>
                <?php
                if (isset($_SESSION["type"]) && $_SESSION["type"] == "admin") {
                    echo "<div class='mb-3'>
                    <a href='new.php' class='btn btn-success my-2'>Πρόσθεσε Ανακοίνωση</a>
                    </div>";
                }
                ?>

                <?php
                $url = "http://localhost:9999/GymWService/rest/announcements";

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                $data = json_decode($response);
                if ($data == null) {
                    echo "<p class='text-center'>Δεν υπάρχουν ανακοινώσεις!</p>";
                } else {
                    foreach ($data as $post) {
                        echo "<div class= 'col-lg-3 col-md-6'>";
                        echo "<div class='p-1 border' id='announc'>";

                        echo "<h2>", $post->title . "</h2>";
                        date_default_timezone_set("Europe/Athens");
                        $d = date("d-m-Y", strtotime($post->dateposted));
                        echo "<time>", $d, "</time>";
                        echo "<p>", $post->content . "</p>";
                        if (isset($_SESSION["type"]) && $_SESSION["type"] == "admin") {
                            echo "<a class='btn my-1' href='edit.php?id=", $post->anid, "' style='background-color: #ffdd00;'>Edit</a>
                                <form class='d-inline me-0' action=", htmlspecialchars($_SERVER["PHP_SELF"]), " method='POST'>
                                    <input type='hidden' name='anid' value='", $post->anid, "'>
                                    <button class='btn btn-danger' type='submit'>Delete</button>
                                </form>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
        </section>
    </main>
</body>

</html>