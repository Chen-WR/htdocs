<?php
    require('../config/post_login.php');
    $conversation_id = $_GET['conversation_id'];
    // check if coversation_id exist
    if(isset($_GET['conversation_id'])){
        $conversation_id = $_GET['conversation_id'];
        $rows = $user->readMessage(intval($conversation_id));
        $errorArray = $user->getError();
        if (count($errorArray)<=0){
            echo '<div style="text-align:center;">';
            echo '<h1 style="color:white;">'.$rows[0]['subject'].'</h1>';
            foreach($rows as $row){
                if ($_SESSION['id'] == $row['receiver_id']){
                    echo"<div class='container' style='width:90%;margin:auto;overflow:hidden;'>
                            <div style='font-size:14px;border:1px black solid;float:left;color:#f30f0fd9;'>
                                <img src='".$row['profile_pic']."' width=50px>"
                                .$rows[0]['username'].":".
                            "</div>
                            <div style='font-size:20px;color:white;word-wrap: break-word;border:2px solid;border-radius:8px;width:20%;float:left;background-color:#505050;'>"
                                .stripslashes($row['message']).
                                "<br>"
                                .$row['timestamp'].
                            "</div>
                        </div>";
                }
                elseif ($_SESSION['id'] == $row['sender_id']){
                    echo"<div class='container' style='width:90%;margin:auto;overflow:hidden;'>
                            <div style='font-size:14px;border:1px black solid;float:right; color:#edf012;'>"
                                .$user->getusername().
                                "<img src='".$user->getProfilepic()."' width=50px>
                            </div>
                            <div style='font-size:20px;color:white;word-wrap: break-word;border:2px solid;border-radius:8px;width:20%;float:right;background-color:#1a7dee;'>"
                                .stripslashes($row['message']).
                                "<br>"
                                .$row['timestamp'].
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
        <body style="background-color:black;" onload='window.onload=window.scrollTo(0, document.body.scrollHeight);history.scrollRestoration = "manual"';>
            <div style='text-align:center;'>
                <form action="read_message.php?conversation_id=<?php if(isset($_GET['conversation_id'])){$conversation_id = $_GET['conversation_id'];echo $conversation_id;}?>" method="post">
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

