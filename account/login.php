<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/config.php";
include('user.php');

session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: home.php");
  exit;
}

$username = $password = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
							
							
							$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");
							$iid = mysqli_query($aVar,"SELECT pic FROM users WHERE id = '$id'");
							if($row = mysqli_fetch_assoc($iid))
							{
							
		 					$pic = $row['pic'];
		 					$_SESSION["pic"] = $pic;
							}
							mysqli_close($aVar);
							
							//please add this to the login for chat
                            $query = "INSERT INTO login_details(user_id) VALUES (?)";
                            $stmt = $link->prepare($query);
                            $stmt->bind_param("i", $val1);
                            $val1 = $id;
                            $stmt->execute();
                            $_SESSION['login_details_id'] = $link->insert_id;
							mysqli_close($aVar);
							                            

							
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
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
        <h2>Login</h2>
        <p>Please fill in your credentials to login</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username or Email</label>
            </div>
            <div>
                <input type="text" name="username">
            </div>  
            <div>
                <label>Password</label>
            </div>
            <div>
                <input type="password" name="password">
            </div>
            <div>
                <input type="submit" class="button" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>