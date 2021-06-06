<!--when user send new msg, sender is read and receiver is 
    not read, user open msg, change receiver read status to read, 
    when reply, treat as new msg where sender and receiver is flipped  -->
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
            <h1>
                <?php echo $_SESSION['username']; ?>
                <a style='float:right;' href="new_message.php">New Message</a>
            </h1>
            <?php
                echo '<h3>'.'Messages'.'('.intval(count($rows)).')'.'</h3>';
                echo '<table>';
                    echo'<tr>';
                        echo '<th>Subject</th>';
                        echo '<th>With</th>';
                        echo '<th>Date</th>';
                    echo'</tr>';
                        if (count($rows) == 0){
                            echo '<tr>';
                            echo '<td>'.'No Conversation'.'</td>';
                            echo '</tr>'; 
                        }
                        else{
                            foreach($rows as $row){
                                echo '<tr>';
                                    echo '<td><a href="read_message.php?conversation_id='.$row["conversation_id"].'">'.$row["subject"].'</a></td>';
                                    echo '<td>'.$row['username'].'</td>';
                                    echo '<td>'.$row['timestamp'].'</td>';
                                echo '</tr>';  
                            } 
                        }
                echo '</table>';
            ?>
        </body>
    </html>
