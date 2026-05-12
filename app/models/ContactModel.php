<?php
require_once 'BaseModel.php';

class ContactModel extends BaseModel
{
    public function create($data)
    {
        $this->db->query("INSERT INTO contact (name, email, phone, subject, message, status, memberId) VALUES (:name, :email, :phone, :subject, :message, 'pending', :memberId)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':memberId', $data['memberId']);
        return $this->db->execute();
    }

    public function getAll($limit, $offset, $search = '', $status = '')
    {
        $sql = "SELECT c.*, u.fullname AS member_name FROM contact c LEFT JOIN user u ON c.memberId = u.userId WHERE 1 = 1";
        if ($search !== '') {
            $sql .= " AND (c.name LIKE :kw1 OR c.email LIKE :kw2 OR c.phone LIKE :kw3 OR c.subject LIKE :kw4 OR c.message LIKE :kw5)";
        }
        if ($status !== '') {
            $sql .= " AND c.status = :status";
        }
        $sql .= " ORDER BY c.contactId DESC LIMIT :limit OFFSET :offset";
        $this->db->query($sql);
        if ($search !== '') {
            $keyword = '%' . $search . '%';
            $this->db->bind(':kw1', $keyword);
            $this->db->bind(':kw2', $keyword);
            $this->db->bind(':kw3', $keyword);
            $this->db->bind(':kw4', $keyword);
            $this->db->bind(':kw5', $keyword);
        }
        if ($status !== '') {
            $this->db->bind(':status', $status);
        }
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function countAll($search = '', $status = '')
    {
        $sql = "SELECT COUNT(*) AS total FROM contact WHERE 1 = 1";
        if ($search !== '') {
            $sql .= " AND (name LIKE :kw1 OR email LIKE :kw2 OR phone LIKE :kw3 OR subject LIKE :kw4 OR message LIKE :kw5)";
        }
        if ($status !== '') {
            $sql .= " AND status = :status";
        }
        $this->db->query($sql);
        if ($search !== '') {
            $keyword = '%' . $search . '%';
            $this->db->bind(':kw1', $keyword);
            $this->db->bind(':kw2', $keyword);
            $this->db->bind(':kw3', $keyword);
            $this->db->bind(':kw4', $keyword);
            $this->db->bind(':kw5', $keyword);
        }
        if ($status !== '') {
            $this->db->bind(':status', $status);
        }
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    public function getById($id)
    {
        $this->db->query("SELECT c.*, u.fullname AS member_name FROM contact c LEFT JOIN user u ON c.memberId = u.userId WHERE c.contactId = :id");
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function updateStatus($id, $status, $replyContent, $adminId)
    {
        $this->db->query("UPDATE contact SET status = :status, reply_content = :reply_content, adminId = :adminId WHERE contactId = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':reply_content', $replyContent);
        $this->db->bind(':adminId', $adminId);
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM contact WHERE contactId = :id");
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function getStats()
    {
        $this->db->query("SELECT status, COUNT(*) AS total FROM contact GROUP BY status");
        $rows = $this->db->resultSet();
        $stats = ['pending' => 0, 'read' => 0, 'replied' => 0, 'all' => 0];
        foreach ($rows as $row) {
            $stats[$row->status] = (int)$row->total;
            $stats['all'] += (int)$row->total;
        }
        return $stats;
    }
}
