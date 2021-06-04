<?php
    include('header.php');
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
        </div>
        <div>
            <?php
                $rows = get_video($conn);
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

<?php
    if(!isset($_SESSION["login"]) or $_SESSION["login"] !== true){
        header("location: ../account/login.php");
        exit;
    }
   if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['video_upload'])){
            upload_video($conn);
        }
   }

    function upload_video($conn){
        // 1MB = 1048576 Bytes
        $count = 0;
        $maxsize = 10485760;
        $allow_extension = array("mp4","avi","3gp","mov","mpeg");
        if (!file_exists('../video')) {
            mkdir('../video', 0777, true);
        }
        $filedir = "../video/";
        $filename = $_FILES['video_file']['name'];
        $filesize = $_FILES['video_file']['size'];
        $file = $filedir.$filename;
        while (true){
            if (file_exists($file)){
                $file = $filedir.$count.$filename;
                $count++;
            }
            else{
                break;
            }
        }
        $filetype = strtolower(pathinfo($file,PATHINFO_EXTENSION));
        if (in_array($filetype,$allow_extension)){
            if ($filesize > $maxsize or $filesize == 0){
                echo 'File must be less than 1MB';
            }
            else{
                if(move_uploaded_file($_FILES['video_file']['tmp_name'],$file)){
                    $query = "INSERT INTO video (name, location, user_id) VALUES (?,?,?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('sss', $filename, $file, $_SESSION['id']);
                    $success = $stmt->execute();
                    if ($success){
                        echo 'Upload Successful';
                        echo "<meta http-equiv='refresh' content='4'>";
                    }
                    else{
                        echo 'Upload Failed';
                    }
                }
            }
        }
        else{
            echo "Invalid File Extension";
        }
    }
    function get_video($conn){
        $query = "SELECT * FROM video WHERE user_id=? ORDER BY timestamp DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }
?>


