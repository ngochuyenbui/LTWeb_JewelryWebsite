<?php
// core/Database.php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $stmt; // Statement
    private $error;

    public function __construct() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // Cấu hình PDO
        $options = array(
            PDO::ATTR_PERSISTENT => true, // Giữ kết nối
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Báo lỗi dạng ngoại lệ (Exception)
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ // Fetch dữ liệu dạng Object
        );

        // Tạo instance PDO mới
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "Lỗi kết nối cơ sở dữ liệu: " . $this->error;
        }
    }

    // Chuẩn bị câu lệnh SQL
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind giá trị
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Thực thi prepared statement
    public function execute() {
        return $this->stmt->execute();
    }

    // Lấy danh sách kết quả (nhiều dòng)
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Lấy một dòng kết quả
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Lấy số lượng hàng bị ảnh hưởng
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Lấy ID vừa được insert
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
