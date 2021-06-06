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
        public function getReceivedPM(){
            // Select all from message, join by user that send the message, and extract all message that receiver id is current user and is unread by status 0, group by conversation id, going from newest to oldest.
            $unread_query = "SELECT * FROM message INNER JOIN user ON message.sender_id = user.id WHERE receiver_id=? AND receiver_read=0 GROUP BY conversation_id ORDER BY conversation_id DESC";
            $stmt = $this->link->prepare($unread_query);
            $stmt->bind_param('i', $this->user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return $rows;
        }
        public function getSendPM(){
            // Select all from message, join by user that send the message, and extract all message that receiver id is current user and is unread by status 0, group by conversation id, going from newest to oldest.
            $read_query = "SELECT * FROM message INNER JOIN user ON message.receiver_id = user.id WHERE sender_id=? AND sender_read=1 GROUP BY conversation_id ORDER BY conversation_id DESC";
            $stmt = $this->link->prepare($read_query);
            $stmt->bind_param('i', $this->user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return $rows;
        }
        public function setNewPM($subject, $receiver, $message){
            //check if any required is missing
            if (empty(trim($subject))){
                $this->error['subject_error'] = "Subject Required";
            }
            elseif (empty(trim($receiver))){
                $this->error['receiver_error'] = "Receiver Required";
            }
            elseif (empty(trim($message))){
                $this->error['message_error'] = "Message Required";
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
                            $conversat_id = intval(count($rows))+1;
                            $false = 0;
                            $true = 1;
                            $query = "INSERT INTO message (sender_id, receiver_id, conversation_id, subject, message, sender_read, receiver_read) VALUES (?,?,?,?,?,?,?)";
                            $stmt = $this->link->prepare($query);
                            $stmt->bind_param('iiissii', $this->user_id, $receiver , $conversat_id, $subject, $message, $true, $false);
                            $rc = $stmt->execute();
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
        public function getError(){
            return $this->error;
        }
    }
?>