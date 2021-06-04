<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
    header("location: ../account/login.php");
    exit;
}	

?>
<?php


if($_SESSION["pic"] == NULL || $_SESSION["pic"] == "pics/")
{
$image = "pics/17004.png";
}
else
{
$image = $_SESSION["pic"];
}   


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
<style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" />


</head>
<body>
	<div class="container">

			
		<h1 id="title"><em>Alias</em><br>
	    <span id="group17">By Group 17</span></h1>
		


	<div class="navbar">
		
		<a href="welcome.php">Home</a>
     	<a href="reset-password.php" class="btn btn-warning">Reset Password</a>
     	<a href="../account/logout.php" class="btn btn-danger">Logout</a>
		
		</div>



		<div id="profile">
		<br />	
		<img id="proImg"src="<?php echo $image ?>" width="200px">
        <h1 id="proUsername"><b><?php echo htmlspecialchars($_SESSION["username"]); ?>'s</b> homepage</h1>
			
		</div>

		 
	<div class="toggle">
		<div class="fixer-container">
		<a href="javascript:com()">Post Comment</a>
     	<a href="javascript:pro()">Change Profile Picture</a>
		<a href="chat.php">Chat</a>
		<a href="list_pm.php">Messaging</a>
		<a href="video.php">My Channel</a>
<form action="" method="post" id="formie">
<input type="text" name="search">
<input type="submit" name="submit" value="Search Users">
</form>



		</div>
		
<div id="com"></div>
		
		</div>
		

 

	<?php


// Check if image file is a actual image or fake image
if(isset($_POST["subcom"])) {


$target_dir = "commentpics/";
$target_file = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if($target_file !== "commentpics/"){	
  $check = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
  if($check !== false) {
   // echo "File is an image - " . $check["mime"] . ". ";
    $uploadOk = 1;
  } else {
    echo "File is not an image. ";
    $uploadOk = 0;
  }

// Check file size
if ($_FILES["fileToUpload2"]["size"] > 500000) {
  echo "Sorry, your file is too large. ";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded. ";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file)) {
   // echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload2"]["name"])). " has been uploaded. ";
  } 
  else {
    echo "Sorry, there was an error uploading your file. ";
  }
}
}

//comment upload
	$block = "1";
	
    $word=$_POST['comment'];

     	if($_POST["rad"] == "radtext")
		{
		    if(preg_match("/[^a-zA-Z0-9 ]+/", $word))
    		{
    		$block = "0";
			echo '<p style="color:red">No special characters</p>';
			}
			else
			{
		 		$comment = "<b>".$_POST["comment"]."</b>";
			}
		
		}
		else
		{
		$comment = $_POST["comment"];
		}
$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");		  
	
if($aVar === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}



$num = $_SESSION['id'];
$comment_length = strlen($comment);

if($comment_length > 200)
{
header("location: welcome.php?error=1");
}
else
{
// Attempt insert query execution
if($block == "1"){
if($uploadOk == 0){
$target_file = "commentpics/";
}


$sql = "INSERT INTO commentz (id, comment, a_id, pics) VALUES ('','$comment','$num', '$target_file')";
if(mysqli_query($aVar, $sql)){
  // echo "Records inserted successfully.";

   				
   
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($aVar);
}
}

}

// Close connection
mysqli_close($aVar);

}


?>		
	<?php
	$block2 = "1";
	
	if(isset($_POST['subcom2']))
{

$target_dir = "replypics/";
$target_file = $target_dir . basename($_FILES["fileToUpload3"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if($target_file !== "replypics/"){	
  $check = getimagesize($_FILES["fileToUpload3"]["tmp_name"]);
  if($check !== false) {
   // echo "File is an image - " . $check["mime"] . ". ";
    $uploadOk = 1;
  } else {
    echo "File is not an image. ";
    $uploadOk = 0;
  }

// Check file size
if ($_FILES["fileToUpload3"]["size"] > 500000) {
  echo "Sorry, your file is too large. ";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded. ";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload3"]["tmp_name"], $target_file)) {
   // echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload2"]["name"])). " has been uploaded. ";
  } 
  else {
    echo "Sorry, there was an error uploading your file. ";
  }
}
}

