<?php
// app/controllers/Test.php

class Test extends Controller {
    public function index() {
        // Load model
        $testModel = $this->model('TestDbModel');
        $tables = $testModel->getSampleData();
        
        echo "<h2>Kết nối Database thành công! Các bảng hiện có:</h2>";
        echo "<pre>";
        print_r($tables);
        echo "</pre>";
    }
}
