<?php
	require('../config/post_login.php');
	// $rows = $user->getComment(); 
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Home</title>	
	</head>
	<body>	
		<div class='home-user-container'>
			<div class='home-user-username'>
				<?php
					if ($user->getFirstname() == null or $user->getLastname() == null){
						echo $user->getUsername();
					} 
					else{
						echo $user->getFirstname().' '.$user->getLastname();
					}
				?>
			</div>
			<div class='home-user-pic'>
				<img src="<?php echo $user->getProfilepic();?>" width="200px"/>
			</div>
		</div>
		<div class='home-new-post-button-container'>
			<button type="button" class="btn btn-primary btn-lg" onclick="newpostToggle()">New Post</button>
		</div>
		<div style='display:none;' class='home-new-post-container' id='new-post'>
			<div class='home-new-post-input-box'>
				<h2>New Post</h2>
				<form method='post'>
					<div>
						<textarea cols="50" rows="3" name="new-post"></textarea>
					</div>
					<div>
						<input type="submit" value="Post">
					</div>
				</form>
			</div>
		</div>
		<div class='home-list-post'>

		</div>	 
	</body>
</html>