//reply text

    $word=$_POST['reply'];

     	if($_POST["rad"] == "radtext")
		{
		    if(preg_match("/[^a-zA-Z0-9 ]+/", $word))
    		{
    		$block2 = "0";
			echo '<p style="color:red">No special characters</p>';
			}
			else
			{
		 		$reply = "<b>".$_POST["reply"]."</b>";
			}
		
		}
		else
		{
		$reply = $_POST["reply"];
		}
$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");	

		
		$name2 = $_SESSION["username"];
		
		$iid2 = mysqli_query($aVar,"SELECT id FROM users WHERE username = '$name2'");
		$value2 = $iid2->fetch_row()[0] ?? false;
		

	  
	
if($aVar === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


		 
		 $nameie = $_POST["nameie"];
		 

         $newname = str_replace("#", "",$nameie); 

		 
$comment_length = strlen($reply);

if($comment_length > 200)
{
header("location: welcome.php?error=1");
}
else
{
// Attempt insert query execution
if($block2 == "1"){

$sql = "INSERT INTO replying (id, reply, a_id, b_id, pics) VALUES ('','$reply','$newname','$value2','$target_file')";
if(mysqli_query($aVar, $sql)){
   //echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($aVar);
}
}
 
}

// Close connection
mysqli_close($aVar);

    }




	?>

	<?php


// Check if image file is a actual image or fake image
if(isset($_POST["submit2"])) {


$target_dir = "pics/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    //echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image. ";
    $uploadOk = 0;
  }

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large. ";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded. ";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded. ";
	
	
	$id = $_SESSION["id"];
	
	$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");
	$sql = "Update users SET pic = '$target_file' WHERE id = $id";
	
	session_start();
	$_SESSION["pic"] = $target_file;
	
	$page = $_SERVER['PHP_SELF'];
$sec = "1";
header("Refresh: $sec; url=$page");
	

if(mysqli_query($aVar, $sql)){
   // echo "Records inserted successfully. ";
	
	
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($aVar);
}


  } 
  
  
  else {
    echo "Sorry, there was an error uploading your file. ";
  }
  mysqli_close($aVar);
}
}


?>
	

	


	<?php
	

	
	if(isset($_POST['submit'])){
	
	$search_value=$_POST["search"];
	if($search_value != NULL){
	
$num = $_SESSION["id"];	
$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");	
$che = 0;
$check = mysqli_query($aVar,"SELECT username FROM users WHERE id = '$num'");
while($rowboat = mysqli_fetch_assoc($check))
		 {
		  $fri = $rowboat['username'];
		  }
		  if($fri != $search_value){
		  
	
$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");
if($aVar->connect_error){
    echo 'Connection Faild: '.$aVar->connect_error;
    }else{
        $sql="select username from users where username like '%$search_value%'";

        $res=$aVar->query($sql);

        if($row=$res->fetch_assoc()){
           // echo 'Username:  '.$row["username"];
		   session_start();
		  $rowww = $row["username"];
		   $_SESSION["searchname"] = $rowww;
		   
		   header("location: result.php");

            }
			else
			{
			echo "no users found.";
			}       

        }	
}
else{
echo "silly thats you";
}
}
else{
echo "field empty";
}
}


?>	
	
