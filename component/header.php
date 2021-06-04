<?php
require_once "../config/connection.php";
	include('navbar.php');
	session_start();
	if(!isset($_SESSION["login"]) or $_SESSION["login"] !== true){
		header("location: ../account/login.php");
		exit;
	}	
?>