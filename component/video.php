<?php
    require('../config/post_login.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['video_upload'])){
            $code = $user->setVideo($_FILES['video_file']['name'], $_FILES['video_file']['size'], $_FILES['video_file']['tmp_name']);
            if ($code == 1){
                echo '<div style="color:red; text-align:center;">'.'Upload Success'.'</div>';
            }
            elseif ($code == 0){
                echo '<div style="color:red; text-align:center;">'.'Upload Failed'.'</div>';
                $errorArray = $user->getError();
            }
        }
   }
?>

<!DOCTYPE html>
<html>
    <body>
        <div style="text-align:center;">
            <h1>Welcome to <?php echo $_SESSION['username']?>'s Channel</h1>
            <form method="post" action="" enctype='multipart/form-data'>
                <input type='file' name='video_file'>
                <input type='submit' value='Upload' name='video_upload'>
            </form>
            <p id='error-array'>
                <?php
                    if (isset($errorArray)){     
                        if (count($errorArray)>0){
                            echo '<p>'.'Please correct the error below'.'</p>';
                            foreach($errorArray as $error){
                                echo '<p style="color:red;">'.$error.'</p>';
                            }
                        }
                    }
                ?>
            </p>
        </div>
        <div>
            <?php
                $rows = $user->getVideo();
                foreach($rows as $row){
                    echo '<div style="text-align:center;">';
                    echo 'Title:'.'<p><b><STRONG>'.$row["name"].'</STRONG></b></p>';
                    echo 'Upload Time: '.$row["timestamp"];
                    echo '</div>';
                    echo '<div  style="text-align:center">';
                    echo '<video src="'.$row["location"].'" controls width="480px" height="360px">';
                    echo '</div>';
                }
            ?>
        </div>
    </body>
</html>



