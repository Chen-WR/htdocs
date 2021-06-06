<!--when user send new msg, sender is read and receiver is 
    not read, user open msg, change receiver read status to read, 
    when reply, treat as new msg where sender and receiver is flipped  -->
<?php
    require('../config/post_login.php');
    $user = new UserFunction($_SESSION['id'], $conn);
    $received_rows = $user->getReceivedPM();
    $send_rows = $user->getSendPM();
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Messages</title>
        </head>
        <body>
            <h1>
                <?php echo $_SESSION['username']; ?>
                <a style='float:right;' href="new_message.php">New Message</a>
            </h1>
            <?php
                echo '<h3>'.'New Received'.'('.intval(count($received_rows)).')'.'</h3>';
                echo '<table>';
                    echo'<tr>';
                        echo '<th>Subject</th>';
                        echo '<th>From</th>';
                        echo '<th>Date</th>';
                    echo'</tr>';
                        if (count($received_rows) == 0){
                            echo '<tr>';
                            echo '<td>'.'No New Received Message'.'</td>';
                            echo '</tr>'; 
                        }
                        else{
                            foreach($received_rows as $row){
                                echo '<tr>';
                                    echo '<td><a href="read_message.php?conversation_id='.$row["conversation_id"].'">'.$row["subject"].'</a></td>';
                                    echo '<td>'.$row['username'].'</td>';
                                    echo '<td>'.$row['timestamp'].'</td>';
                                echo '</tr>';  
                            } 
                        }
                echo '</table>';

                echo '<h3>'.'Conversations'.'('.intval(count($send_rows)).')'.'</h3>';
                echo '<table>';
                    echo'<tr>';
                        echo '<th>Subject</th>';
                        echo '<th>To</th>';
                        echo '<th>Date</th>';
                    echo'</tr>';
                        if (count($send_rows) == 0){
                            echo '<tr>';
                            echo '<td>'.'No Conversation'.'</td>';
                            echo '</tr>'; 
                        }
                        else{
                            foreach($send_rows as $row){
                                echo '<tr>';
                                    echo '<td><a href="readmessage.php?conversation_id='.$row["conversation_id"].'">'.$row["subject"].'</a></td>';
                                    echo '<td>'.$row['username'].'</td>';
                                    echo '<td>'.$row['timestamp'].'</td>';
                                echo '</tr>';  
                            } 
                        }
                echo '</table>';
            ?>
        </body>
    </html>
