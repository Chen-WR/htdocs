<?php
    class UserFunction{
        private $link;
        private $error = array();

        public function __construct($conn){
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
        public function getError(){
            return $this->error;
        }
    }
?>