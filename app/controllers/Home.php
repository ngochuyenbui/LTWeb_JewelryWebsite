<?php
class Home extends Controller
{
    public function index()
    {
        $siteContentModel = $this->model('SiteContentModel');
        $productModel = $this->model('ProductModel');
        $managerId = (($_SESSION['user_role'] ?? null) === ROLE_ADMIN) ? ($_SESSION['user_id'] ?? null) : null;

        $siteContentModel->ensureDefaults($managerId);

        $this->view('client/Home', [
            'content' => $siteContentModel->getContentMap(),
            'images' => $siteContentModel->getImageMap(),
            'featuredProducts' => $productModel->getFeaturedProducts(4),
            'homeCategories' => $productModel->getHomeCategories(4),
        ]);
    }
}
