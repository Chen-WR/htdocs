<?php
include('header.php');
?>
<!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Personal Messages</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style>
		a {
		color:#535BFF
		 }
		
		</style>
    </head>

	

<br>

    <body>
        <div class="header"> 
			<header>
			
		<h1 id="title"><em>Alias</em><br>
	    <span id="group17">By Group 17</span></h1>
		
			</header>
		</div>
        <div class="content">
<?php

if(isset($_GET['subcom5']))
{
$ore = $_GET['imp'];
$num = $_SESSION['id'];
$turf = $_SESSION['username'];

$aVar = mysqli_connect("127.0.0.1","stuartkruze","54321","usersproject");	
$che = 0;
$check = mysqli_query($aVar,"SELECT friend FROM friends WHERE userid = '$num'");
$adding = mysqli_query($aVar,"SELECT id FROM users WHERE username = '$ore'");
$adding2 = $adding->fetch_row()[0] ?? false;
while($rowboat = mysqli_fetch_assoc($check))
		 {
		  $fri = $rowboat['friend'];
		  
		  if($fri == $ore){
		  $che = 1;
		 }
		 }
if($che != 1){
$sql = "INSERT INTO friends (id, userid, friend) VALUES ('','$num','$ore')";
$sql2 = "INSERT INTO friends (id, userid, friend) VALUES ('','$adding2','$turf')";
$chee = 0;
if(mysqli_query($aVar, $sql)){
   $chee = 1;
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($aVar);
}
if(mysqli_query($aVar, $sql2)){
   $chee = $chee + 1;
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($aVar);
}
if($chee == 2)
{
echo "friend added";
}
}
else{
echo "friend already in your list";
}
}





//session_start();
//We check if the user is logged
if(isset($_SESSION['username']))
{
//We list his messages in a table
//Two queries are executes, one for the unread messages and another for read messages


$sql1 = 'select
  m1.id,
  m1.title,
  m1.timestamp,
  count(m2.id) as reps,
  users.id as userid,
  users.username
from
  pm as m1,
  pm as m2,
  users
where
  (
    (
      m1.user1 = "'.$_SESSION['id'].'"
      and m1.user1read = "no"
      and users.id = m1.user2
    )
    or (
      m1.user2 = "'.$_SESSION['id'].'"
      and m1.user2read = "no"
      and users.id = m1.user1
    )
  )
  and m1.id2 = "1"
  and m2.id = m1.id
group by
  m1.id
order by
  m1.id desc
';

 $sql2 = 'select
  m1.id,
  m1.title,
  m1.timestamp,
  count(m2.id) as reps,
  users.id as userid,
  users.username
from
  pm as m1,
  pm as m2,
  users
where
  (
    (
      m1.user1 = "'.$_SESSION['id'].'"
      and m1.user1read = "yes"
      and users.id = m1.user2
    )
    or (
      m1.user2 = "'.$_SESSION['id'].'"
      and m1.user2read = "yes"
      and users.id = m1.user1
    )
  )
  and m1.id2 = "1"
  and m2.id = m1.id
group by
  m1.id
order by
  m1.id desc
';

$result1 = mysqli_query($link, $sql1);

$result2 = mysqli_query($link, $sql2);

//debugging purpose
// if (!$result1) {
//     printf("Error message: %s\n", mysqli_error($link));
// }



?>
<p><?php echo 'Hello ',$_SESSION['username']; ?></p>
<a href="new_pm.php" class="link_new_pm">New DM</a><br />
List of Messages:<br />
<p align="right"><a href="welcome.php">Back to Main Page</a><br><a href="logout.php">Logout</a></p>
<h3>Unread Messages(<?php echo intval(mysqli_num_rows($result1)); ?>):</h3>
<table>
        <tr>
        <th class="title_cell">Title</th>
        <th>Replies</th>
        <th>Participant</th>
        <th>Date of creation</th>
    </tr>
<?php
//We display the list of unread messages
while($row1 = mysqli_fetch_array($result1))
{
?>
        <tr>
        <td class="left"><a href="read_pm.php?id=<?php echo $row1['id']; ?>"><?php echo htmlentities($row1['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
        <td><?php echo $row1['reps']-1; ?></td>
        <td><?php echo $row1['username']; ?></td>
        <td><?php echo date('Y/m/d H:i:s' ,$row1['timestamp']); ?></td>
    </tr>
<?php
}
//If there is no unread message we notice it
if(intval(mysqli_num_rows($result1))==0)
{
?>
        <tr>
        <td colspan="4" class="center">You have no unread message.</td>
    </tr>
<?php
}
?>
</table>
<br />
<h3>Read Messages(<?php echo intval(mysqli_num_rows($result2)); ?>):</h3>
<table>
        <tr>
        <th class="title_cell">Title</th>
        <th>Replies</th>
        <th>Participant</th>
        <th>Date or creation</th>
    </tr>
<?php
//We display the list of read messages
while($row2 = mysqli_fetch_array($result2))
{
?>
        <tr>
        <td class="left"><a href="read_pm.php?id=<?php echo $row2['id']; ?>"><?php echo htmlentities($row2['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
        <td><?php echo $row2['reps']-1; ?></td>
        <td><?php echo $row2['username']; ?></td>
        <td><?php echo date('Y/m/d H:i:s' ,$row2['timestamp']); ?></td>
    </tr>
<?php
}
//If there is no read message we notice it
if(intval(mysqli_num_rows($result2))==0)
{
?>
        <tr>
        <td colspan="4" class="center">You have no read message.</td>
    </tr>
<?php
}
?>
</table>
<?php
}
else
{
        echo 'You must be logged to access this page.';
        header("location: login.php");
}
?>
                </div>
        </body>
</html>

