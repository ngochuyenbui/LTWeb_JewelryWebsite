<div class="w-full relative px-12 md:px-16">
    <!-- Owl Carousel Container -->
    <div class="owl-carousel owl-theme category-carousel pb-6 pt-2">
        <!-- Mục: Tất cả -->     
        <?php foreach ($data['categories'] ?? [] as $category): ?>
            <?php
            $c_hidden = is_object($category) ? ($category->is_hidden ?? 0) : ($category['is_hidden'] ?? 0);
            if ($c_hidden == 1) continue;

            $c_id = is_object($category) ? ($category->cateId ?? '') : ($category['cateId'] ?? '');
            $c_name = is_object($category) ? ($category->name ?? '') : ($category['name'] ?? '');
            $c_image = is_object($category) ? ($category->image_url ?? '') : ($category['image_url'] ?? '');
            if (empty($c_image)) {
                $c_image = "https://placehold.co/600x450/f8fafc/64748b?text=" . urlencode($c_name);
            } else {
                $c_image = strpos($c_image, 'http') === 0 ? $c_image : URLROOT . $c_image;
            }
            $isActive = isset($_GET['category']) && $_GET['category'] == $c_id;
            ?>
            <div class="item">
                <a href="?category=<?= $c_id ?>" data-category="<?= $c_id ?>" class="category-link flex flex-col items-center text-center group cursor-pointer h-full">
                    <div class="category-border w-full aspect-[4/3] overflow-hidden mb-3 border-2 transition-all duration-300 <?= $isActive ? 'border-amber-500 shadow-md' : 'border-transparent group-hover:border-amber-300' ?>">
                        <img src="<?= htmlspecialchars($c_image) ?>" alt="<?= htmlspecialchars($c_name) ?>" class="object-none w-full h-full group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    </div>
                    <span class="w-full font-serif font-bold text-3xl uppercase tracking-wide transition-colors text-slate-700 group-hover:text-amber-600">
                        <?= htmlspecialchars($c_name) ?>
                    </span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.category-carousel').owlCarousel({
        loop: true,
        margin: 16, 
        nav: true, 
        dots: false, 
        navText: [
            '<div class="w-10 h-10   flex items-center justify-center text-slate-600 hover:text-amber-600  bg-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></div>',
            '<div class="w-10 h-10   flex items-center justify-center text-slate-600 hover:text-amber-600  bg-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></div>'
        ],
        responsive: {
            0: { items: 1 },
            576: { items: 2},
            1024: { items: 3 }
        }
    });
});
</script>

<style>
.category-carousel .owl-nav {
    position: absolute;
    top: 40%; 
    transform: translateY(-50%);
    width: 100%;
    left: 0;
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    margin-top: 0 !important;
}
.category-carousel .owl-nav button {
    pointer-events: auto;
    background: transparent !important;
}
@media (min-width: 768px) {
    .category-carousel .owl-nav {
        width: calc(100% + 6rem); 
        left: -3rem;
    }
}

/* Đảm bảo các Danh mục trong Carousel có chiều cao bằng nhau */
.category-carousel .owl-stage {
    display: flex;
}
.category-carousel .owl-item {
    display: flex;
    flex: 1 0 auto;
}
.category-carousel .item {
    display: flex;
    flex-direction: column;
    width: 100%;
}
</style>