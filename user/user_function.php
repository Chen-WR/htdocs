<?php
    class UserFunction{
        private $link;
        private $user_id;
        private $error = array();

        public function __construct($user_id, $conn){
            $this->user_id = $user_id;
            $this->link = $conn;
        }

        public function setVideo(){
            // 1MB = 1048576 Bytes
            $count = 0;
            $maxsize = 10485760;
            $allow_extension = array("mp4","avi","3gp","mov","mpeg");
            if (!file_exists('../video')) {
                mkdir('../video', 0777, true);
            }
            $filedir = "../video/";
            $filename = $_FILES['video_file']['name'];
            $filesize = $_FILES['video_file']['size'];
            $file = $filedir.$filename;
            while (true){
                if (file_exists($file)){
                    $file = $filedir.$count.$filename;
                    $count++;
                }
                else{
                    break;
                }
            }
            $filetype = strtolower(pathinfo($file,PATHINFO_EXTENSION));
            if (in_array($filetype,$allow_extension)){
                if ($filesize > $maxsize or $filesize == 0){
                    $this->error['size_error'] = 'File must be less than 1MB';
                }
                else{
                    if(move_uploaded_file($_FILES['video_file']['tmp_name'],$file)){
                        if (count($this->error)<=0){
                            $query = "INSERT INTO video (name, location, user_id) VALUES (?,?,?)";
                            $stmt = $this->link->prepare($query);
                            $stmt->bind_param('sss', $filename, $file, $_SESSION['id']);
                            $success = $stmt->execute();
                            if ($success){
                                return 1;
                            }
                            else{
                                return 0;
                            }
                        }
                        else{
                            return 0;
                        }
                    }
                }
            }
            else{
                $this->error['extension_error'] = 'Invalid File Extension';
            }
        }
        public function getVideo(){
            $query = "SELECT * FROM video WHERE user_id=? ORDER BY timestamp DESC";
            $stmt = $this->link->prepare($query);
            $stmt->bind_param('i', $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return $rows;
        
        }
        public function getMessage(){
            // Select all from message, join by user that send the message, and extract all message that receiver id is current user and is unread by status 0, group by conversation id, going from newest to oldest.
            $query = "SELECT * FROM message INNER JOIN user ON (user.id!=?) AND (user.id = message.sender_id OR user.id = message.receiver_id) WHERE sender_id=? OR receiver_id=? GROUP BY conversation_id ORDER BY conversation_id DESC;
            ";
            $stmt = $this->link->prepare($query);
            $stmt->bind_param('iii', $this->user_id, $this->user_id, $this->user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return $rows;
        }
        public function newMessage($subject, $receiver, $message){
            //check if any required is missing
            if (empty(trim($subject))){
                $this->error['subject_error'] = "Subject Required";
                return 0;
            }
            elseif (empty(trim($receiver))){
                $this->error['receiver_error'] = "Receiver Required";
                return 0;
            }
            elseif (empty(trim($message))){
                $this->error['message_error'] = "Message Required";
                return 0;
            }
            else{
                if (count($this->error)<=0){
                    //check if receiver exist
                    $query = "SELECT * FROM user WHERE username=?";
                    $stmt = $this->link->prepare($query);
                    $stmt->bind_param('s',$receiver);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    if (count($rows)==1){
                        // if receiver exist
                        if ($rows[0]['id']!=$this->user_id){
                            $receiver = $rows[0]['id'];
                            // if receiver is not the user itself
                            $query = "SELECT * FROM message GROUP BY conversation_id";
                            $stmt = $this->link->prepare($query);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $rows = $result->fetch_all(MYSQLI_ASSOC);
                            $conversation_id = intval(count($rows))+1;
                            $query = "INSERT INTO message (sender_id, receiver_id, conversation_id, subject, message) VALUES (?,?,?,?,?)";
                            $stmt = $this->link->prepare($query);
                            $stmt->bind_param('iiiss', $this->user_id, $receiver , $conversation_id, $subject, $message);
                            $stmt->execute();
                            return 1;
                        }
                        else{
                            $this->error['receiver_error'] = "You can't send message to yourself";
                            return 0;
                        }
                    }
                    else{
                        $this->error['receiver_error'] = "Receiver Username Does Not Exist";
                        return 0;
                    }
                }
                else{
                    // error, return 0
                    return 0;
                }
            }
        }
        public function readMessage($conversation_id){
            // check conversation_id in database
            $query = "SELECT * FROM message WHERE conversation_id=? GROUP BY conversation_id";
            $stmt = $this->link->prepare($query);
            $stmt->bind_param('i', $conversation_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if (count($rows)==1){
                if ($rows[0]['sender_id']==$this->user_id or $rows[0]['receiver_id']==$this->user_id){
                    $query = "SELECT * FROM message INNER JOIN user ON (user.id!=?) AND (user.id = message.sender_id OR user.id = message.receiver_id) WHERE conversation_id=? ORDER BY timestamp Desc";
                    $stmt = $this->link->prepare($query);
                    $stmt->bind_param('ii', $this->user_id, $conversation_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    return $rows;
                }
                else{
                    $this->error['auth_error'] = 'Not able to view other peoples conversation';
                }
            }
            else{
                $this->error['conversation_error'] = 'Conversation Does Not Exist';
                return 0;
            }
        }
        public function replyMessage($message, $conversation_id){
            if (empty(trim($message))){
                $this->error['message_error'] = "Message Required";
                return 0;
            }
            elseif (empty($conversation_id)){
                $this->error['conversation_error'] = "Conversation Does Not Exist";
                return 0;
            }
            else{
                if (count($this->error)<=0){
                    //check if receiver exist
                    $query = "SELECT * FROM message WHERE conversation_id=? GROUP BY conversation_id";
                    $stmt = $this->link->prepare($query);
                    $stmt->bind_param('i', $conversation_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    if (count($rows)==1){
                        // check whose the receiver
                        if ($this->user_id == $rows[0]['receiver_id']){
                            $receiver = $rows[0]['sender_id'];
                        }
                        elseif ($this->user_id == $rows[0]['sender_id']){
                            $receiver = $rows[0]['receiver_id'];

                        }
                        $subject = $rows[0]['subject'];
                        $query = "INSERT INTO message (sender_id, receiver_id, conversation_id, subject, message) VALUES (?,?,?,?,?)";
                        $stmt = $this->link->prepare($query);
                        $stmt->bind_param('iiiss', $this->user_id, $receiver , $conversation_id, $subject, $message);
                        $stmt->execute();
                        return 1;
                    }
                    else{
                        $this->error['reply_error'] = "Reply Error";
                        return 0;
                    }
                }
                else{
                    // error, return 0
                    return 0;
                }
            }
        }
        public function getError(){
            return $this->error;
        }
    }
?>