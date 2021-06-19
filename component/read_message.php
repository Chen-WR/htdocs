<?php
    require('../config/post_login.php');
    // check if coversation_id exist
    if(isset($_GET['conversation_id']) and isset($_GET['subject'])){
        $conversation_id = $_GET['conversation_id'];
        $subject = $_GET['subject'];
        $rows = $user->readMessage(intval($conversation_id));
        $errorArray = $user->getError();
        if (count($errorArray)<=0){
            echo '<h1 class="read-message-subject">'.$subject.'</h1>';
            echo '<div class="read-message-container">';
            foreach($rows as $row){
                if ($user->getId() == $row['receiver_id']){
                    echo"<div class='read-message-box'>
                            <div class='read-message-box-left-user'>
                                <img src='".$row['profile_pic']."' width=50px>".$rows[0]['username']."->".   
                            "</div>
                            <div class='read-message-box-left-message'>"
                                ."<span class='read-message-span'>".stripslashes($row['message']).
                                "<br>"
                                .$row['timestamp']."</span>".
                            "</div>
                        </div>";
                }
                elseif ($user->getId() == $row['sender_id']){
                    echo"<div class='read-message-box'>
                            <div class='read-message-box-right-user'><-"
                                .$user->getusername()."<img src='".$user->getProfilepic()."' width=50px>  
                            </div>
                            <div class='read-message-box-right-message'>"
                                ."<span class='read-message-span'>".stripslashes($row['message']).
                                "<br>"
                                .$row['timestamp']."</span>".
                            "</div>
                        </div>";
                }
            }
        }
        else{
            foreach($errorArray as $error){
                echo '<p style="text-align:center;">'.$error.'</p>';
            }
        }
    }
    else{
        echo "Conversation Not Found";
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_GET['conversation_id'])){
            $conversation_id = intval($_GET['conversation_id']);
            echo $conversation_id;
            $message_input = $conn->real_escape_string($_POST['message']);
            $code = $user->replyMessage($message_input, $conversation_id);
            $errorArray = $user->getError();
            if ($code == 1){
                echo '<p style="text-align:center; color:red;">'.'Reply Sent Successful'.'</p>';
                echo '<head><meta http-equiv="refresh" content="1"></head>';
            }        
            elseif ($code == 0){
                foreach($errorArray as $error){
                    echo '<p style="text-align:center; color:red;">'.$error.'</p>';
                }
            }
        }   
    }
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Read and Reply</title>
        </head>
        <body class='message-body' onload='window.onload=window.scrollTo(0, document.body.scrollHeight);history.scrollRestoration = "manual"';>
            <div style='text-align:center;'>
                <form action="read_message.php?conversation_id=<?php if(isset($_GET['conversation_id'])){$conversation_id = $_GET['conversation_id'];echo $conversation_id;}?>&subject=<?php if(isset($_GET['subject'])){$subject = $_GET['subject'];echo $subject;}?>" method="post">
                    <div>
                        <label style='color:white;'>Reply</label>
                    </div>
                    <div>
                        <textarea cols="40" rows="1" name="message"></textarea>
                    </div>
                    <input type="submit" formaction="message.php" value="Back">
                    <input type="submit" value="Send">
                </form>
            </div>
        </body>
    </html>

