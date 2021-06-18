<?php
    require('../config/post_login.php');
    $video_id = $_GET['video_id'];
    if(isset($_GET['video_id'])){
        $src = $user->watchVideo($video_id);
    }
?>
<!DOCTYPE html>
    <body>
        <video width="320" height="240" controls autoplay>
            <source src="<?php echo $src?>">
    </body>
</html>