<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['password']);
unset($_SESSION['usrID']);
session_destroy();
header("Location: index.php");
?>