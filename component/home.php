<?php
	require('../config/post_login.php');
	// $rows = $user-getComment(); 
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Home</title>	
	</head>
	<body style="text-align: center;">	
		<div>
			<h1><b>
				<?php
					if ($user->getFirstname() == null or $user->getLastname() == null){
						echo $user->getUsername();
					} 
					else{
						echo $user->getFirstname().' '.$user->getLastname();
					}
				?>
			</b></h1>
			<img src="<?php echo $user->getProfilepic();?>" width="200px">
		</div>
		<div>

		</div>	 
	</body>
</html>