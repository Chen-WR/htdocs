<?php
    include('header.php');
?>
<?php
    function getUnread($conn){
        if(isset($_SESSION['username']) and $_SESSION['login']==true){
            $unread_query = "SELECT * FROM message WHERE receiver_id=?";
            $stmt = $conn->prepare($unread_query);
            $stmt->bind_param('i', $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return $rows;
        }
    }
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Messages</title>
        </head>
        <body>
            <h1><?php echo $_SESSION['username']; ?></h1>
            <a href="new_message.php">New Message</a>
            <?php $rows = getUnread($conn); ?>
            <h3>Unread Messages(<?php echo intval(count($rows)); ?>):</h3>
        </body>
    </html>
<!--when user send new msg, sender is read and receiver is 
    not read, user open msg, change receiver read status to read, 
    when reply, treat as new msg where sender and receiver is flipped  -->
<!-- <table>
    <tr>
        <th class="title_cell">Title</th>
        <th>Replies</th>
        <th>Participant</th>
        <th>Date of creation</th>
    </tr>
//We display the list of unread messages
while($row1 = mysqli_fetch_array($result1))
{

    <tr>
        <td class="left"><a href="read_pm.php?id=<?php echo $row1['id']; ?>"><?php echo htmlentities($row1['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
        <td><?php echo $row1['reps']-1; ?></td>
        <td><?php echo $row1['username']; ?></td>
        <td><?php echo date('Y/m/d H:i:s' ,$row1['timestamp']); ?></td>
    </tr>

}
//If there is no unread message we notice it
if(intval(mysqli_num_rows($result1))==0)
{

<tr>
<td colspan="4" class="center">You have no unread message.</td>
</tr>
}

</table> -->
