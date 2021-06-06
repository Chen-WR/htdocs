<?php
require('../config/post_login.php')
?>
<!DOCTYPE html>
    <head>
        <title>Read and Reply</title>
    </head>
    <body>
			

<?php
//session_start();
//We check if the user is logged
if(isset($_SESSION['username']))
{
//We check if the ID of the discussion is defined
if(isset($_GET['conversation_id']))
{
$id = intval($_GET['id']);
//We get the title and the narators of the discussion
$sql1 = 'select title, user1, user2 from pm where id="'.$id.'" and id2="1"';
$result1 = mysqli_query($link, $sql1);

$row1 = mysqli_fetch_array($result1);
//We check if the discussion exists
if(mysqli_num_rows($result1)==1)
{
//We check if the user have the right to read this discussion
if($row1['user1']==$_SESSION['id'] or $row1['user2']==$_SESSION['id'])
{
//The discussion will be placed in read messages
if($row1['user1']==$_SESSION['id'])
{       
        $sql = 'update pm set user1read="yes" where id="'.$id.'" and id2="1"';
        mysqli_query($link, $sql);
        $user_partic = 2;
}
else
{       
        $sql = 'update pm set user2read="yes" where id="'.$id.'" and id2="1"';
        mysqli_query($link, $sql);
        $user_partic = 1;
}
//We get the list of the messages
$sql2 = 'select pm.timestamp, pm.message, users.id as userid, users.username from pm, users where pm.id="'.$id.'" and users.id=pm.user1 order by pm.id2';
$result2 = mysqli_query($link, $sql2);

//We check if the form has been sent
if(isset($_POST['message']) and $_POST['message']!='')
{
        $message = $_POST['message'];
        //We remove slashes depending on the configuration
        if(get_magic_quotes_gpc())
        {
                $message = stripslashes($message);
        }
        //We protect the variables
        $message = mysqli_real_escape_string($link, $message);
        //We send the message and we change the status of the discussion to unread for the recipient
        $sql3 = 'insert into pm (id, id2, title, user1, user2, message, timestamp, user1read, user2read)values("'.$id.'", "'.(intval(mysqli_num_rows($result2))+1).'", "", "'.$_SESSION['id'].'", NULL, "'.$message.'", "'.time().'", "", "")';
        $result3 = mysqli_query($link, $sql3);
        $sql4 = 'update pm set user'.$user_partic.'read="yes" where id="'.$id.'" and id2="1"';
        $result4 = mysqli_query($link, $sql4);


        if($result3 and $result4)
        {
?>
<div class="message">Your message has successfully been sent.<br />
<a href="read_pm.php?id=<?php echo $id; ?>">Back to Discussion</a></div>
<?php
        }
        else
        {

?>
<div class="message">An error occurred while sending the message.<br />
<a href="read_pm.php?id=<?php echo $id; ?>">Back to the discussion</a></div>
<?php
        }
}
else
{
//We display the messages
?>
<div class="content">
<h1><?php echo $row1['title']; ?></h1>
<table class="messages_table">
        <tr>
        <th class="author">User</th>
        <th>Message</th>
    </tr>
<?php
while($row2 = mysqli_fetch_array($result2))
{
?>
        <tr>
        <td class="author center">
<?php

?><br /><?php echo $row2['username']; ?></td>
        <td class="left">
            <div class="date">Sent: <?php echo date('m/d/Y H:i:s' ,$row2['timestamp']); ?></div>
        <?php echo $row2['message']; ?></td>
    </tr>
<?php
}
if (!$result2) {
    printf("Error message: %s\n", mysqli_error($link));
}
//We display the reply form
?>
</table><br />
<h2>Reply</h2>
<div class="center">
    <form action="read_pm.php?id=<?php echo $id; ?>" method="post">
        <label for="message" class="center">Message</label><br />
        <textarea cols="40" rows="5" name="message" id="message"></textarea><br />
        <input type="submit" value="Send" />
        <input type="submit" formaction="list_pm.php" value="Back">
    </form>
</div>
</div>
<?php
}
}
else
{
        echo '<div class="message">You dont have the rights to access this page.</div>';
}
}
else
{
        echo '<div class="message">This discussion does not exists.</div>';
}
}
else
{
        echo '<div class="message">The discussion ID is not defined.</div>';
}
}
else
{
        echo '<div class="message">You must be logged to access this page.</div>';
        header("location: login.php");
}
?>
        
        </body>
</html>