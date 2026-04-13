<?php
class UserModel extends Database
{
    public function getUserByUsername($username)
    {
        $this->query("SELECT * FROM user WHERE username = :username");
        $this->bind(':username', $username);
        return $this->single();
    }
    public function addUser($data){
        $this->query("INSERT INTO user (username, pwd_hash, email, role) VALUES (:username, :pwd_hash, :email, :role)");
        $this->bind(':username', $data['username']);
        $this->bind(':pwd_hash', $data['password']);
        $this->bind(':email', $data['email']);
        $this->bind(':role', ROLE_USER);
        return $this->execute();
    }
}



?>