<?php
// app/models/BaseModel.php

class BaseModel {
    protected $db;

    public function __construct() {
        // Tái sử dụng kết nối Database
        $this->db = new Database();
    }
}
