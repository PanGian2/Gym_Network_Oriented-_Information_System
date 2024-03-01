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
    <!-- Styles & Favicon -->
    <link rel="shortcut icon" href="../../imgs/arm_11878800.png" type="image/x-icon">
    <link rel="stylesheet" href="../../styles/yellow_button.css">
    <style>
        .list-group-item {
            margin: 0.7% 0.5%;
            background-color: #ffe135;
            border: 1px solid #d0bf02 !important;
            border-radius: 5px;
            padding: 1% 0;
            color: black;
        }

        .list-group-item:hover {
            background-color: #f4ca16;
        }
    </style>
</head>

<body>
    <?php require_once("../partials/authenticateAdmin.php") ?>
    <?php include("../partials/admin_navbar.php") ?>
    <main>

        <section id="welcome" class="text-center container-fluid">
            <h1 class="text-center my-5">Καλωσήλθατε στην Σελίδα Διαχέιρισης</h1>
            <h2 class="mb-4">Επιλέξτε μία από τις σελίδες διαχείρισης</h2>
            <div class="list-group list-group-horizontal-lg text-center">
                <a href="admin_reg_forms.php" class="list-group-item list-group-item-action rounded-end ">Αιτήματα</a>
                <a href="users/show.php" class="list-group-item list-group-item-action ">Χρήστες</a>
                <a href="trainers/show.php" class="list-group-item list-group-item-action ">Γυμναστές</a>
                <a href="programs/index.php" class="list-group-item list-group-item-action ">Προγράμματα</a>
                <a href="calendars/index.php" class="list-group-item list-group-item-action">Ημερολόγιο</a>
                <a href="announcements/show.php" class="list-group-item list-group-item-action  ">Ανακοινώσεις</a>
            </div>
        </section>

    </main>
</body>

</html>