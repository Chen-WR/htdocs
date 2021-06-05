<?php
    require('../config/pre_login.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email_input = $conn->real_escape_string($_POST['email']);
        $username_input = $conn->real_escape_string($_POST['username']);
        $password_input = $conn->real_escape_string($_POST['password']);;
        $confirm_password_input = $conn->real_escape_string($_POST['confirm_password']);
        $user = new UserAccount($conn);
        $code = $user->registration($email_input, $username_input, $password_input, $confirm_password_input);
        if ($code==1){
            header("location: login.php");
        }
        elseif ($code==0){
            $errorArray = $user->getError();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
    </head>
    <body>
        <div style='text-align:center;' class="container">
            <header><p><b>Sign Up</b></p></header>
            <p>All Field Required For Registration</p>
            <p id='error-array'>
                <?php
                    if (isset($errorArray)){     
                        if (count($errorArray)>0){
                            echo '<p>'.'Please correct the error below'.'</p>';
                        }
                    }
                ?>
            </p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <label>Email Address</label>
                </div>
                <div>
                    <input type="text" name="email" value="" placeholder='abc@domain.com'>
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['email_error'])){
                            echo $errorArray['email_error'];
                        }
                    ?>
                </div>
                <div>
                    <label>Username</label>
                </div>
                <div>
                    <input type="text" name="username" value="" placeholder='5 or more characters'>
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['username_error'])){
                            echo $errorArray['username_error'];
                        }
                    ?>
                </div>
                <div>
                    <label>Password</label>
                </div>
                <div>
                    <input type="password" name="password" value="" placeholder='12 or more characters'>
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['password1_error'])){
                            echo $errorArray['password1_error'];
                        }
                    ?>
                </div>
                <div>
                    <label>Confirm Password</label>
                </div>
                <div>
                    <input type="password" name="confirm_password" value="" placeholder='must be same as password'>
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['password2_error'])){
                            echo $errorArray['password2_error'];
                        }
                    ?>
                </div>
                <div>
                    <input type="submit" class="button" value="Submit">
                    <input type="reset" class="button" value="Reset">
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
        </div>   
    </body>
</html>
 

