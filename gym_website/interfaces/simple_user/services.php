<?php session_start(); ?>
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
    <link rel="stylesheet" href="../../styles/services.css">
</head>

<body>
    <?php include '../partials/navbar.php'; ?>

    <main>
        <h1>Υπηρεσίες</h1>

        <section id="serv">
            <div class="container mb-3">
                <div class="row">
                    <?php
                    $url = "http://localhost:9999/GymWService/rest/programs";

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    $data = json_decode($response);
                    if ($data == null) {
                        echo "<h2>Ακόμα δεν έχουμε δημιουργήσει κάποια υπηρεσία!</h2>";
                    } else {

                        foreach ($data as $post) {
                            echo "<div class='col-lg-3 col-md-6'>
                                <a href='programs.php?id=", $post->pid, "' aria-expanded='true' aria-label='Click image to go to the page of the program'><img src=", $post->img_url, " class='img-fluid' alt='Image of the program'></a>
                                <h2><a class='nameServ' href='programs.php?id=", $post->pid, "'>", $post->program_name, "</a></h2>
                            </div>";
                        }
                    }



                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../partials/footer.php'; ?>

</body>

</html>