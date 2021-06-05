<?php
    require_once "connection.php";
    require('../user/user_account.php');
    session_start();
    if(isset($_SESSION["login"]) and $_SESSION["login"] === true){
        header("location: ../component/home.php");
        exit;
    }	
?>