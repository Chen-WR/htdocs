<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="../css/stylesheet.css">
		<link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />
		<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.14/semantic.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>	
		<!-- CSS only -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
		<!-- JavaScript Bundle with Popper -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>	
	</head>	
	<body>
		<div style="background-color:white;" class="ui secondary  menu">
			<a class="active item" href="home.php">
				Home
			</a>
			<a class="item" href="message.php">
				Messages
			</a>
			<a class="item">
				Friends
			</a>
			<a class="item" href="video.php">
				My Channel
			</a>
			<div class="right menu">
				<div class="item">
					<div class="ui icon input">
						<input type="text" placeholder="Search people you know...">
						<i class="search link icon"></i>
					</div>
				</div>
				<a class="ui item" href="../component/user_info_edit.php">
				Edit Profile
				</a>
				<a class="ui item" href="../account/logout.php">
				Logout
				</a>
			</div>
		</div>
    </body>
</html>