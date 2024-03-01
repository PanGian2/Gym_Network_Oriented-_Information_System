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

<body class="d-flex flex-column vh-100">
    <?php require_once("../../partials/authenticateAdmin.php") ?>
    <?php include("../../partials/admin_navbar.php") ?>
    <?php
    require("../../partials/flash_messages.php");
    //Depending on the occasion, display the appropriate flash messages on top of the page
    if (isset($_SESSION["deleteSuccess"]) && $_SESSION["deleteSuccess"] == true) {
        flash("deleteSuccess");
        $_SESSION["deleteSuccess"] = false;
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
        <section class="container mt-5">
            <h1 class="text-center mb-4">Όλα τα Προγράμματα</h1>
            <div>
                <a href="new.php" class="btn btn-success my-3">Προσθήκη Προγράμματος</a>
            </div>
            <?php
            $url = "http://localhost:9999/GymWService/rest/programs";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $data = json_decode($response);

            if ($data == null) {
                echo "<h2 class='text-center'>Δεν υπάρχουν προγράμματα!</h2>";
            } else {
                foreach ($data as $post) {
                    echo "<div class='card mb-5 '>";
                    echo "<div class='row align-items-center'>";
                    echo "<div class=' col-lg-4 col-md-12'>";
                    echo "<img src=", $post->img_url . " alt='' class='img-fluid'>";
                    echo "</div>";
                    echo "<div class='col-lg-8 col-md-12'>";
                    echo "<div class='card-body'>";
                    echo "<h2 class='card-title fs-4'>", $post->program_name . "</h2>";
                    echo "<p class='card-text'>", $post->whatdescription . "</p>";
                    if ($post->type == 1) {
                        echo "<p class='card-text'><small class='text-muted'>Τύπος: Ατομικό</small></p>";
                    } else {
                        echo "<p class='card-text'><small class='text-muted'>Τύπος: Ομαδικό</small></p>";
                    }
                    echo "<a href='show.php?id=", $post->pid, "' class='btn btn-primary'>View ", $post->program_name, "</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }

            ?>

        </section>

    </main>
</body>

</html>