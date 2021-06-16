<?php
    require('../config/pre_login.php');
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email_or_username_input = mysqli_escape_string($conn, $_POST['email_or_username']);
        $password_input = mysqli_escape_string($conn, $_POST['password']);
        $code = $user->login($email_or_username_input, $password_input);
        if ($code==1){
            $rows = $user->getRows();
            $_SESSION["login"] = true;
            $_SESSION['status'] = true;
            $_SESSION["id"] = $rows[0]['id'];
            $_SESSION["firstname"] = $rows[0]['firstname'];
            $_SESSION["lastname"] = $rows[0]['lastname'];
            $_SESSION["email"] = $rows[0]['email'];
            $_SESSION["username"] = $rows[0]['username'];
            if ($rows[0]['profile_pic'] == null){
                $_SESSION['profile_pic'] = "../image/default_profile_pic.jpg";
            }
            elseif ($rows[0]['profile_pic'] != null){
                $_SESSION["profile_pic"] = $rows[0]['profile_pic'];
            }
            header("location: ../component/home.php");
        }
        elseif ($code==0){
            $errorArray = $user->getError();
        }					

							
	// 						//please add this to the login for chat
    //                         $query = "INSERT INTO login_details(user_id) VALUES (?)";
    //                         $stmt = $link->prepare($query);
    //                         $stmt->bind_param("i", $val1);
    //                         $val1 = $id;
    //                         $stmt->execute();
    //                         $_SESSION['login_details_id'] = $link->insert_id;
	// 						mysqli_close($aVar);				
    }
?>
<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
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


 
