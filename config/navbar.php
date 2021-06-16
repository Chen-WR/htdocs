<!DOCTYPE html>
<html lang="en">
	<head>
		<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.14/semantic.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>	
	</head>
	<body>	
		<div class="ui secondary  menu">
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