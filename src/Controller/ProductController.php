<?php

require_once __DIR__ . '/../Service/ExternalApiService.php';
require_once __DIR__ . '/../Service/ProductService.php';
require_once __DIR__ . '/../Utils/Response.php';

class ProductController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }



    public function list()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            Response::json(['error' => 'Unauthorized'], 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $products = $this->productService->getAll($token, $category, $search, $page);

        if (!$products) {
            Response::json(['error' => 'Unauthorized or invalid token'], 401);
        }

        Response::json($products);
    }


    public function getById($id)
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            Response::json(['error' => 'Unauthorized'], 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        $product = $this->productService->getById($token, $id);

        if ($product) {
            Response::json($product);
        } else {
            Response::json(['error' => 'Product not found or unauthorized'], 404);
        }
    }
}
