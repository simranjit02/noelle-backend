<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

echo json_encode([
    'message' => 'Noelle Backend API',
    'version' => '1.0',
    'endpoints' => [
        'GET /api/products.php' => 'Get all products',
        'GET /api/products.php?category=Lips' => 'Filter by category',
        'GET /api/products.php?id=Lips_1' => 'Get single product',
        'GET /api/products.php?popular=1' => 'Get popular products',
        'GET /api/products.php?new=1' => 'Get new products',
        'GET /api/products.php?limit=6' => 'Limit results'
    ]
]);
?>
