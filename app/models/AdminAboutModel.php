<?php
require_once 'BaseModel.php';

class AdminAboutModel extends BaseModel
{
    private $sections = [
        'company-images' => [
            'label' => 'Hình ảnh công ty',
            'table' => 'about_company_image',
            'primaryKey' => 'imageId',
            'icon' => 'ti-image',
            'columns' => [
                'image_url' => ['label' => 'Ảnh', 'type' => 'image'],
                'title' => ['label' => 'Tiêu đề'],
                'description' => ['label' => 'Mô tả'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'sort_order' => ['label' => 'Thứ tự'],
            ],
            'fields' => [
                'title' => ['label' => 'Tiêu đề', 'type' => 'text', 'required' => true],
                'description' => ['label' => 'Mô tả', 'type' => 'textarea'],
                'image_url' => ['label' => 'Hình ảnh', 'type' => 'file', 'required' => true, 'accept' => 'image/*', 'maxSize' => 104857600],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'active', 'options' => ['active' => 'Hiển thị', 'inactive' => 'Ẩn']],
                'sort_order' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0],
            ],
        ],
        'values' => [
            'label' => 'Giá trị cốt lõi',
            'table' => 'about_value',
            'primaryKey' => 'valueId',
            'icon' => 'ti-star',
            'columns' => [
                'icon' => ['label' => 'Icon'],
                'title' => ['label' => 'Tiêu đề'],
                'description' => ['label' => 'Mô tả'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'sort_order' => ['label' => 'Thứ tự'],
            ],
            'fields' => [
                'icon' => ['label' => 'Icon', 'type' => 'select', 'default' => 'sparkles', 'options' => [
                    'award' => 'Award',
                    'heart' => 'Heart',
                    'users' => 'Users',
                    'sparkles' => 'Sparkles',
                ]],
                'title' => ['label' => 'Tiêu đề', 'type' => 'text', 'required' => true],
                'description' => ['label' => 'Mô tả', 'type' => 'textarea', 'required' => true],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'active', 'options' => ['active' => 'Hiển thị', 'inactive' => 'Ẩn']],
                'sort_order' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0],
            ],
        ],
        'testimonials' => [
            'label' => 'Khách hàng chia sẻ',
            'table' => 'customer_testimonial',
            'primaryKey' => 'testimonialId',
            'icon' => 'ti-comment-alt',
            'columns' => [
                'avatar_url' => ['label' => 'Avatar', 'type' => 'image'],
                'customer_name' => ['label' => 'Khách hàng'],
                'customer_role' => ['label' => 'Vai trò'],
                'rating' => ['label' => 'Đánh giá'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'sort_order' => ['label' => 'Thứ tự'],
            ],
            'fields' => [
                'customer_name' => ['label' => 'Tên khách hàng', 'type' => 'text', 'required' => true],
                'customer_role' => ['label' => 'Vai trò', 'type' => 'text'],
                'avatar_url' => ['label' => 'Avatar', 'type' => 'file', 'accept' => 'image/*', 'maxSize' => 104857600],
                'content' => ['label' => 'Nội dung chia sẻ', 'type' => 'textarea', 'required' => true],
                'rating' => ['label' => 'Số sao', 'type' => 'number', 'default' => 5, 'min' => 1, 'max' => 5],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'active', 'options' => ['active' => 'Hiển thị', 'inactive' => 'Ẩn']],
                'sort_order' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0],
            ],
        ],
        'branches' => [
            'label' => 'Hệ thống cửa hàng',
            'table' => 'branch',
            'primaryKey' => 'branchId',
            'icon' => 'ti-location-pin',
            'columns' => [
                'city' => ['label' => 'Thành phố'],
                'address' => ['label' => 'Địa chỉ'],
                'phone' => ['label' => 'Điện thoại'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'sort_order' => ['label' => 'Thứ tự'],
            ],
            'fields' => [
                'city' => ['label' => 'Thành phố', 'type' => 'text', 'required' => true],
                'address' => ['label' => 'Địa chỉ', 'type' => 'text', 'required' => true],
                'phone' => ['label' => 'Điện thoại', 'type' => 'text'],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'active', 'options' => ['active' => 'Hiển thị', 'inactive' => 'Ẩn']],
                'sort_order' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0],
            ],
        ],
        'jobs' => [
            'label' => 'Tuyển dụng',
            'table' => 'recruitment_job',
            'primaryKey' => 'jobId',
            'icon' => 'ti-briefcase',
            'columns' => [
                'title' => ['label' => 'Vị trí'],
                'location' => ['label' => 'Địa điểm'],
                'job_type' => ['label' => 'Loại việc'],
                'experience_level' => ['label' => 'Kinh nghiệm'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'sort_order' => ['label' => 'Thứ tự'],
            ],
            'fields' => [
                'title' => ['label' => 'Vị trí', 'type' => 'text', 'required' => true],
                'location' => ['label' => 'Địa điểm', 'type' => 'text', 'required' => true],
                'job_type' => ['label' => 'Loại việc', 'type' => 'text', 'default' => 'Full-time', 'required' => true],
                'experience_level' => ['label' => 'Kinh nghiệm', 'type' => 'text'],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'open', 'options' => ['open' => 'Đang tuyển', 'closed' => 'Đã đóng']],
                'sort_order' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0],
            ],
        ],
        'job-applications' => [
            'label' => 'Hồ sơ ứng tuyển',
            'table' => 'job_application',
            'primaryKey' => 'applicationId',
            'icon' => 'ti-folder',
            'orderBy' => '`created_at` DESC, `applicationId` DESC',
            'columns' => [
                'fullname' => ['label' => 'Ứng viên'],
                'email' => ['label' => 'Email'],
                'phone' => ['label' => 'Điện thoại'],
                'position' => ['label' => 'Vị trí'],
                'location' => ['label' => 'Địa điểm'],
                'cv_path' => ['label' => 'CV', 'type' => 'file'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'created_at' => ['label' => 'Ngày nộp', 'type' => 'datetime'],
            ],
            'fields' => [
                'fullname' => ['label' => 'Họ và tên', 'type' => 'text', 'required' => true],
                'email' => ['label' => 'Email', 'type' => 'email', 'required' => true],
                'phone' => ['label' => 'Số điện thoại', 'type' => 'tel', 'required' => true],
                'position' => ['label' => 'Vị trí ứng tuyển', 'type' => 'text', 'required' => true],
                'location' => ['label' => 'Chi nhánh / địa điểm', 'type' => 'text'],
                'cv_path' => ['label' => 'CV', 'type' => 'file', 'required' => true, 'accept' => '.pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'fileKind' => 'document', 'allowedExtensions' => ['pdf', 'doc', 'docx'], 'uploadDir' => 'cv', 'pathPrefix' => 'assets/uploads/cv/', 'maxSize' => 104857600],
                'cover_letter' => ['label' => 'Thư giới thiệu', 'type' => 'textarea'],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'new', 'options' => [
                    'new' => 'Mới',
                    'reviewed' => 'Đã xem',
                    'interview' => 'Hẹn phỏng vấn',
                    'offered' => 'Đã nhận',
                    'rejected' => 'Từ chối',
                ]],
            ],
        ],
        'stats' => [
            'label' => 'Thống kê',
            'table' => 'about_stat',
            'primaryKey' => 'statId',
            'icon' => 'ti-bar-chart',
            'columns' => [
                'stat_value' => ['label' => 'Số liệu'],
                'label' => ['label' => 'Nhãn'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'sort_order' => ['label' => 'Thứ tự'],
            ],
            'fields' => [
                'stat_value' => ['label' => 'Số liệu', 'type' => 'text', 'required' => true],
                'label' => ['label' => 'Nhãn', 'type' => 'text', 'required' => true],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'active', 'options' => ['active' => 'Hiển thị', 'inactive' => 'Ẩn']],
                'sort_order' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0],
            ],
        ],
    ];

    public function getSections()
    {
        return $this->sections;
    }

    public function getSection($sectionKey)
    {
        return $this->sections[$sectionKey] ?? null;
    }

    public function ensureTables()
    {
        $statements = [
            "CREATE TABLE IF NOT EXISTS `about_company_image` (
                `imageId` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(150) NOT NULL,
                `description` varchar(255) DEFAULT NULL,
                `image_url` varchar(500) NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `sort_order` int(11) NOT NULL DEFAULT 0,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`imageId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `about_value` (
                `valueId` int(11) NOT NULL AUTO_INCREMENT,
                `icon` varchar(50) NOT NULL DEFAULT 'sparkles',
                `title` varchar(120) NOT NULL,
                `description` varchar(255) NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `sort_order` int(11) NOT NULL DEFAULT 0,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`valueId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `customer_testimonial` (
                `testimonialId` int(11) NOT NULL AUTO_INCREMENT,
                `customer_name` varchar(120) NOT NULL,
                `customer_role` varchar(150) DEFAULT NULL,
                `avatar_url` varchar(500) DEFAULT NULL,
                `content` text NOT NULL,
                `rating` int(1) NOT NULL DEFAULT 5,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `sort_order` int(11) NOT NULL DEFAULT 0,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`testimonialId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `branch` (
                `branchId` int(11) NOT NULL AUTO_INCREMENT,
                `city` varchar(120) NOT NULL,
                `address` varchar(255) NOT NULL,
                `phone` varchar(30) DEFAULT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `sort_order` int(11) NOT NULL DEFAULT 0,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`branchId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `recruitment_job` (
                `jobId` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(180) NOT NULL,
                `location` varchar(120) NOT NULL,
                `job_type` varchar(80) NOT NULL DEFAULT 'Full-time',
                `experience_level` varchar(180) DEFAULT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'open',
                `sort_order` int(11) NOT NULL DEFAULT 0,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`jobId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `job_application` (
                `applicationId` int(11) NOT NULL AUTO_INCREMENT,
                `fullname` varchar(150) NOT NULL,
                `email` varchar(150) NOT NULL,
                `phone` varchar(20) NOT NULL,
                `position` varchar(180) NOT NULL,
                `location` varchar(120) DEFAULT NULL,
                `cv_path` varchar(500) NOT NULL,
                `cover_letter` text NULL,
                `status` varchar(20) NOT NULL DEFAULT 'new',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`applicationId`),
                KEY `status` (`status`),
                KEY `created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `about_stat` (
                `statId` int(11) NOT NULL AUTO_INCREMENT,
                `stat_value` varchar(30) NOT NULL,
                `label` varchar(120) NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `sort_order` int(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`statId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        ];

        foreach ($statements as $sql) {
            $this->db->query($sql);
            $this->db->execute();
        }
    }

    public function getRows($sectionKey, $limit = null, $offset = 0, array $filters = [])
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return [];
        }

        $table = $this->identifier($section['table']);
        $primaryKey = $section['primaryKey'];
        $columns = $this->selectColumns($section);
        $orderBy = $section['orderBy'] ?? (
            isset($section['fields']['sort_order'])
                ? $this->identifier('sort_order') . ' ASC, ' . $this->identifier($primaryKey) . ' ASC'
                : $this->identifier($primaryKey) . ' DESC'
        );
        [$whereSql, $bindings] = $this->buildFilterSql($section, $filters);

        $sql = "SELECT {$columns} FROM {$table}{$whereSql} ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $this->db->query($sql);
        $this->bindFilterValues($bindings);
        if ($limit !== null) {
            $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
            $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        }
        return $this->db->resultSet();
    }

    public function countRows($sectionKey, array $filters = [])
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return 0;
        }

        $table = $this->identifier($section['table']);
        [$whereSql, $bindings] = $this->buildFilterSql($section, $filters);
        $this->db->query("SELECT COUNT(*) AS total FROM {$table}{$whereSql}");
        $this->bindFilterValues($bindings);
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    public function getRow($sectionKey, $id)
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return null;
        }

        $table = $this->identifier($section['table']);
        $primaryKey = $this->identifier($section['primaryKey']);
        $columns = $this->selectColumns($section);

        $this->db->query("SELECT {$columns} FROM {$table} WHERE {$primaryKey} = :id LIMIT 1");
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    public function create($sectionKey, array $data)
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return false;
        }

        $fieldNames = array_keys($section['fields']);
        $columns = implode(', ', array_map([$this, 'identifier'], $fieldNames));
        $placeholders = ':' . implode(', :', $fieldNames);
        $table = $this->identifier($section['table']);

        $this->db->query("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        $this->bindPayload($fieldNames, $data);
        return $this->db->execute();
    }

    public function update($sectionKey, $id, array $data)
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return false;
        }

        $fieldNames = array_keys($section['fields']);
        $assignments = [];
        foreach ($fieldNames as $fieldName) {
            $assignments[] = $this->identifier($fieldName) . ' = :' . $fieldName;
        }

        $table = $this->identifier($section['table']);
        $primaryKey = $this->identifier($section['primaryKey']);
        $this->db->query("UPDATE {$table} SET " . implode(', ', $assignments) . " WHERE {$primaryKey} = :id");
        $this->bindPayload($fieldNames, $data);
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    public function delete($sectionKey, $id)
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return false;
        }

        $table = $this->identifier($section['table']);
        $primaryKey = $this->identifier($section['primaryKey']);
        $this->db->query("DELETE FROM {$table} WHERE {$primaryKey} = :id");
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    private function selectColumns(array $section)
    {
        $columns = array_unique(array_merge([$section['primaryKey']], array_keys($section['columns'] ?? []), array_keys($section['fields'])));
        return implode(', ', array_map([$this, 'identifier'], $columns));
    }

    private function bindPayload(array $fieldNames, array $data)
    {
        foreach ($fieldNames as $fieldName) {
            $this->db->bind(':' . $fieldName, $data[$fieldName] ?? null);
        }
    }

    private function buildFilterSql(array $section, array $filters)
    {
        $conditions = [];
        $bindings = [];
        $query = trim((string)($filters['q'] ?? ''));
        $status = trim((string)($filters['status'] ?? ''));

        if ($query !== '') {
            $searchConditions = [];
            foreach ($this->searchableColumns($section) as $column) {
                $searchConditions[] = $this->identifier($column) . ' LIKE :search';
            }

            if (!empty($searchConditions)) {
                $conditions[] = '(' . implode(' OR ', $searchConditions) . ')';
                $bindings[':search'] = '%' . $query . '%';
            }
        }

        if ($status !== '' && isset($section['fields']['status'])) {
            $conditions[] = $this->identifier('status') . ' = :status';
            $bindings[':status'] = $status;
        }

        return [
            empty($conditions) ? '' : ' WHERE ' . implode(' AND ', $conditions),
            $bindings,
        ];
    }

    private function searchableColumns(array $section)
    {
        $columns = [];
        $allFields = array_merge($section['columns'] ?? [], $section['fields'] ?? []);
        foreach ($allFields as $fieldName => $field) {
            $type = $field['type'] ?? 'text';
            if (in_array($type, ['number', 'select', 'status', 'image', 'file', 'datetime', 'datetime-local'], true)) {
                continue;
            }
            $columns[] = $fieldName;
        }

        return array_values(array_unique($columns));
    }

    private function bindFilterValues(array $bindings)
    {
        foreach ($bindings as $param => $value) {
            $this->db->bind($param, $value);
        }
    }

    private function identifier($identifier)
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
