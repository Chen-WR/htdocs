<?php
    require('../config/post_login.php');
    $rows = $user->getMessage();
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Messages</title>
        </head>
        <body id='message-body'>
            <a id='new-message-btn' href='new_message.php'>New Message</a>
            <?php
                echo '<h3 style="text-align:center;">'.'Messages'.'('.intval(count($rows)).')'.'</h3>';
                    if (count($rows) == 0){
                        echo '<p style="text-align:center;">'.'No Conversation'.'</p>';
                    }
                    else{
                        echo "<div id='message-container'>";
                        foreach($rows as $row){
                            echo"<a id='message-box-link'  href='read_message.php?conversation_id=".$row['conversation_id']."&subject=".$row['subject']."'>
                                    <div id='message-box'>
                                        <div id='message-box-pic'>
                                            <img src='".$row['profile_pic']."' width='40px'>
                                        </div>
                                        <div id='message-box-username'>
                                            ".$row['username']."
                                        </div>
                                        <div id='message-box-subject'>
                                            ".$row['subject']."
                                        </div>
                                    </div>
                                </a>";
                        } 
                        echo "</div>";
                    }
            ?>
        </body>
    </html>
