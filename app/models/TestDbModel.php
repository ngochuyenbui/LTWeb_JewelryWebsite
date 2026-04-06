<?php
require_once "BaseModel.php";

class TestDbModel extends BaseModel {
    public function getSampleData() {
        // Thực hiện một truy vấn mẫu
        $this->db->query("SHOW TABLES");
        return $this->db->resultSet();
    }
}
