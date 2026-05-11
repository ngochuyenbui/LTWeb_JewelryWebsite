<?php
require_once 'BaseModel.php';
class UserModel extends BaseModel
{
    private function rowToArray($row)
    {
        return $row ? (array)$row : null;
    }

    public function getUserByUsername($username)
    {
        $this->db->query("SELECT * FROM user WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->rowToArray($this->db->single());
    }
    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM user WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->rowToArray($this->db->single());
    }
    public function addUser($data){
        $this->db->query("INSERT INTO user (username, pwd_hash, email, role) VALUES (:username, :pwd_hash, :email, :role)");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':pwd_hash', $data['password']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', ROLE_USER);
        
        if ($this->db->execute()) {
            $userId = $this->db->lastInsertId();
            $this->db->query("INSERT INTO member (userId) VALUES (:userId)");
            $this->db->bind(':userId', $userId);
            return $this->db->execute();
        }
        return false;
    }

    public function getTotalUsers() {
        $this->db->query("SELECT COUNT(*) as total FROM user WHERE role = :role");
        $this->db->bind(':role', ROLE_USER);
        $row = $this->rowToArray($this->db->single());
        return $row ? (int)$row['total'] : 0;
    }
}
