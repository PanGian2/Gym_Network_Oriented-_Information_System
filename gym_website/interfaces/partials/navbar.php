<header>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../simple_user/home.php" style=" color: yellow;">DS Gym</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="color: yellow;">DS Gym</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../simple_user/home.php">Αρχική</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../simple_user/make_reservation.php">Κλείσε Ραντεβού</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                                Υπηρεσίες
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../simple_user/services.php">Όλες οι υπηρεσίες</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <?php
                                //Display only four programs
                                $url = "http://localhost:9999/GymWService/rest/programs";

                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($curl);
                                $data = json_decode($response);
                                $x = 0;
                                if ($data != null) {
                                    foreach ($data as $post) {
                                        if ($x < 4) {
                                            echo "<li><a class='dropdown-item' href='../simple_user/programs.php?id=", $post->pid, " '> ", $post->program_name, " </a></li>";
                                            $x++;
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    //If the user is logged in display the user profile link too
                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                        echo '<a href="../simple_user/user_profile.php" class="nav-link my-2 me-3">Ο λογαριασμός μου</a>';
                        echo '<a class="y_button btn my-2 my-lg-0"  href="../partials/logout.php" style="color:black;">Logout</a>';
                    } else {
                        echo '<a class="y_button btn my-2 my-lg-0"  href="../partials/login.php" style="color:black;">Login/Register</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>
</header>