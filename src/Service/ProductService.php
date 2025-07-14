<?php

require_once 'ExternalApiService.php';

class ProductService
{
    private $externalApi;

    public function __construct()
    {
        $this->externalApi = new ExternalApiService();
    }

    public function getAll($token, $category = null, $search = null, $page = 1, $perPage = 16)
    {
        $products = $this->externalApi->getProductsWithToken($token);
        if (!$products) {
            return null;
        }

        $result = [];

        foreach ($products as $product) {
            if ($category && (!isset($product['categoryName']) || strcasecmp(trim($product['categoryName']), trim($category)) !== 0)) {
                continue;
            }

            if ($search && stripos($product['naziv'], $search) === false && stripos($product['description'], $search) === false) {
                continue;
            }

            $result[] = $this->processProduct($product);
        }

        $total = count($result);
        $start = ($page - 1) * $perPage;
        $pagedData = array_slice($result, $start, $perPage);

        return [
            'data' => $pagedData,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    public function getById($token, $id)
    {
        $products = $this->externalApi->getProductsWithToken($token);
        if (!$products) {
            return null;
        }

        foreach ($products as $product) {
            if (isset($product['sku']) && $product['sku'] == $id) {
                return $this->processProduct($product);
            }
        }

        return null;
    }

    private function processProduct($product)
    {
        if (isset($product['category']) && strcasecmp($product['category'], 'Monitori') === 0) {
            $product['price'] *= 1.1;
        }

        if (isset($product['description'])) {
            $product['description'] = preg_replace('/brzina/i', 'performanse', $product['description']);
        }

        return $product;
    }
}
