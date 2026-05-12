<?php
require_once 'BaseModel.php';

class SiteContentModel extends BaseModel
{
    private $defaultContents = [
        'site_brand_name' => ['Cấu hình chung', 'AURELIA'],
        'site_tagline' => ['Cấu hình chung', 'Fine Jewelry'],
        'site_phone' => ['Cấu hình chung', '1900 8868'],
        'site_email' => ['Cấu hình chung', 'contact@aurelia.vn'],
        'site_address' => ['Cấu hình chung', '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh'],
        'home_eyebrow' => ['Trang chủ', 'Bộ sưu tập cưới 2026'],
        'home_title' => ['Trang chủ', 'Trang sức chế tác riêng cho khoảnh khắc đáng nhớ'],
        'home_intro' => ['Trang chủ', 'Aurelia tuyển chọn kim cương, vàng và đá quý đạt chuẩn kiểm định để tạo nên những thiết kế tinh tế, bền vững và giàu cảm xúc.'],
        'home_primary_cta' => ['Trang chủ', 'Khám phá bộ sưu tập'],
        'home_secondary_cta' => ['Trang chủ', 'Liên hệ tư vấn'],
        'home_feature_1_title' => ['Trang chủ', 'Chế tác thủ công'],
        'home_feature_1_content' => ['Trang chủ', 'Mỗi thiết kế được hoàn thiện bởi nghệ nhân có kinh nghiệm và kiểm tra kỹ trước khi giao đến khách hàng.'],
        'home_feature_2_title' => ['Trang chủ', 'Chất liệu minh bạch'],
        'home_feature_2_content' => ['Trang chủ', 'Nguồn vàng, bạc, kim cương và đá quý được chọn lọc theo tiêu chuẩn kiểm định rõ ràng.'],
        'home_feature_3_title' => ['Trang chủ', 'Bảo hành tận tâm'],
        'home_feature_3_content' => ['Trang chủ', 'Hỗ trợ làm sạch, đánh bóng và bảo hành định kỳ để sản phẩm luôn giữ vẻ sáng đẹp.'],
        'contact_heading' => ['Trang liên hệ', 'Liên hệ Aurelia'],
        'contact_intro' => ['Trang liên hệ', 'Đội ngũ tư vấn luôn sẵn sàng hỗ trợ chọn mẫu, đặt lịch thử nhẫn và giải đáp chính sách bảo hành.'],
        'contact_working_hours' => ['Trang liên hệ', '8:00 - 21:00, Thứ 2 đến Chủ nhật'],
    ];

    private $defaultImages = [
        'logo_main' => ['Logo chính', 'header', 'assets/news/kimcuonglabgrowncuoccachmangtr.webp'],
        'home_hero' => ['Ảnh hero trang chủ', 'homepage', 'assets/news/nhancuoihanquoclagixuhuongtran.webp'],
        'contact_banner' => ['Ảnh banner liên hệ', 'contact', 'assets/news/topthuonghieutrangsucuytintaiv.webp'],
    ];

    public function ensureDefaults($managerId = null)
    {
        foreach ($this->defaultContents as $key => $item) {
            if (!$this->contentExists($key)) {
                $this->db->query("SELECT COALESCE(MAX(pageId), 0) + 1 AS nextId FROM page_content");
                $next = $this->db->single();
                $this->db->query("INSERT INTO page_content (pageId, page_key, section, content, managerId) VALUES (:id, :page_key, :section, :content, :managerId)");
                $this->db->bind(':id', (int)$next->nextId);
                $this->db->bind(':page_key', $key);
                $this->db->bind(':section', $item[0]);
                $this->db->bind(':content', $item[1]);
                $this->db->bind(':managerId', $managerId);
                $this->db->execute();
            }
        }

        foreach ($this->defaultImages as $key => $item) {
            $image = $this->getImageByKey($key);
            if (!$image) {
                $this->db->query("INSERT INTO site_image (image_key, filepath, location_tag, managerId) VALUES (:image_key, :filepath, :location_tag, :managerId)");
                $this->db->bind(':image_key', $key);
                $this->db->bind(':filepath', $item[2]);
                $this->db->bind(':location_tag', $item[1]);
                $this->db->bind(':managerId', $managerId);
                $this->db->execute();
            } elseif (!$this->publicFileExists($image->filepath) && $this->publicFileExists($item[2])) {
                $this->db->query("UPDATE site_image SET filepath = :filepath, location_tag = :location_tag, managerId = :managerId WHERE image_key = :image_key");
                $this->db->bind(':filepath', $item[2]);
                $this->db->bind(':location_tag', $item[1]);
                $this->db->bind(':managerId', $managerId);
                $this->db->bind(':image_key', $key);
                $this->db->execute();
            }
        }
    }

