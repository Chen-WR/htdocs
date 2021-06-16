<?php
    require('../config/post_login.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstname_input = $conn->real_escape_string($_POST['firstname']);
        $lastname_input = $conn->real_escape_string($_POST['lastname']);
        $email_input = $conn->real_escape_string($_POST['email']);
        $username_input = $conn->real_escape_string($_POST['username']);
        $code = $user->EditUserInfo($firstname_input, $lastname_input, $email_input, $username_input);
        $user->setProfilepic($_FILES['picture_file']['name'], $_FILES['picture_file']['size'], $_FILES['picture_file']['tmp_name']);
        if ($code==1){
            $_SESSION["firstname"] = $user->getFirstname();
            $_SESSION["lastname"] = $user->getLastname();
            $_SESSION["email"] = $user->getEmail();
            $_SESSION["username"] = $user->getUsername();
            $_SESSION["profile_pic"] = $user->getProfilepic();
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
        <title>Profile</title>
    </head>
    <body>
        <div style='text-align:center;' class="container">
            <header><p><b>Edit Profile</b></p></header>
            <p id='error-array'>
                <?php
                    if (isset($errorArray)){     
                        if (count($errorArray)>0){
                            echo '<p>'.'Please correct the error below'.'</p>';
                        }
                    }
                    if (isset($code)){
                        if ($code == 1){
                            echo "User Account Info Updated";
                        }
                    }
                ?>
            </p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div>
                    <label>Profile Picture</label>
                </div>
                <div>
                    <img src="<?php echo $user->getProfilepic();?>" width="200px">
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['size_error'])){
                            echo $errorArray['size_error'];
                        }
                        elseif (isset($errorArray['upload_error'])){
                            echo $errorArray['upload_error'];
                        }
                        elseif (isset($errorArray['extension_error'])){
                            echo $errorArray['extension_error'];
                        }
                    ?>
                </div>
                <div>
                    <input type='file' name='picture_file'>
                </div>
                <div>
                    <label>First Name</label>
                </div>
                <div>
                    <input type="text" name="firstname" value="<?php echo $user->getFirstname();?>">
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['firstname_error'])){
                            echo $errorArray['firstname_error'];
                        }
                    ?>
                </div>
                <div>
                    <label>Last Name</label>
                </div>
                <div>
                    <input type="text" name="lastname" value="<?php echo $user->getLastname();?>">
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['lastname_error'])){
                            echo $errorArray['lastname_error'];
                        }
                    ?>
                </div>
                <div>
                    <label>Email</label>
                </div>
                <div>
                    <input type="email" name="email" value="<?php echo $user->getEmail();?>" required>
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
                    <input type="text" name="username" value="<?php echo $user->getUsername();?>" required>
                </div>
                <div style="color:red;">
                    <?php
                        if (isset($errorArray['username_error'])){
                            echo $errorArray['username_error'];
                        }
                    ?>
                </div>
                <div>
                    <input type="submit" class="button" value="Submit">
                </div>
            </form>
        </div>   
    </body>
</html>
 