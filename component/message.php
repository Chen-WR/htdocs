<?php
    require('../config/post_login.php');
    $rows = $user->getMessage();
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Messages</title>
        </head>
        <body>
            <h1 style='text-align:center;'>
                <a href="new_message.php">New Message</a>
            </h1>
            <?php
                echo '<h3 style="text-align:center;">'.'Messages'.'('.intval(count($rows)).')'.'</h3>';
                        if (count($rows) == 0){
                            echo '<p style="text-align:center;">'.'No Conversation'.'</p>';
                        }
                        else{
                            foreach($rows as $row){
                                echo "<div style='border:2px solid black;text-align:center;margin:auto;width:100px'>
                                    <a href='read_message.php?conversation_id=".$row["conversation_id"]."'>".$row["subject"]."</a></div>";
                                    // echo '<td style="flex:2;">'.$row['username'].'</td>';
                            } 
                        }
            ?>
        </body>
    </html>
