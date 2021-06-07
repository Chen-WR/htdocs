<?php
    require('../config/post_login.php');
    $user = new UserFunction($_SESSION['id'], $conn);
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
                            echo '<table style="margin-left: auto; margin-right: auto;">';
                            echo'<tr>';
                                echo '<th style="flex:2;" >Subject</th>';
                                echo '<th style="flex:2;">User</th>';
                                echo '<th style="flex:2;">Date</th>';
                                echo '<th style="flex:2;">Time</th>';
                            echo'</tr>';
                            foreach($rows as $row){
                                echo '<tr style="text-align:center;">';
                                    echo '<td style="flex:2;"><a href="read_message.php?conversation_id='.$row["conversation_id"].'">'.$row["subject"].'</a></td>';
                                    echo '<td style="flex:2;">'.$row['username'].'</td>';
                                    echo '<td style="flex:2;">'.substr(strval($row['timestamp']), 0, 11).'</td>';
                                    echo '<td style="flex:2;">'.substr(strval($row['timestamp']), 11).'</td>';
                                echo '</tr>';  
                            } 
                        }
                echo '</table>';
                echo '<p style="text-align:center;"><a href="home.php">'.'Back Home'.'</a></p>';
            ?>
        </body>
    </html>
