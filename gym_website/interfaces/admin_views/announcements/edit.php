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
        // Get the id from the url
        if (isset($_GET["id"])) {
            $anid = $_GET["id"];
        }
        $url = "http://localhost:9999/GymWService/rest/announcements/" . $anid;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $data = json_decode($response);
        ?>

        <div class="row gx-0 my-3">
            <h1 class="text-center my-3">Επεξεργασία Ανακοίνωσης</h1>
            <div class="col-6 offset-3">
                <form action="../../../utils/submitData.php" method="POST">
                    <input type="hidden" name="formType" value="announcementUpdate">
                    <input type="hidden" name="dateposted" value="<?php echo $data->dateposted; ?>">
                    <input type="hidden" name="id" value="<?php echo $anid ?>">
                    <div class="mb-3">
                        <label class="form-label" for="title">Τίτλος</label>
                        <input class="form-control" type="text" id="title" name="title" value="<?php echo $data->title; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="date">Ημερομηνία</label>
                        <input class="form-control" type="date" id="date" name="date" value="<?php echo $data->dateposted; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Περιεχόμενο Ανακοίνωσης</label>
                        <textarea class="form-control" rows=6 type="text" id="description" name="content" required><?php echo $data->content; ?></textarea>
                    </div>
                    <div class="my-4">
                        <button class="btn btn-info" type="submit">Ενημέρωση Ανακοίνωσης</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container text-center">
            <a href="show.php" class="btn btn-success my-4">Επιστροφή στην Διαχείριση Ανακοινώσεων</a>
        </div>

    </main>
</body>

</html>