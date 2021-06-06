<?php
    require('../config/post_login.php');
    $user = new UserFunction($_SESSION['id'], $conn);
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $subject_input = $conn->real_escape_string($_POST['subject']);
        $receiver_input = $conn->real_escape_string($_POST['receiver']);
        $message_input = $conn->real_escape_string($_POST['message']);
        $code = $user->setNewPM($subject_input, $receiver_input, $message_input);
        $errorArray = $user->getError();
        if ($code == 1){
            echo '<p style="text-align:center; color:red;">'.'Message Sent Successful'.'</p>';
        }        
        elseif ($code == 0){
            foreach($errorArray as $error){
                echo '<p style="text-align:center; color:red;">'.$error.'</p>';
            }
        }
    }
?>
<!DOCTYPE html>
    <head>
        <title>New Message</title>
    </head>
    <body>
        <div style='text-align:center;'>
            <h1>New Message</h1>
            <form action="new_message.php" method="post">
                <p>Please fill the following form to send a Direct message</p>
                <div>
                    <label>Subject</label>
                </div>
                <div>
                    <input type="text" name="subject">
                </div>
                <div>
                    <label>To(Username)</label>
                </div>
                <div>
                <input type="text" name="receiver">
                </div>
                <div>
                    <label>Message</label>
                </div>
                <div>
                    <textarea cols="40" rows="5" name="message"></textarea>
                </div>
                <input type="submit" value="Send">
                <input type="submit" formaction="message.php" value="Cancel">
            </form>
        </div>
    </body>
</html>