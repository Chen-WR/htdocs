<?php
    require_once "../config/connection.php";
    include('navbar.php');
    session_start();
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
   else{
       get_video($conn);
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
        
        // $fetchVideos = mysqli_query($conn, "SELECT * FROM videos where user_id = '".$_SESSION['id']."' ORDER BY id DESC");

        // while($row = mysqli_fetch_assoc($fetchVideos)){
        //   $location = $row['location'];
        //   $name = $row['name'];
        //   $time = date("F j, Y, g:i a", $row['time_stamp']);
        
        //   echo "<br>";  
        //   echo "<div style=text-align:center>";
        //   echo $name; 
        //   echo "<br>";
        //   echo $time;
        //   echo "<br>";
        //   echo "<video src='".$location."' controls width='800px' height='600px' >";
        //   echo "</div>";
    }
?>


