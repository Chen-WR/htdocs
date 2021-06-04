<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/config.php";
include('user.php');
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email_input = mysqli_escape_string($conn, $_POST['email']);
    $username_input = mysqli_escape_string($conn, $_POST['username']);
    $password_input = mysqli_escape_string($conn, $_POST['password']);
    $confirm_password_input = mysqli_escape_string($conn, $_POST['confirm_password']);
    $user = new User($email_input, $username_input, $password_input, $confirm_password_input);
    $dataArray = $user->getData($conn);
    $errorArray = $user->getError();
    $email = $dataArray['email'];
    $username = $dataArray['username'];
    $password = $dataArray['password'];
    if (count($errorArray)>0){
    }
    else{
        $query = "INSERT INTO user (email, username, password) VALUES('$email', '$username', '$password')";
        mysqli_query($conn, $query);
        mysqli_close($conn);
        header("location: login.php");
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
		<header><p>Sign Up</p></header>
        <p>All Field Required For Registration</p>
		<p id='error-array'>
            <?php
                if (isset($errorArray)){     
                    if (count($errorArray)>0){
                        echo '<p>'.'Please correct the error below'.'</p>';
                        foreach ($errorArray as $error){
                            echo '<li>'.$error.'</li>';
                        }
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
            <div>
                <label>Username</label>
            </div>
            <div>
                <input type="text" name="username" value="" placeholder='5 or more characters'>
            </div>
            <div>
                <label>Password</label>
            </div>
            <div>
                <input type="password" name="password" value="" placeholder='12 or more characters'>
            </div>
            <div>
                <label>Confirm Password</label>
            </div>
            <div>
                <input type="password" name="confirm_password" value="" placeholder='must be same as password'>
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
