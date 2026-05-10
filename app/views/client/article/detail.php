<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article->title) ?> - AURELIA</title>
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

        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .article-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding: 2.5rem 2rem;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 24px;
            color: #f5e6b8;
            position: relative;
            overflow: hidden;
        }

        .article-header::before {
            content: '✦';
            position: absolute;
            font-size: 200px;
            bottom: -40px;
            right: -30px;
            opacity: 0.05;
            font-family: serif;
        }

        .category-badge {
            display: inline-block;
            background: rgba(218, 165, 32, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1rem;
            border: 1px solid rgba(218, 165, 32, 0.3);
            color: #d9b461;
        }

        .article-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .article-meta {
            display: flex;
            justify-content: center;
            gap: 2rem;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meta-icon {
            width: 14px;
            height: 14px;
            stroke: #d9b461;
            stroke-width: 1.5;
            fill: none;
        }

        .thumbnail-wrapper {
            margin-bottom: 2.5rem;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: #e8e2d4;
            aspect-ratio: 16/9;
        }

        .thumbnail-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .thumbnail-wrapper:hover img {
            transform: scale(1.02);
        }

        .article-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(218, 165, 32, 0.1);
        }

        .article-content h2 {
            font-size: 1.6rem;
            margin: 1.5rem 0 0.8rem;
            color: #1e1b1a;
            border-left: 3px solid #d9b461;
            padding-left: 1rem;
        }

        .article-content h3 {
            font-size: 1.2rem;
            margin: 1rem 0 0.5rem;
            color: #2d3748;
        }

        .article-content p {
            margin-bottom: 1rem;
            line-height: 1.7;
            color: #4a5568;
        }

        .article-content img {
            max-width: 100%;
            border-radius: 16px;
            margin: 1.5rem 0;
        }

        .comments-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(218, 165, 32, 0.1);
        }

        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0e6d3;
        }

        .comments-header h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e1b1a;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comment-icon {
            width: 22px;
            height: 22px;
            stroke: #d9b461;
            stroke-width: 1.5;
            fill: none;
        }

        .comments-count {
            background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%);
            color: white;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .comment-form {
            background: #f8f6f2;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            border: 1px solid rgba(218, 165, 32, 0.15);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .comment-form input,
        .comment-form select,
        .comment-form textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #e8e2d4;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
        }

        .comment-form input:focus,
        .comment-form select:focus,
        .comment-form textarea:focus {
            outline: none;
            border-color: #d9b461;
            box-shadow: 0 0 0 3px rgba(218, 180, 97, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(185, 133, 45, 0.3);
        }

        .submit-icon {
            width: 16px;
            height: 16px;
            stroke: white;
            stroke-width: 2;
            fill: none;
        }

        .comment-card {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #f0e6d3;
            transition: background 0.3s ease;
        }

        .comment-card:hover {
            background: #f8f6f2;
            border-radius: 12px;
        }

        .avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #d9b461 0%, #b5852d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
        }

        .comment-author {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 0.4rem;
        }

        .author-name {
            font-weight: 700;
            color: #1e1b1a;
        }

        .comment-date {
            font-size: 0.7rem;
            color: #aaa;
        }

        .comment-text {
            color: #555;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .detail-container {
                padding: 1rem;
            }
            
            .article-header h1 {
                font-size: 1.5rem;
            }
            
            .article-header {
                padding: 1.5rem;
            }
            
            .article-meta {
                flex-direction: column;
                gap: 0.5rem;
                align-items: center;
            }
            
            .article-content {
                padding: 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="detail-container">
    <div class="article-header">
        <span class="category-badge"><?= htmlspecialchars($article->category_name) ?></span>
        <h1><?= htmlspecialchars($article->title) ?></h1>
        <div class="article-meta">
            <span class="meta-item">
                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="12" height="12">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Bởi <?= htmlspecialchars($article->author_name) ?>
            </span>
            <span class="meta-item">
                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="12" height="12">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <?= date('d/m/Y', strtotime($article->published_at)) ?>
            </span>
            <span class="meta-item">
                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="12" height="12">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
                5 phút đọc
            </span>
        </div>
    </div>

    <?php if (!empty($article->thumbnail)): ?>
        <div class="thumbnail-wrapper">
            <img src="<?= URLROOT . '/' . htmlspecialchars($article->thumbnail) ?>" alt="<?= htmlspecialchars($article->title) ?>">
        </div>
    <?php endif; ?>

    <div class="article-content">
        <?php 
            $decodedContent = html_entity_decode($article->content, ENT_QUOTES, 'UTF-8');
            $contentWithoutStyles = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $decodedContent);
            echo $contentWithoutStyles;
        ?>
    </div>

    <style>
        .related-articles { margin-bottom: 3rem; }
        .related-articles h3 { font-size: 1.5rem; margin-bottom: 1.5rem; color: #1e1b1a; border-left: 3px solid #d9b461; padding-left: 1rem; }
        .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
        .related-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease; border: 1px solid rgba(218,165,32,0.1); }
        .related-card:hover { transform: translateY(-5px); }
        .related-img { width: 100%; height: 200px; object-fit: cover; }
        .related-content { padding: 1rem; }
        .related-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; color: #1e1b1a; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .alert-success { background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f5c6cb; }
    </style>

    <?php if (!empty($relatedArticles)): ?>
    <div class="related-articles">
        <h3>Bài viết liên quan</h3>
        <div class="related-grid">
            <?php foreach ($relatedArticles as $rel): ?>
                <a href="<?= URLROOT ?>/News/detail/<?= $rel->articleId ?>" style="text-decoration: none;">
                    <div class="related-card">
                        <?php if (!empty($rel->thumbnail)): ?>
                            <img src="<?= URLROOT . '/' . htmlspecialchars($rel->thumbnail) ?>" class="related-img" alt="<?= htmlspecialchars($rel->title) ?>">
                        <?php endif; ?>
                        <div class="related-content">
                            <h4 class="related-title"><?= htmlspecialchars($rel->title) ?></h4>
                            <span style="font-size: 0.8rem; color: #888;"><?= date('d/m/Y', strtotime($rel->published_at)) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="comments-section">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success">Cảm ơn bạn đã bình luận! Phản hồi của bạn đang chờ quản trị viên phê duyệt trước khi hiển thị.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'length'): ?>
                <div class="alert-error">Bình luận quá ngắn. Vui lòng nhập ít nhất 5 ký tự.</div>
            <?php else: ?>
                <div class="alert-error">Có lỗi xảy ra, vui lòng thử lại.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="comments-header">
            <h3>
                <svg class="comment-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                Phản hồi & Bình luận
            </h3>
            <span class="comments-count"><?= count($comments) ?> bình luận</span>
        </div>

        <form action="<?= URLROOT ?>/News/postComment" method="POST" class="comment-form">
            <input type="hidden" name="articleId" value="<?= $article->articleId ?>">
            <input type="hidden" name="contentId" value="<?= $article->contentId ?>">
            
            <div class="form-row">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div style="position: relative;">
                        <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; stroke: #b5852d; stroke-width: 1.5; fill: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" name="guest_name" required placeholder="Tên của bạn" style="padding-left: 2.5rem;">
                    </div>
                <?php endif; ?>
                <select name="rating">
                    <option value="5">★★★★★ Tuyệt vời</option>
                    <option value="4">★★★★☆ Rất tốt</option>
                    <option value="3">★★★☆☆ Bình thường</option>
                </select>
            </div>
            
            <div style="position: relative; margin-bottom: 1rem;">
                <svg style="position: absolute; left: 12px; top: 14px; width: 16px; height: 16px; stroke: #b5852d; stroke-width: 1.5; fill: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <textarea name="content" rows="4" required placeholder="Viết bình luận của bạn..." style="padding-left: 2.5rem;"></textarea>
            </div>
            
            <button type="submit" class="submit-btn">
                <svg class="submit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                Gửi bình luận
            </button>
        </form>

        <div class="comments-list">
            <?php foreach ($comments as $comment): ?>
                <div class="comment-card">
                    <div class="avatar">
                        <?= strtoupper(substr($comment->member_name ?? $comment->guest_name, 0, 1)) ?>
                    </div>
                    <div class="comment-content">
                        <div class="comment-author">
                            <span class="author-name"><?= htmlspecialchars($comment->member_name ?? $comment->guest_name) ?></span>
                            <span class="comment-date"><?= date('d/m/Y', strtotime($comment->created_at)) ?></span>
                        </div>
                        <p class="comment-text"><?= nl2br(htmlspecialchars($comment->content)) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>