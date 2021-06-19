<?php
class UserAccount{
    private $conn;
    private $error = array();

    // Constructor have 2 underscore and not 1!!!!!
    public function __construct($conn){
        $this->conn = $conn;
    }

    protected function email_validation($email){
        if (empty(trim($email))){
            $this->error['email_error'] = "Email Required";
        }
        elseif (!empty(trim($email))){
            $email_param = trim($email);
            $query = "SELECT * from user where email=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $email_param);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0){
                $this->error['email_error'] = "Email Existed";
            }
            else{
                return trim($email);
            }
        }
    }
    protected function username_validation($username){
        if (empty(trim($username))){
            $this->error['username_error'] = "Username Required";
        }
        elseif (!empty(trim($username))){
            $username_param = trim($username);
            $query = "SELECT * from user where username=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $username_param);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0){
                $this->error['username_error'] = "Username Existed";
            }
            else{
                return trim($username);
            }
        }
    }
    protected function password_validation($password1, $password2){
        if (empty(trim($password1))){
            $this->error['password1_error'] = "Password Required";
        }
        elseif (empty(trim($password2))){
            $this->error['password2_error'] = "Confirm Password Required";
        }
        elseif (trim($password1) != trim($password2)){
            $this->error['password2_error'] = "Password Not Match";
        }
        else{
            return password_hash(trim($password1), PASSWORD_DEFAULT);
        }
    }
    public function registration($email_input, $username_input, $password_input, $confirm_password_input){
        $profile_pic = "../image/default_profile_pic.jpg";
        $email = $this->email_validation($email_input);
        $username = $this->username_validation($username_input);
        $password = $this->password_validation($password_input, $confirm_password_input);
        $query = "INSERT INTO user (email, username, password, profile_pic) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssss', $email, $username, $password, $profile_pic);
        if (count($this->error)<=0){
            $stmt->execute();
            return 1;
        }
        else {
            return 0;
        }
    }
    protected function login_user_validation($email_or_username){
        if (empty(trim($email_or_username))){
            $this->error['email_or_username_error'] = "Email or Username Required";
        }
        elseif (!empty(trim($email_or_username))){
            return trim($email_or_username);
        }
    }
    protected function login_password_validation($password){
        if (empty(trim($password))){
            $this->error['password_error'] = "Password Required";
        }
        elseif (!empty(trim($password))){
            return trim($password);
        }
    }
    public function login($email_or_username_input, $password_input){
        $email_or_username = $this->login_user_validation($email_or_username_input);
        $password = $this->login_password_validation($password_input);
        if (count($this->error)<=0){
            if (str_contains($email_or_username_input, '@')){
                $query = "SELECT * FROM user WHERE email=?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('s', $email_or_username);
            }
            elseif (!str_contains($email_or_username_input, '@')){
                $query = "SELECT * FROM user WHERE username=?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('s', $email_or_username);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows==1){
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $password_check = $rows[0]['password'];
                if (password_verify($password, $password_check)){
                    $this->rows = $rows;
                    return 1;
                }
                else{
                    $this->error['login_error'] = "Username/Email or Password Incorrect";
                    return 0;
                }
            }
            else{
                $this->error['login_error'] = "Username/Email or Password Incorrect";
                return 0;
            }
        }
        else{
            return 1;
        }
    }
    public function getError(){
        return $this->error;
    }
    public function getRows(){
        return $this->rows; 
    }
}
?>