<?php

	     
	
		 $aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");
		 		 
		 $name = $_SESSION["username"];
		 
		 
		 $iid = mysqli_query($aVar,"SELECT id FROM users WHERE username = '$name'");
		 $value = $iid->fetch_row()[0] ?? false;
		 
		 
		 
		 $find_comments = mysqli_query($aVar,"SELECT comment FROM commentz WHERE a_id = '$value' ORDER BY a_id ASC, time DESC");
		 
		
		 
		 
		 $id = mysqli_query($aVar,"SELECT id FROM commentz WHERE a_id = '$value' ORDER BY a_id ASC, time DESC");
		 
		 
		 while($row = mysqli_fetch_assoc($find_comments))
		 {
		  		
		  		
		  		$comment = $row['comment'];
				
				$idd = mysqli_fetch_assoc($id);
				
				$iddd = $idd['id'];
				$idddd = "#".$iddd;
				
				
				$find_time = mysqli_query($aVar,"SELECT time FROM commentz WHERE id = '$iddd'");
				$time = $find_time->fetch_row()[0] ?? false;
				
				$find_img = mysqli_query($aVar,"SELECT pics FROM commentz WHERE id = '$iddd'");
				
				$imgg = mysqli_fetch_assoc($find_img);
				$imgdrop = $imgg['pics'];
				
				if($imgdrop !== "commentpics/"){
				$imggg = "<img src='$imgdrop' id='pro2' width='200px'> ";
				}
				else{
				$imggg = NULL;
				}
				
 				echo "<pre>";
				echo "<div id='commm'>"; 
				echo "<img src='$image' id='pro2' width='40px'> ".$name." ".$imggg.$comment." ".$time." <button onclick = 'works(this.id)' id='$idddd'>reply</button><br />";
				echo "</div>";
				

				$find_id = mysqli_query($aVar,"SELECT id FROM replying WHERE a_id = '$iddd'");			
				
				echo "<div id='$iddd'></div>";

		 while($row3 = mysqli_fetch_assoc($find_id))
		 {
		 
		  		$iid = $row3['id'];
				
				
				$find_replys = mysqli_query($aVar,"SELECT reply FROM replying WHERE id = '$iid'");
				$reply = $find_replys->fetch_row()[0] ?? false;
				
				$find_nid = mysqli_query($aVar,"SELECT b_id FROM replying WHERE id = '$iid'");
				$nid = $find_nid->fetch_row()[0] ?? false;
				$find_n = mysqli_query($aVar,"SELECT username FROM users WHERE id = '$nid'");
				$n = $find_n->fetch_row()[0] ?? false;
				
				$find_t = mysqli_query($aVar,"SELECT time FROM replying WHERE id = '$iid'");
				$t = $find_t->fetch_row()[0] ?? false;
				
				$findimage = mysqli_query($aVar,"SELECT b_id FROM replying WHERE id = '$iid'");
				$im = $findimage->fetch_row()[0] ?? false;
				
				$mag = mysqli_query($aVar,"SELECT pic FROM users WHERE id = '$im'");
				$mag2 = $mag->fetch_row()[0] ?? false;
				
				if($mag2 == NULL || $mag2 == "pics/")
				{
				$mag3 = "pics/17004.png";
				}
				else
				{
				$mag3 = $mag2;
				}
				
				$find_img = mysqli_query($aVar,"SELECT pics FROM replying WHERE id = '$iid'");
				
				$pics = $find_img->fetch_row()[0] ?? false;
				
				
				if($pics !== "replypics/"){
				$imggg = "<img src='$pics' id='pro5' width='200px'> ";
				}
				else{
				$imggg = NULL;
				}
				
				echo "<br />";
				echo "<div id='commm2'>";
		  		echo "<img src='$mag3' id='pro3' width='40px'> ".$n." ".$imggg.$reply." ".$t. "<br />";		 
		 		echo "</div>";
				
		 		
		 }			
				
				
		 
		 echo "</pre>";	
		 }
		 
		 

		 if(isset($_GET['error']))
		 {
		 echo "<p>200 Character Limit";
		 }
		 mysqli_close($aVar);
		 
	



?>

	
	<script type="text/javascript">
	 
	 function works(pip) {
	 
	 //HTML5 local storage 
localStorage.setItem("pip",pip);

	 
	 $(pip).load('reply.html');	
			
	}
	
	function com(){
	$("#com").load('comment.html');
	
	}
	
	function pro(){
	$("#com").load('pro.html');
	
	}
	</script>
	



<p style="padding:40px; color: #000000;">Copyright © 2020</p>

	
</body>
</html>