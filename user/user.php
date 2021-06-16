<?php
class User{
    private $status;
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $username;
    private $profile_pic;
    private $conn;
    private $error = array();  

    public function __construct($status, $id, $firstname, $lastname, $email, $username, $profile_pic, $conn){
        $this->status = $status;
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->username = $username;
        $this->profile_pic = $profile_pic;
        $this->conn = $conn;
    }

    // For videos function/////////////////////////////////////////////////////////////////
    public function setVideo($filename, $filesize, $tempfilename){
        // 1MB = 1048576 Bytes
        $count = 0;
        $maxsize = 10485760;
        $allow_extension = array("mp4","avi","3gp","mov","mpeg");
        if (!file_exists('../video')) {
            mkdir('../video', 0777, true);
        }
        $filedir = "../video/";
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
                $this->error['size_error'] = 'File must be less than 10MB';
            }
            else{
                if(move_uploaded_file($tempfilename ,$file)){
                    if (count($this->error)<=0){
                        $query = "INSERT INTO video (name, location, user_id) VALUES (?,?,?)";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param('ssi', $filename, $file, $this->id);
                        $success = $stmt->execute();
                        if ($success){
                            return 1;
                        }
                        else{
                            $this->error['upload_error'] = 'Upload Failed';
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
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    
    }
    //End for video function////////////////////////////////////////////////////////////////////////

    //Profile pic function start////////////////////////////////////////////////////////////////////

    public function setProfilepic($filename, $filesize, $tempfilename){
        // 1MB = 1048576 Bytes
        $count = 0;
        $maxsize = 10485760;
        $allow_extension = array("jpg","jpeg","tiff","png","gif");
        if (!file_exists('../picture')) {
            mkdir('../picture', 0777, true);
        }
        $filedir = "../picture/";
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
        if ($filesize != 0){
            if (in_array($filetype,$allow_extension)){
                if ($filesize > $maxsize or $filesize == 0){
                    $this->error['size_error'] = 'File must be less than 10MB';
                }
                else{
                    if(move_uploaded_file($tempfilename ,$file)){
                        if (count($this->error)<=0){
                            $query = "UPDATE user SET profile_pic=? WHERE id=?";
                            $stmt = $this->conn->prepare($query);
                            $stmt->bind_param('si', $file, $this->id);
                            $success = $stmt->execute();
                            if ($success){
                                $this->profile_pic = $file;
                                return 1;
                            }
                            else{
                                $this->error['upload_error'] = 'Upload Failed';
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
    }
    //End of profile pic function//////////////////////////////////////////////////////////////////

    // For messages function///////////////////////////////////////////////////////////////////////
    public function getMessage(){
        // Select all from message, join by user that send the message, and extract all message that receiver id is current user and is unread by status 0, group by conversation id, going from newest to oldest.
        $query = "SELECT * FROM message INNER JOIN user ON (user.id!=?) AND (user.id = message.sender_id OR user.id = message.receiver_id) WHERE sender_id=? OR receiver_id=? GROUP BY conversation_id ORDER BY conversation_id DESC;
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('iii', $this->id, $this->id, $this->id);
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
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('s',$receiver);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                if (count($rows)==1){
                    // if receiver exist
                    if ($rows[0]['id']!=$this->id){
                        $receiver = $rows[0]['id'];
                        // if receiver is not the user itself
                        $query = "SELECT * FROM message GROUP BY conversation_id";
                        $stmt = $this->conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                        $conversation_id = intval(count($rows))+1;
                        $query = "INSERT INTO message (sender_id, receiver_id, conversation_id, subject, message) VALUES (?,?,?,?,?)";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param('iiiss', $this->id, $receiver , $conversation_id, $subject, $message);
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
                return 0;
            }
        }
    }
    public function readMessage($conversation_id){
        // check conversation_id in database
        $query = "SELECT * FROM message WHERE conversation_id=? GROUP BY conversation_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $conversation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        if (count($rows)==1){
            if ($rows[0]['sender_id']==$this->id or $rows[0]['receiver_id']==$this->id){
                $query = "SELECT * FROM message INNER JOIN user ON (user.id!=?) AND (user.id = message.sender_id OR user.id = message.receiver_id) WHERE conversation_id=? ORDER BY timestamp Desc";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('ii', $this->id, $conversation_id);
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
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('i', $conversation_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                if (count($rows)==1){
                    // check whose the receiver
                    if ($this->id == $rows[0]['receiver_id']){
                        $receiver = $rows[0]['sender_id'];
                    }
                    elseif ($this->id == $rows[0]['sender_id']){
                        $receiver = $rows[0]['receiver_id'];

                    }
                    $subject = $rows[0]['subject'];
                    $query = "INSERT INTO message (sender_id, receiver_id, conversation_id, subject, message) VALUES (?,?,?,?,?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('iiiss', $this->id, $receiver , $conversation_id, $subject, $message);
                    $stmt->execute();
                    return 1;
                }
                else{
                    $this->error['reply_error'] = "Reply Error";
                    return 0;
                }
            }
            else{
                return 0;
            }
        }
    }
    //End of message function////////////////////////////////////////////////////////////////////////
    
    //Edit user info function/////////////////////////////////////////////////////////////////////////
    public function EditUserInfo($firstname, $lastname, $email, $username){
        if (!empty(trim($email))){
            if ($email != $this->email){
                $email_param = trim($email);
                $query = "SELECT * from user where email=?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('s', $email_param);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0){
                    $this->error['email_error'] = "Email Existed";
                }
            }
        }
        if (!empty(trim($username))){
            if ($username != $this->username){
                $username_param = trim($username);
                $query = "SELECT * from user where username=?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('s', $username_param);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0){
                    $this->error['username_error'] = "Username Existed";
                }
            }
        }
        if (count($this->error) <= 0){
            $query = "UPDATE user SET firstname=?, lastname=?, email=?, username=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssi', $firstname, $lastname, $email, $username, $this->id);
            $success = $stmt->execute();
            if ($success){
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                $this->email = $email;
                $this->username = $username;
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
    //End of edit user info function/////////////////////////////////////////////////////////////////

    //Getter for all property//////////////////////////////////////////////////////////////////////
    public function getFirstname(){
        return $this->firstname;
    }
    public function getLastname(){
        return $this->lastname;
    }
    public function getusername(){
        return $this->username;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getProfilepic(){
        return $this->profile_pic;
    }
    //End of getters

    public function getError(){
        return $this->error;
    }
}
?>