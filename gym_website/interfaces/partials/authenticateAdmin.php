<?php
session_start();
//Αυθεντικοποίηση διαχειριστή. Πρέπει να είναι συνδεδεμένος και ο τύπος του να είναι admin. 
//Σε περίτπωση που δεν ισχύει κάποιο από τα δύο γίνεται ανακατεύθυνση στην αρχική σελίδα
if (!isset($_SESSION["loggedin"])) {
    $_SESSION["notLoggedIn"] = true;
    require_once("../partials/flash_messages.php");
    flash("notLoggedIn", "Πρέπει πρώτα να συνδεθείτε για να πλοηγηθήτε σε αυτή την σελίδα", FLASH_WARNING);
    header("Location: ../simple_user/home.php");
} elseif ($_SESSION["type"] !== "admin") {
    header('X-PHP-Response-Code: 403', true, 403);
    $_SESSION["unauthorized"] = true;
    require_once("../partials/flash_messages.php");
    flash("notAdmin", "Απαγορεύεται η πρόσβαση σε αυτή την σελίδα", FLASH_ERROR);
    header("Location: ../simple_user/home.php");
}
