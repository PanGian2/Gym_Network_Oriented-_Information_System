<?php
session_start();
//Αυθεντικοποίηση απλού χρήστη. Πρέπει να είναι συνδεδεμένος. 
//Σε περίτπωση που δεν ισχύει γίνεται ανακατεύθυνση στην αρχική σελίδα
if (!isset($_SESSION["loggedin"])) {
    require_once("flash_messages.php");
    $_SESSION["notLoggedIn"] = true;
    flash("notLoggedIn", "Πρέπει πρώτα να συνδεθείτε για να πλοηγηθήτε σε αυτή την σελίδα", FLASH_WARNING);
    header("Location: ../simple_user/home.php");
}
