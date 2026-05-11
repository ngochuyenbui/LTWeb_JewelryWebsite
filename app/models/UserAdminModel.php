<?php
require_once 'BaseModel.php';

class UserAdminModel extends BaseModel {
    public function getAllUsersPaginated($limit, $offset, $search = '') {
        $sql = "SELECT * FROM user WHERE role IN ('member', 'locked') ";
        if (!empty($search)) {
            $sql .= " AND (username LIKE :search OR email LIKE :search OR fullname LIKE :search) ";
        }
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        if (!empty($search)) {
            $this->db->bind(':search', "%$search%");
        }
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getTotalUsersCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM user WHERE role IN ('member', 'locked') ";
        if (!empty($search)) {
            $sql .= " AND (username LIKE :search OR email LIKE :search OR fullname LIKE :search) ";
        }
        $this->db->query($sql);
        if (!empty($search)) {
            $this->db->bind(':search', "%$search%");
        }
        $row = $this->db->single();
        return $row['total'] ?? 0;
    }

    public function getUserDetails($userId) {
        $this->db->query("SELECT u.*, m.phonenum, m.address, m.rewardPoint 
                          FROM user u 
                          LEFT JOIN member m ON u.userId = m.userId 
                          WHERE u.userId = :userId AND u.role IN ('member', 'locked')");
        $this->db->bind(':userId', $userId);
        return $this->db->single();
    }

    public function resetPassword($userId, $newHash) {
        $this->db->query("UPDATE user SET pwd_hash = :hash WHERE userId = :userId");
        $this->db->bind(':hash', $newHash);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    public function toggleLock($userId, $isLocked) {
        $newRole = $isLocked ? 'locked' : 'member';
        $this->db->query("UPDATE user SET role = :role WHERE userId = :userId AND role IN ('member', 'locked')");
        $this->db->bind(':role', $newRole);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }
}