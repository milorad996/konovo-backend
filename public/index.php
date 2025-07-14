<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../src/Controller/ProductController.php';
require_once __DIR__ . '/../src/Controller/AuthController.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/login' && $method === 'POST') {
    (new AuthController())->login();
} elseif (preg_match('#^/products/?$#', $uri) && $method === 'GET') {
    (new ProductController())->list();
} elseif (preg_match('#^/products/(\d+)/?$#', $uri, $matches) && $method === 'GET') {
    (new ProductController())->getById((int)$matches[1]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
