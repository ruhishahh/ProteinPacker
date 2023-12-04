<?php
	require_once "config.php";
    session_start();

    if (isset($_SESSION['usrID']))
    {
        header("Location: /frontend/registerAccountForm.php");
        exit;
    }
?>
