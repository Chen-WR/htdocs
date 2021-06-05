<?php
	require_once "../config/connection.php";
	require('navbar.php');
    require('../user/user_function.php');
	session_start();
	if(!isset($_SESSION["login"]) or $_SESSION["login"] !== true){
		header("location: ../account/login.php");
		exit;
	}	
?>