<?php
require_once 'BaseModel.php';

class ProfileModel extends BaseModel {
    // Lấy tất cả thông tin của 1 user (bao gồm user và member)
    public function getUserProfile($userId) {
        $this->db->query("SELECT u.userId, u.username, u.fullname, u.email, u.avatar, m.phonenum as phone, m.address, m.rewardPoint 
                          FROM user u 
                          LEFT JOIN member m ON u.userId = m.userId 
                          WHERE u.userId = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->single();
    }

    // Cập nhật thông tin cơ bản
    public function updateProfile($userId, $fullname, $phone, $address) {
        $this->db->query("UPDATE user SET fullname = :fullname WHERE userId = :userId");
        $this->db->bind(':fullname', $fullname);
        $this->db->bind(':userId', $userId);
        $this->db->execute();

        $this->db->query("INSERT INTO member (userId, phonenum, address) VALUES (:userId, :phone, :address) 
                          ON DUPLICATE KEY UPDATE phonenum = :phone, address = :address");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':address', $address);
        return $this->db->execute();
    }

    // Cập nhật Avatar
    public function updateAvatar($userId, $avatarUrl) {
        $this->db->query("UPDATE user SET avatar = :avatar WHERE userId = :userId");
        $this->db->bind(':avatar', $avatarUrl);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    // Lấy Mật khẩu mã hóa để kiểm tra
    public function getPasswordHash($userId) {
        $this->db->query("SELECT pwd_hash FROM user WHERE userId = :userId");
        $this->db->bind(':userId', $userId);
        $row = $this->db->single();
        return $row ? (is_object($row) ? $row->pwd_hash : $row['pwd_hash']) : null;
    }

    // Cập nhật Mật khẩu mới
    public function updatePassword($userId, $newHash) {
        $this->db->query("UPDATE user SET pwd_hash = :hash WHERE userId = :userId");
        $this->db->bind(':hash', $newHash);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }
}