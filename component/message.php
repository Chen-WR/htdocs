<?php
    require('../config/post_login.php');
    $rows = $user->getMessage();
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Messages</title>
        </head>
        <body class='message-body'>
            <a id='new-message-btn' href='new_message.php'>New Message</a>
            <?php
                echo '<h3 style="text-align:center;">'.'Messages'.'('.intval(count($rows)).')'.'</h3>';
                    if (count($rows) == 0){
                        echo '<p style="text-align:center;">'.'No Conversation'.'</p>';
                    }
                    else{
                        echo "<div class='message-container'>";
                        foreach($rows as $row){
                            echo"<a class='message-box-link-box'  href='read_message.php?conversation_id=".$row['conversation_id']."&subject=".$row['subject']."'>
                                        <div class='message-box-pic'>
                                            <img src='".$row['profile_pic']."' width='60px'>
                                        </div>
                                        <div class='message-box-username'>
                                            ".$row['username']."
                                        </div>
                                        <div class='message-box-timestamp'>
                                            ".date('m-d-y',strtotime($row['timestamp']))."
                                        </div>
                                        <div class='message-box-subject'>
                                            ".$row['subject']."
                                        </div>
                                        <div class='message-box-latest-message'>
                                            ".substr($row['message'],0,15)."....
                                        </div>   
                                </a>";
                        } 
                        echo "</div>";
                    }
            ?>
        </body>
    </html>
