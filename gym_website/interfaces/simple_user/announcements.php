<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Google fonts links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Styles and favicon -->
    <link rel="stylesheet" href="../../styles/yellow_button.css">
    <link rel="stylesheet" href="announcement.css">
    <link rel="shortcut icon" href="../../../imgs/arm_11878800.png" type="image/x-icon">
</head>

<body>
    <?php require_once('../partials/authenticateUser.php'); ?>
    <?php include('../partials/navbar.php'); ?>


    <main>
        <section class="container-fluid">
            <div class="row my-3 text-center gx-3 gy-2">
                <h1 class="mb-4">Τελευταία Νέα</h1>

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
                        echo "<div class='p-1 border' style='height: 100%'>";

                        echo "<h2>", $post->title . "</h2>";
                        date_default_timezone_set("Europe/Athens");
                        $d = date("d-m-Y", strtotime($post->dateposted));
                        echo "<time>", $d, "</time>";
                        echo "<p>", $post->content . "</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
        </section>
    </main>
</body>

</html>