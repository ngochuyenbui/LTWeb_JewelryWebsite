<?php
class UserModel extends Database
{
    public function getUserByUsername($username)
    {
        $this->query("SELECT * FROM users WHERE username = :username");
        $this->bind(':username', $username);
        return $this->single();
    }
    public function addUser($data){
        $this->query("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
        $this->bind(':username', $data['username']);
        $this->bind(':password', $data['password']);
        $this->bind(':email', $data['email']);
        $this->bind(':role', ROLE_USER);
        return $this->execute();
    }
}



?>