<?php
    require('../config/post_login.php');
    $user = new UserFunction($_SESSION['id'],$conn);
    $conversation_id = $_GET['conversation_id'];

?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Read and Reply</title>
        </head>
        <body>
            <div style='text-align:center;'>
                <form action="read_message.php?conversation_id=<?php if(isset($_GET['conversation_id'])){$conversation_id = $_GET['conversation_id'];echo $conversation_id;}?>" method="post">
                    <div>
                        <label>Reply</label>
                    </div>
                    <div>
                        <textarea cols="40" rows="5" name="message"></textarea>
                    </div>
                    <input type="submit" value="Send">
                </form>
            </div>            
        </body>
    </html>
<?php
    // check if coversation_id exist
    if(isset($_GET['conversation_id'])){
        $conversation_id = $_GET['conversation_id'];
        $rows = $user->readMessage(intval($conversation_id));
        $errorArray = $user->getError();
        if (count($errorArray)<=0){
            echo '<div style="text-align:center;">';
            echo '<h1>'.$rows[0]['subject'].'</h1>';
            echo '</div>';
            foreach($rows as $row){
                echo '<div style="text-align:center; border:1px solid black; width:200px; margin: 0 auto";>';
                echo '<p>Time:'.$row['timestamp'].'</p>';
                if ($_SESSION['id'] == $row['receiver_id']){
                    echo '<p>From:'.$row['username'].'<br>'.'To:'.'Me';
                }
                else{
                    echo '<p>From:'.'Me'.'<br>'.'To:'.$row['username'];
                }
                echo '<p>'.$row['message'].'</p>';
                echo '</div>';
            }
        }
        else{
            foreach($errorArray as $error){
                echo $error;
            }
        }
    }
    else{
        echo "Conversation Not Found";
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_GET['conversation_id'])){
            
        }
    }
?>