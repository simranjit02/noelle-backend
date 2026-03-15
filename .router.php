<?php
/**
 * NOELLE BACKEND ROUTER - Single Source of Truth
 * START WITH: php -S localhost:5000 .router.php
 */

// ============ CORS - Allow Everything ============
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, HEAD');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Access-Control-Allow-Origin');
header('Content-Type: application/json');

// Handle preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// ============ ROUTING ============
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// API REQUEST
if (strpos($uri, '/api/') === 0) {
    $file = __DIR__ . $uri;
    
    // File exists and is a real file
    if (is_file($file)) {
        require $file;
        exit(0);
    }
    
    // API file not found
    http_response_code(404);
    echo json_encode(['error' => 'API endpoint not found', 'requested' => $uri]);
    exit(1);
}

// CART REQUEST
if (strpos($uri, '/cart/') === 0) {
    $file = __DIR__ . $uri;
    
    // File exists and is a real file
    if (is_file($file)) {
        require $file;
        exit(0);
    }
    
    // Cart file not found
    http_response_code(404);
    echo json_encode(['error' => 'Cart endpoint not found', 'requested' => $uri]);
    exit(1);
}

// AUTH REQUEST
if (strpos($uri, '/auth/') === 0) {
    $file = __DIR__ . $uri;
    
    // File exists and is a real file
    if (is_file($file)) {
        require $file;
        exit(0);
    }
    
    // Auth file not found
    http_response_code(404);
    echo json_encode(['error' => 'Auth endpoint not found', 'requested' => $uri]);
    exit(1);
}

// ROOT / HOME
if ($uri === '/' || $uri === '') {
    require __DIR__ . '/public/index.php';
    exit(0);
}

// PUBLIC STATIC FILES
if (strpos($uri, '/public/') === 0) {
    $file = __DIR__ . $uri;
    if (is_file($file)) {
        return false; // Let PHP serve static files
    }
}

// NOT FOUND
http_response_code(404);
echo json_encode(['error' => 'Not found', 'path' => $uri]);
exit(1);
?>
