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
            <h1>Welcome to <?php echo $user->getFirstname().' '.$user->getLastname();?> Channel</h1>
            <form method="post" action="" enctype='multipart/form-data'>
                <div style="width:20%;margin:0 auto;" class="mb-3">
                    <label for="formFileSm" class="form-label">Upload Video</label>
                    <input class="form-control form-control-sm" id="formFileSm" type="file" name='video_file'>
                    <input type='submit' value='Upload' name='video_upload'>
                </div>
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
        <div class="row row-cols-1 row-cols-md-6 g-4 ">
            <?php
                $rows = $user->getVideo();
                foreach($rows as $row){
            ?>
            <div class="col">
                <div class="card" style="width: 10rem;">
                    <a href="watch_video.php?video_id=<?php echo $row['video_id']?>"><img src="<?php echo $row['video_preview']?>" class="card-img-top" alt="Test"></a>
                    <div class="card-body">
                        <a href="watch_video.php?video_id=<?php echo $row['video_id']?>"><h5 class="card-title"><?php echo pathinfo($row['video_name'], PATHINFO_FILENAME)?></h5></a>
                        <p class="card-text"><?php echo $row['upload_time']?></p>
                    </div>
                </div>
            </div> 
            <?php
                }
            ?>
        </div>
    </body>
</html>



