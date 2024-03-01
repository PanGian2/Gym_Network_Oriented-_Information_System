<?php
// Βρίσκουμε το απόλυτο path για τον φάκελο "admin"
$adminPath = 'interfaces/admin_views';

// Επιλέγουμε τον φάκελο ανάλογα με το αρχείο που καλεί το navbar.php
$currentFile = $_SERVER['PHP_SELF'];
$currentDir = dirname($currentFile);

// Αν το αρχείο καλείται από υποφάκελο, προσαρμόζουμε το path
if ($currentDir != '/') {
    $adminPath .= str_repeat('../', substr_count($currentDir, '/'));
}
?>
<header>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $adminPath; ?>admin_home.php" style="color: yellow;">DS Gym Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">DS Gym Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?php echo $adminPath; ?>admin_home.php">Home</a>
                        </li>
                        <li class=" nav-item">
                            <a class="nav-link" href="<?php echo $adminPath; ?>admin_reg_forms.php">Aιτήματα</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $adminPath; ?>users/show.php">Χρήστες</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="services.html" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Δομικά Στοιχεία
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo $adminPath; ?>trainers/show.php">Γυμναστές</a></li>
                                <li><a class="dropdown-item" href="<?php echo $adminPath; ?>programs/index.php">Προγράμματα</a></li>
                                <li><a class="dropdown-item" href="<?php echo $adminPath; ?>calendars/index.php">Ημερομηνίες Προγραμμάτων</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $adminPath; ?>announcements/show.php">Ανακοινώσεις</a>
                        </li>
                    </ul>
                    <a href="<?php echo $adminPath; ?>../partials/logout.php" class="y_button btn mb-3 mb-lg-0" style="color:black;">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</header>