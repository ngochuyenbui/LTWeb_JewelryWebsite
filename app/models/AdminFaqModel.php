<?php
require_once 'BaseModel.php';

class AdminFaqModel extends BaseModel
{
    private $sections = [
        'faqs' => [
            'label' => 'Câu hỏi thường gặp',
            'table' => 'faq',
            'primaryKey' => 'faqId',
            'icon' => 'ti-help-alt',
            'orderBy' => '`priority` ASC, `faqId` ASC',
            'columns' => [
                'question' => ['label' => 'Câu hỏi'],
                'answer' => ['label' => 'Câu trả lời'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'priority' => ['label' => 'Thứ tự'],
            ],
            'fields' => [
                'question' => ['label' => 'Câu hỏi', 'type' => 'textarea', 'required' => true, 'minLength' => 5, 'maxLength' => 500],
                'answer' => ['label' => 'Câu trả lời', 'type' => 'textarea', 'required' => true, 'minLength' => 10, 'maxLength' => 3000],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'active', 'options' => ['active' => 'Hiển thị', 'inactive' => 'Ẩn']],
                'priority' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0, 'min' => 0, 'max' => 999],
            ],
        ],
        'submissions' => [
            'label' => 'Câu hỏi khách gửi',
            'table' => 'faq_question_submission',
            'primaryKey' => 'submissionId',
            'icon' => 'ti-email',
            'orderBy' => '`created_at` DESC, `submissionId` DESC',
            'columns' => [
                'fullname' => ['label' => 'Khách hàng'],
                'email' => ['label' => 'Email'],
                'phone' => ['label' => 'Số điện thoại'],
                'question' => ['label' => 'Câu hỏi'],
                'status' => ['label' => 'Trạng thái', 'type' => 'status'],
                'created_at' => ['label' => 'Ngày gửi', 'type' => 'datetime'],
                'answered_at' => ['label' => 'Ngày xử lý', 'type' => 'datetime'],
            ],
            'fields' => [
                'fullname' => ['label' => 'Họ và tên', 'type' => 'text', 'required' => true, 'minLength' => 2, 'maxLength' => 120],
                'email' => ['label' => 'Email', 'type' => 'email', 'required' => true, 'maxLength' => 150],
                'phone' => ['label' => 'Số điện thoại', 'type' => 'tel', 'maxLength' => 30],
                'question' => ['label' => 'Câu hỏi', 'type' => 'textarea', 'required' => true, 'minLength' => 10, 'maxLength' => 2000],
                'status' => ['label' => 'Trạng thái', 'type' => 'select', 'default' => 'new', 'options' => [
                    'new' => 'Mới',
                    'processing' => 'Đang xử lý',
                    'answered' => 'Đã trả lời',
                    'closed' => 'Đã đóng',
                ]],
                'answered_at' => ['label' => 'Ngày xử lý', 'type' => 'datetime-local'],
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
            "CREATE TABLE IF NOT EXISTS `faq` (
                `faqId` int(11) NOT NULL AUTO_INCREMENT,
                `question` text NOT NULL,
                `answer` text NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'active',
                `priority` int(5) NOT NULL DEFAULT 0,
                `creatorId` int(11) DEFAULT NULL,
                `contentId` int(11) DEFAULT NULL,
                PRIMARY KEY (`faqId`),
                KEY `status` (`status`),
                KEY `priority` (`priority`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `faq_question_submission` (
                `submissionId` int(11) NOT NULL AUTO_INCREMENT,
                `fullname` varchar(120) NOT NULL,
                `email` varchar(150) NOT NULL,
                `phone` varchar(30) DEFAULT NULL,
                `question` text NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'new',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `answered_at` datetime DEFAULT NULL,
                PRIMARY KEY (`submissionId`),
                KEY `status` (`status`),
                KEY `created_at` (`created_at`)
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
        $columns = $this->selectColumns($section);
        $orderBy = $section['orderBy'] ?? ($this->identifier($section['primaryKey']) . ' ASC');
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

    public function create($sectionKey, array $data, array $extra = [])
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return false;
        }

        $payload = array_merge($this->filterPayload($section, $data), $extra);
        $fieldNames = array_keys($payload);
        $columns = implode(', ', array_map([$this, 'identifier'], $fieldNames));
        $placeholders = ':' . implode(', :', $fieldNames);
        $table = $this->identifier($section['table']);

        $this->db->query("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        $this->bindPayload($fieldNames, $payload);
        return $this->db->execute();
    }

    public function update($sectionKey, $id, array $data)
    {
        $section = $this->getSection($sectionKey);
        if (!$section) {
            return false;
        }

        $payload = $this->filterPayload($section, $data);
        $fieldNames = array_keys($payload);
        $assignments = [];
        foreach ($fieldNames as $fieldName) {
            $assignments[] = $this->identifier($fieldName) . ' = :' . $fieldName;
        }

        $table = $this->identifier($section['table']);
        $primaryKey = $this->identifier($section['primaryKey']);
        $this->db->query("UPDATE {$table} SET " . implode(', ', $assignments) . " WHERE {$primaryKey} = :id");
        $this->bindPayload($fieldNames, $payload);
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

    private function filterPayload(array $section, array $data)
    {
        $payload = [];
        foreach (array_keys($section['fields']) as $fieldName) {
            $payload[$fieldName] = $data[$fieldName] ?? null;
        }
        return $payload;
    }

    private function selectColumns(array $section)
    {
        $columns = array_unique(array_merge([$section['primaryKey']], array_keys($section['columns']), array_keys($section['fields'])));
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
            if (in_array($type, ['number', 'select', 'status', 'image', 'datetime', 'datetime-local'], true)) {
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
