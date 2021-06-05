<?php
require('../config/post_login.php');
$user = new UserFunction($_SESSION['id'], $conn);
?>
<!DOCTYPE html>
    <head>
        <title>New Message</title>
    </head>
    <body>
        <div>
            <h1>New Direct Message</h1>
            <form action="new_message.php" method="post">
                    <p>Please fill the following form to send a Direct message</p>
                    <label for="title">Title</label><br><input type="text" value="<?php echo htmlentities($otitle, ENT_QUOTES, 'UTF-8'); ?>" id="title" name="title" /><br />
                    <label for="recip">Recipient<span class="small">(Username)</span></label><br><input type="text" value="<?php echo htmlentities($orecip, ENT_QUOTES, 'UTF-8'); ?>" id="recip" name="recip" /><br />
                    <label for="message">Message</label><br><textarea cols="40" rows="5" id="message" name="message"><?php echo htmlentities($omessage, ENT_QUOTES, 'UTF-8'); ?></textarea><br />
                    <input type="submit" value="Send" />
                    <input type="submit" formaction="list_pm.php" value="Cancel">
            </form>
        </div>
    </body>
</html>

<?php
if(isset($_SESSION['username']))
{
$form = true;
$otitle = '';
$orecip = '';
$omessage = '';
//We check if the form has been sent
if(isset($_POST['title'], $_POST['recip'], $_POST['message']))
{
        $otitle = $_POST['title'];
        $orecip = $_POST['recip'];
        $omessage = $_POST['message'];
        //We remove slashes depending on the configuration
        if(get_magic_quotes_gpc())
        {
                $otitle = stripslashes($otitle);
                $orecip = stripslashes($orecip);
                $omessage = stripslashes($omessage);
        }
        //We check if all the fields are filled
        if($_POST['title']!='' and $_POST['recip']!='' and $_POST['message']!='')
        {
                //We protect the variables
                $title = mysqli_real_escape_string($link,$otitle);
                $recip = mysqli_real_escape_string($link,$orecip);
                $message = mysqli_real_escape_string($link, $omessage);

                //We check if the recipient exists
                
                $sql1 = 'select * from users where username = "'.$recip.'"';

                $sql2 = 'select id as recipid, (select count(*) from pm) as npm from users where username = "'.$recip.'"';

                $result1 = mysqli_query($link, $sql1); 

                $result2 = mysqli_query($link, $sql2); 

                $rows = mysqli_fetch_array($result2);

                if(mysqli_num_rows($result1) > 0)
                {
                        //We check if the recipient is not the actual user
                        if($rows['recipid']!=$_SESSION['id'])
                        {
                                $id = $rows['npm']+1;
                                //We send the message
                                $sql = 'insert into pm (id, id2, title, user1, user2, message, timestamp, user1read, user2read)values("'.$id.'", "1", "'.$title.'", "'.$_SESSION['id'].'", "'.$rows['recipid'].'", "'.$message.'", "'.time().'", "yes", "no")';
                                if(mysqli_query($link, $sql))
                                {
?>
<div class="message">The message has successfully been sent.<br />
<a href="message.php">Go Back to My Message</a></div>
<?php
                                        $form = false;
                                }
                                else
                                {
                                        //Otherwise, we say that an error occured
                                        $error = 'An error occurred while sending the message';
                                }
                        }
                        else
                        {
                                //Otherwise, we say the user cannot send a message to himself
                                $error = 'You cannot send a message to yourself.';
                        }
                }
                else
                {
                        //Otherwise, we say the recipient does not exists
                        $error = 'The recipient does not exists.';
                }
        }
        else
        {
                //Otherwise, we say a field is empty
                $error = 'A field is empty. Please fill of the fields.';
        }
}
elseif(isset($_GET['recip']))
{
        //We get the username for the recipient if available
        $orecip = $_GET['recip'];
}
if($form)
{
//We display a message if necessary
if(isset($error))
{
        echo '<div class="message">'.$error.'</div>';
}
//We display the form
?>

<?php
}
}
?>
