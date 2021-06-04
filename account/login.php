<?php
require_once "../config/config.php";
include('user.php');

session_start();
 
if(isset($_SESSION["login"]) && $_SESSION["login"] === true){
  header("location: ../component/home.php");
  exit;
}
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email_or_username_input = mysqli_escape_string($conn, $_POST['email_or_username']);
    $password_input = mysqli_escape_string($conn, $_POST['password']);
    $user = new User($conn);
    $code = $user->login($email_or_username_input, $password_input);
    if ($code==0){
        $_SESSION["login"] = true;
        header("location: ../component/home.php");
    }
    elseif ($code==1){
        $errorArray = $user->getError();
    }
    
    

        
    //     if($stmt = mysqli_prepare($link, $sql)){
    //         // Bind variables to the prepared statement as parameters
    //         mysqli_stmt_bind_param($stmt, "s", $param_username);
            
    //         // Set parameters
    //         $param_username = $username;
            
    //         // Attempt to execute the prepared statement
    //         if(mysqli_stmt_execute($stmt)){
    //             // Store result
    //             mysqli_stmt_store_result($stmt);
                
    //             // Check if username exists, if yes then verify password
    //             if(mysqli_stmt_num_rows($stmt) == 1){                    
    //                 // Bind result variables
    //                 mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
    //                 if(mysqli_stmt_fetch($stmt)){
    //                     if(password_verify($password, $hashed_password)){
    //                         // Password is correct, so start a new session
    //                         session_start();
                            
    //                         // Store data in session variables
    //                         $_SESSION["loggedin"] = true;
    //                         $_SESSION["id"] = $id;
    //                         $_SESSION["username"] = $username;
							
							
	// 						$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");
	// 						$iid = mysqli_query($aVar,"SELECT pic FROM users WHERE id = '$id'");
	// 						if($row = mysqli_fetch_assoc($iid))
	// 						{
							
	// 	 					$pic = $row['pic'];
	// 	 					$_SESSION["pic"] = $pic;
	// 						}
	// 						mysqli_close($aVar);
							
	// 						//please add this to the login for chat
    //                         $query = "INSERT INTO login_details(user_id) VALUES (?)";
    //                         $stmt = $link->prepare($query);
    //                         $stmt->bind_param("i", $val1);
    //                         $val1 = $id;
    //                         $stmt->execute();
    //                         $_SESSION['login_details_id'] = $link->insert_id;
	// 						mysqli_close($aVar);
							                            

							
    //                         // Redirect user to welcome page
    //                         header("location: welcome.php");
    //                     } else{
    //                         // Display an error message if password is not valid
    //                         $password_err = "The password you entered was not valid.";
    //                     }
    //                 }
    //             } else{
    //                 // Display an error message if username doesn't exist
    //                 $username_err = "No account found with that username.";
    //             }
    //         } else{
    //             echo "Oops! Something went wrong. Please try again later.";
    //         }

    //         // Close statement
    //         mysqli_stmt_close($stmt);
    //     }
    // }
    
    // // Close connection
    // mysqli_close($link);
}
?>
 
<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
    </head>
<body>
    <div style='text-align:center;' class="container">
        <header><p><b>Login</b></p></header>
        <p>Please fill in your credentials to login</p>
        <p id='error-array' style="color:red;">
            <?php
                if (isset($errorArray['login_error'])){     
                   echo $errorArray['login_error'];
                }
            ?>
        </p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username or Email</label>
            </div>
            <div>
                <input type="text" name='email_or_username'>
            </div> 
            <div style="color:red;">
                <?php
                if (isset($errorArray['email_or_username_error'])){
                    echo $errorArray['email_or_username_error'];
                }
                ?>
            </div>
            <div>
                <label>Password</label>
            </div>
            <div>
                <input type="password" name="password">
            </div>
            <div style="color:red;">
                <?php
                if (isset($errorArray['password_error'])){
                    echo $errorArray['password_error'];
                }
                ?>
            </div>
            <div>
                <input type="submit" class="button" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>