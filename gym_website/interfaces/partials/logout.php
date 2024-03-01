<?php
//Clear session info
session_start();
$_SESSION = array();
session_destroy();
header("location: ../simple_user/home.php");
exit;