    public function getContentMap()
    {
        $this->db->query("SELECT page_key, section, content FROM page_content");
        $rows = $this->db->resultSet();
        $map = [];
        foreach ($rows as $row) {
            $map[$row->page_key] = $row->content;
        }
        foreach ($this->defaultContents as $key => $item) {
            if (!isset($map[$key])) {
                $map[$key] = $item[1];
            }
        }
        return $map;
    }

    public function getImageMap()
    {
        $this->db->query("SELECT image_key, filepath, location_tag FROM site_image");
        $rows = $this->db->resultSet();
        $map = [];
        foreach ($rows as $row) {
            $map[$row->image_key] = $row;
        }
        foreach ($this->defaultImages as $key => $item) {
            if (!isset($map[$key])) {
                $map[$key] = (object)[
                    'image_key' => $key,
                    'filepath' => $item[2],
                    'location_tag' => $item[1],
                ];
            }
        }
        return $map;
    }

    public function getContentItems($limit, $offset, $search = '')
    {
        $sql = "SELECT pageId, page_key, section, content, updated_at FROM page_content WHERE (page_key LIKE 'site_%' OR page_key LIKE 'home_%' OR page_key LIKE 'contact_%')";
        if ($search !== '') {
            $sql .= " AND (page_key LIKE :kw1 OR section LIKE :kw2 OR content LIKE :kw3)";
        }
        $sql .= " ORDER BY section ASC, page_key ASC LIMIT :limit OFFSET :offset";
        $this->db->query($sql);
        if ($search !== '') {
            $keyword = '%' . $search . '%';
            $this->db->bind(':kw1', $keyword);
            $this->db->bind(':kw2', $keyword);
            $this->db->bind(':kw3', $keyword);
        }
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function countContentItems($search = '')
    {
        $sql = "SELECT COUNT(*) AS total FROM page_content WHERE (page_key LIKE 'site_%' OR page_key LIKE 'home_%' OR page_key LIKE 'contact_%')";
        if ($search !== '') {
            $sql .= " AND (page_key LIKE :kw1 OR section LIKE :kw2 OR content LIKE :kw3)";
        }
        $this->db->query($sql);
        if ($search !== '') {
            $keyword = '%' . $search . '%';
            $this->db->bind(':kw1', $keyword);
            $this->db->bind(':kw2', $keyword);
            $this->db->bind(':kw3', $keyword);
        }
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    public function getContentById($id)
    {
        $this->db->query("SELECT pageId, page_key, section, content FROM page_content WHERE pageId = :id");
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function updateContent($id, $content, $managerId)
    {
        $this->db->query("UPDATE page_content SET content = :content, updated_at = CURRENT_TIMESTAMP, managerId = :managerId WHERE pageId = :id");
        $this->db->bind(':content', $content);
        $this->db->bind(':managerId', $managerId);
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function getImages($limit, $offset)
    {
        $this->db->query("SELECT imageId, image_key, filepath, location_tag, created_at FROM site_image WHERE image_key IN ('logo_main', 'home_hero', 'contact_banner') ORDER BY image_key ASC LIMIT :limit OFFSET :offset");
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function countImages()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM site_image WHERE image_key IN ('logo_main', 'home_hero', 'contact_banner')");
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    public function getImageById($id)
    {
        $this->db->query("SELECT imageId, image_key, filepath, location_tag FROM site_image WHERE imageId = :id");
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function updateImage($id, $filepath, $managerId)
    {
        $this->db->query("UPDATE site_image SET filepath = :filepath, managerId = :managerId WHERE imageId = :id");
        $this->db->bind(':filepath', $filepath);
        $this->db->bind(':managerId', $managerId);
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function getStats()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM page_content WHERE page_key LIKE 'site_%' OR page_key LIKE 'home_%' OR page_key LIKE 'contact_%'");
        $content = $this->db->single();
        $this->db->query("SELECT COUNT(*) AS total FROM site_image WHERE image_key IN ('logo_main', 'home_hero', 'contact_banner')");
        $images = $this->db->single();
        return [
            'content' => $content ? (int)$content->total : 0,
            'images' => $images ? (int)$images->total : 0,
        ];
    }

    private function contentExists($key)
    {
        $this->db->query("SELECT pageId FROM page_content WHERE page_key = :page_key LIMIT 1");
        $this->db->bind(':page_key', $key);
        return (bool)$this->db->single();
    }

    private function getImageByKey($key)
    {
        $this->db->query("SELECT imageId, filepath FROM site_image WHERE image_key = :image_key LIMIT 1");
        $this->db->bind(':image_key', $key);
        return $this->db->single();
    }

    private function publicFileExists($path)
    {
        $path = ltrim((string)$path, '/');
        return $path !== '' && is_file(APPROOT . '/public/' . $path);
    }
}
