<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức - AURELIA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8f6f2;
            color: #1e1b1a;
        }

        /* Container chính */
        .news-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Hero section - tone vàng/đồng */
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

        .hero-section p {
            font-size: 1rem;
            opacity: 0.8;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Search bar */
        .search-wrapper {
            max-width: 500px;
            margin: -1.5rem auto 3rem;
            position: relative;
            z-index: 2;
        }

        .search-form {
            background: white;
            border-radius: 60px;
            padding: 0.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            border: 1px solid rgba(218, 165, 32, 0.2);
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

        .search-input::placeholder {
            color: #aaa;
        }

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

        .search-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(185, 133, 45, 0.4);
        }

        /* Articles grid */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        /* Article card - phong cách sang trọng */
        .article-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(218, 165, 32, 0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: rgba(218, 165, 32, 0.3);
        }

        /* Image container - kích thước đồng bộ 4:3 */
        .card-image {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            background: #e8e2d4;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .article-card:hover .card-image img {
            transform: scale(1.08);
        }

        .category-tag {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(26, 26, 46, 0.9);
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

        /* Card content */
        .card-content {
            padding: 1.5rem;
        }

        .card-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.7rem;
            color: #999;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e1b1a;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            transition: color 0.3s ease;
        }

        .article-card:hover .card-title {
            color: #b5852d;
        }

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

        .read-more:hover {
            gap: 0.75rem;
            color: #d9b461;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
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
            border: 1px solid rgba(218, 165, 32, 0.2);
        }

        .page-link:hover {
            background: #d9b461;
            color: white;
            border-color: #d9b461;
        }

        .page-link.active {
            background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%);
            color: white;
            border-color: transparent;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem;
            background: white;
            border-radius: 24px;
            margin: 2rem 0;
            border: 1px solid rgba(218, 165, 32, 0.15);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .news-container {
                padding: 1rem;
            }
            
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .hero-section {
                padding: 2rem 1rem;
            }
            
            .articles-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .search-form {
                flex-direction: column;
                border-radius: 24px;
                background: white;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                gap: 0.5rem;
                padding: 0.5rem;
            }
            
            .search-input {
                border-radius: 60px;
                background: #f8f6f2;
            }
            
            .search-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="news-container">
    <!-- Hero section tone vàng/đồng -->
    <div class="hero-section">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p>Khám phá những xu hướng trang sức mới nhất, bí quyết phong cách và câu chuyện thương hiệu từ Aurelia.</p>
    </div>

    <!-- Search bar -->
    <div class="search-wrapper">
        <form action="<?= URLROOT ?>/News/index" method="GET" class="search-form">
            <input type="text" name="search" value="<?= htmlspecialchars($searchKeyword) ?>"
                   placeholder="🔍 Tìm kiếm bài viết..." class="search-input">
            <button type="submit" class="search-btn">
                <span>🔍</span> Tìm kiếm
            </button>
        </form>
    </div>

    <!-- Articles Grid -->
    <?php if (empty($articles)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">💎</div>
            <p>Không tìm thấy bài viết nào phù hợp với tìm kiếm của bạn.</p>
        </div>
    <?php else: ?>
        <div class="articles-grid">
            <?php foreach ($articles as $index => $article): ?>
                <article class="article-card" style="animation-delay: <?= $index * 0.05 ?>s">
                    <a href="<?= URLROOT ?>/News/detail/<?= $article->articleId ?>" style="text-decoration: none; color: inherit;">
                        <div class="card-image">
                            <?php if (!empty($article->thumbnail)): ?>
                                <img src="<?= URLROOT . '/' . htmlspecialchars($article->thumbnail) ?>"
                                     alt="<?= htmlspecialchars($article->title) ?>">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <span style="font-size: 2rem;">💎</span>
                                </div>
                            <?php endif; ?>
                            <div class="category-tag">
                                <?= htmlspecialchars($article->category_name ?? 'Tin tức') ?>
                            </div>
                        </div>
                        
                        <div class="card-content">
                            <div class="card-meta">
                                <span>📅 <?= date('d/m/Y', strtotime($article->published_at ?? $article->created_at ?? 'now')) ?></span>
                                <span>✍️ <?= htmlspecialchars($article->author_name ?? 'Aurelia') ?></span>
                            </div>
                            
                            <h2 class="card-title"><?= htmlspecialchars($article->title) ?></h2>
                            
                            <div class="card-excerpt">
                                <?php 
                                    $decodedContent = html_entity_decode($article->content, ENT_QUOTES, 'UTF-8');
                                    $contentWithoutStyles = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $decodedContent);
                                    echo mb_substr(strip_tags($contentWithoutStyles), 0, 120) . '...';
                                ?>
                            </div>
                            
                            <div class="read-more">
                                Đọc tiếp →
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= URLROOT ?>/News/index?page=<?= $i ?><?= !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : '' ?>"
                       class="page-link <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>