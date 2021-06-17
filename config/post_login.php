<?php
	require_once "../config/connection.php";
	require '../vendor/autoload.php';
	require('navbar.php');
	require('../user/user.php');
	session_start();
	$user = new User($_SESSION['status'], $_SESSION["id"], $_SESSION["firstname"], $_SESSION["lastname"], $_SESSION["email"], $_SESSION["username"], $_SESSION["profile_pic"], $conn);
	if(!isset($_SESSION["login"]) or $_SESSION["login"] !== true){
		header("location: ../account/login.php");
		exit;
	}	
?>