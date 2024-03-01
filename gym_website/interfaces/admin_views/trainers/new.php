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
            <h1 class="text-center my-2">Δημιουργία Γυμναστή/στριας</h1>
            <div class="col-6 offset-3">
                <form action="../../../utils/submitData.php" method="POST">
                    <input type="hidden" name="formType" value="trainerCreate">
                    <div class="mb-3">
                        <label class="form-label" for="name">Ονομα</label>
                        <input class="form-control " type="text" id="name" name="name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="surname">Επιθετο</label>
                        <input class="form-control" type="text" id="surname" name="last_name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-group">
                            <span class="input-group-text" id="email-label">@</span>
                            <input type="email" class="form-control " id="email" aria-label="email" aria-describedby="email-label" name="email" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="phone">Τηλέφωνο</label>
                        <div class="input-group">
                            <span class="input-group-text" id="phone-label">+30</span>
                            <input type="tel" pattern="[0-9]{10}" class="form-control " id="phone" aria-label="phone" aria-describedby="phone-label" name="phone_number" required />

                        </div>
                    </div>

                    <div class="my-4">
                        <button class="btn btn-info" type="submit">Δημιουργία Γυμναστή/στριας </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container text-center">
            <a href="show.php" class="btn btn-success my-4">Επιστροφή στην Διαχείριση Γυμναστών</a>
        </div>
    </main>
</body>

</html>