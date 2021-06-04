<?php

class User{
    private $email;
    private $username;
    private $password;
    private $confirm_password;
    private $error = array();

    // Constructor have 2 underscore and not 1!!!!!
    public function __construct($email, $username, $password, $confirm_password){
        // $this->email = $this->email_validation($email, $link);
        // $this->username = $this->username_validation($username, $link);
        // $this->password = $this->password_validation($password, $confirm_password, $link);
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }

    protected function email_validation($email, $conn){
        if (empty(trim($email))){
            array_push($this->error, "Email Required");
        }
        else{
            $query = "SELECT * from user where email='$email'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0){
                array_push($this->error, "Email Existed");
            }
            else{
                    return trim($email);
            }
        }
    }
    protected function username_validation($username, $conn){
        if (empty(trim($username))){
            array_push($this->error, "Username Required");
        }
        else{
            $query = "SELECT * from user where username='$username'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0){
                array_push($this->error, "Username Existed");
            }
            else{
                    return trim($username);
            }
        }
    }
    protected function password_validation($password1, $password2, $conn){
        if (empty(trim($password1))){
            array_push($this->error, "Password Required");
        }
        elseif (empty(trim($password2))){
            array_push($this->error, "Confirm Password Required");
        }
        elseif (trim($password1) != trim($password2)){
            array_push($this->error, "Password Not Match");
        }
        else{
                return password_hash(trim($password1), PASSWORD_DEFAULT);
        }
    }
    public function getData($link){
        if (isset($this->confirm_password)){
            $this->email = $this->email_validation($this->email, $link);
            $this->username = $this->username_validation($this->username, $link);
            $this->password = $this->password_validation($this->password, $this->confirm_password, $link);
            return array('email'=>$this->email, 'username'=>$this->username, 'password'=>$this->password);
        }
    }
    public function getError(){
        return $this->error;
    }
}
?>