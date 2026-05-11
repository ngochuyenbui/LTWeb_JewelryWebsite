<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - AURELIA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8f6f2;
            color: #1e1b1a;
        }

        .news-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Hero */
        .hero-section {
            text-align: center;
            margin-bottom: 3rem;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: 24px;
            color: #f5e6b8;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(218, 165, 32, 0.2);
        }
        .hero-section::before {
            content: '✦';
            position: absolute;
            font-size: 300px;
            bottom: -80px;
            right: -50px;
            opacity: 0.05;
            font-family: serif;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #f5e6b8 0%, #d9b461 50%, #f5e6b8 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .hero-section p { font-size: 1rem; opacity: 0.8; max-width: 600px; margin: 0 auto; }

        /* Search */
        .search-wrapper { max-width: 500px; margin: -1.5rem auto 3rem; position: relative; z-index: 2; }
        .search-form {
            background: white;
            border-radius: 60px;
            padding: 0.25rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            border: 1px solid rgba(218,165,32,0.2);
        }
        .search-input {
            flex: 1;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 0.9rem;
            border-radius: 60px;
            outline: none;
            font-family: inherit;
            background: transparent;
        }
        .search-input::placeholder { color: #aaa; }
        .search-btn {
            background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 60px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .search-btn:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(185,133,45,0.4); }

        /* ===================== GROUPED LAYOUT ===================== */
        .category-section { margin-bottom: 3.5rem; }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(218,165,32,0.2);
        }
        .category-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e1b1a;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .category-header h2::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 1.4rem;
            background: linear-gradient(135deg, #d9b461, #b5852d);
            border-radius: 2px;
        }
        .view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #b5852d;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            border: 1px solid rgba(181,133,45,0.3);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .view-all-link:hover {
            background: #b5852d;
            color: white;
            border-color: #b5852d;
        }

        /* Cards grid */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 1.75rem;
        }
        .articles-grid.featured-grid {
            grid-template-columns: 1.6fr 1fr 1fr;
        }

        /* Article card */
        .article-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: 1px solid rgba(218,165,32,0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: rgba(218,165,32,0.3);
        }

        .card-image {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            background: #e8e2d4;
        }
        .articles-grid.featured-grid .article-card:first-child .card-image {
            aspect-ratio: 16 / 10;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .article-card:hover .card-image img { transform: scale(1.08); }
        .no-img-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-tag {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(26,26,46,0.9);
            backdrop-filter: blur(5px);
            padding: 0.375rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #d9b461;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 1;
        }

        .card-content { padding: 1.5rem; }
        .card-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.7rem;
            color: #999;
            align-items: center;
        }
        .meta-item { display: inline-flex; align-items: center; gap: 0.3rem; }
        .meta-icon { width: 12px; height: 12px; stroke: #b5852d; stroke-width: 1.8; fill: none; flex-shrink: 0; }

        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e1b1a;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            transition: color 0.3s ease;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .article-card:hover .card-title { color: #b5852d; }

        .card-excerpt {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #b5852d;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            transition: gap 0.3s ease;
        }
        .read-more:hover { gap: 0.75rem; color: #d9b461; }

        /* ===================== FLAT LAYOUT (search/category page) ===================== */
        .flat-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .flat-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e1b1a;
        }
        .search-keyword-badge {
            background: linear-gradient(135deg, #d9b461, #b5852d);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .results-count { color: #888; font-size: 0.9rem; }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2.5rem;
        }
        .page-link {
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 12px;
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(218,165,32,0.2);
        }
        .page-link:hover { background: #d9b461; color: white; border-color: #d9b461; }
        .page-link.active {
            background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%);
            color: white;
            border-color: transparent;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 24px;
            margin: 2rem 0;
            border: 1px solid rgba(218,165,32,0.15);
        }
        .empty-state p { font-size: 1.1rem; color: #888; margin-top: 1rem; }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #b5852d;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: gap 0.3s ease;
        }
        .back-link:hover { gap: 0.75rem; }

        /* Responsive */
        @media (max-width: 1024px) {
            .articles-grid.featured-grid { grid-template-columns: 1fr 1fr; }
            .articles-grid.featured-grid .article-card:first-child { grid-column: span 2; }
        }
        @media (max-width: 768px) {
            .news-container { padding: 1rem; }
            .hero-section h1 { font-size: 2rem; }
            .hero-section { padding: 2rem 1rem; }
            .articles-grid, .articles-grid.featured-grid { grid-template-columns: 1fr; gap: 1.25rem; }
            .articles-grid.featured-grid .article-card:first-child { grid-column: span 1; }
            .search-form { border-radius: 24px; flex-direction: column; padding: 0.5rem; gap: 0.5rem; }
            .search-input { border-radius: 60px; background: #f8f6f2; }
            .search-btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
<div class="news-container">
    <!-- Hero -->
    <div class="hero-section">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p>Khám phá những xu hướng trang sức mới nhất, bí quyết phong cách và câu chuyện thương hiệu từ Aurelia.</p>
    </div>

    <!-- Search bar -->
    <div class="search-wrapper">
        <form action="<?= URLROOT ?>/News/index" method="GET" class="search-form">
            <input type="text" name="search" value="<?= htmlspecialchars($searchKeyword) ?>"
                   placeholder="Tìm kiếm bài viết theo tiêu đề..." class="search-input" id="search-input">
            <button type="submit" class="search-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                Tìm kiếm
            </button>
        </form>
    </div>

    <?php if (!empty($isGrouped)): ?>
        <!-- ===================== GROUPED BY CATEGORY LAYOUT ===================== -->
        <?php if (empty($groupedArticles)): ?>
            <div class="empty-state">
                <svg style="width: 80px; height: 80px; stroke: #d9b461;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <p>Chưa có bài viết nào. Vui lòng quay lại sau!</p>
            </div>
        <?php else: ?>
            <?php foreach ($groupedArticles as $group): ?>
                <section class="category-section">
                    <div class="category-header">
                        <h2><?= htmlspecialchars($group['category']->name) ?></h2>
                        <a href="<?= URLROOT ?>/News/category/<?= $group['category']->cateId ?>" class="view-all-link">
                            Xem tất cả
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>

                    <div class="articles-grid featured-grid">
                        <?php foreach ($group['articles'] as $index => $article): ?>
                            <article class="article-card" style="animation-delay: <?= $index * 0.08 ?>s">
                                <a href="<?= URLROOT ?>/News/detail/<?= $article->articleId ?>" style="text-decoration: none; color: inherit; display: block; height: 100%;">
                                    <div class="card-image">
                                        <?php if (!empty($article->thumbnail)): ?>
                                            <img src="<?= URLROOT . '/' . htmlspecialchars($article->thumbnail) ?>"
                                                 alt="<?= htmlspecialchars($article->title) ?>">
                                        <?php else: ?>
                                            <div class="no-img-placeholder">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                                                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="category-tag"><?= htmlspecialchars($article->category_name ?? 'Tin tức') ?></div>
                                    </div>

                                    <div class="card-content">
                                        <div class="card-meta">
                                            <span class="meta-item">
                                                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                <?= date('d/m/Y', strtotime($article->published_at ?? 'now')) ?>
                                            </span>
                                            <span class="meta-item">
                                                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <?= htmlspecialchars($article->author_name ?? 'Aurelia') ?>
                                            </span>
                                        </div>

                                        <h2 class="card-title"><?= htmlspecialchars($article->title) ?></h2>

                                        <?php if (!empty($article->content)): ?>
                                        <div class="card-excerpt">
                                            <?php
                                                $decoded = html_entity_decode($article->content, ENT_QUOTES, 'UTF-8');
                                                $clean = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $decoded);
                                                echo mb_substr(strip_tags($clean), 0, 120) . '...';
                                            ?>
                                        </div>
                                        <?php endif; ?>

                                        <span class="read-more">
                                            Đọc tiếp
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php else: ?>
        <!-- ===================== FLAT LAYOUT (search / category page) ===================== -->
        <?php if (!empty($searchKeyword)): ?>
            <a href="<?= URLROOT ?>/News/index" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Quay lại tất cả bài viết
            </a>
        <?php elseif (!empty($cateId)): ?>
            <a href="<?= URLROOT ?>/News/index" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Quay lại trang Tin tức
            </a>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <div class="empty-state">
                <svg style="width: 80px; height: 80px; stroke: #d9b461;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    <path d="M11 8v6"></path>
                    <path d="M8 11h6"></path>
                </svg>
                <p>Không tìm thấy bài viết nào phù hợp.</p>
            </div>
        <?php else: ?>
            <div class="articles-grid">
                <?php foreach ($articles as $index => $article): ?>
                    <article class="article-card" style="animation-delay: <?= $index * 0.05 ?>s">
                        <a href="<?= URLROOT ?>/News/detail/<?= $article->articleId ?>" style="text-decoration: none; color: inherit; display: block;">
                            <div class="card-image">
                                <?php if (!empty($article->thumbnail)): ?>
                                    <img src="<?= URLROOT . '/' . htmlspecialchars($article->thumbnail) ?>"
                                         alt="<?= htmlspecialchars($article->title) ?>">
                                <?php else: ?>
                                    <div class="no-img-placeholder">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class="category-tag"><?= htmlspecialchars($article->category_name ?? 'Tin tức') ?></div>
                            </div>

                            <div class="card-content">
                                <div class="card-meta">
                                    <span class="meta-item">
                                        <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <?= date('d/m/Y', strtotime($article->published_at ?? 'now')) ?>
                                    </span>
                                    <span class="meta-item">
                                        <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <?= htmlspecialchars($article->author_name ?? 'Aurelia') ?>
                                    </span>
                                </div>

                                <h2 class="card-title"><?= htmlspecialchars($article->title) ?></h2>

                                <?php if (!empty($article->content)): ?>
                                <div class="card-excerpt">
                                    <?php
                                        $decoded = html_entity_decode($article->content, ENT_QUOTES, 'UTF-8');
                                        $clean = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $decoded);
                                        echo mb_substr(strip_tags($clean), 0, 120) . '...';
                                    ?>
                                </div>
                                <?php endif; ?>

                                <span class="read-more">
                                    Đọc tiếp
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($totalPages) && $totalPages > 1): ?>
                <div class="pagination">
                    <?php
                        $baseUrl = URLROOT . '/News/';
                        if (!empty($cateId)) {
                            $baseUrl .= 'category/' . $cateId . '?page=';
                        } else {
                            $baseUrl .= 'index?page=';
                            if (!empty($searchKeyword)) {
                                $extra = '&search=' . urlencode($searchKeyword);
                            }
                        }
                    ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= $baseUrl . $i . ($extra ?? '') ?>"
                           class="page-link <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>