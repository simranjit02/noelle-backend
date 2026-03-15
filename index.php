<?php
$base_path = __DIR__;
$request_uri = $_SERVER['REQUEST_URI'];

// Parse URL to get path without query string
$url_parts = parse_url($request_uri);
$path = $url_parts['path'];

// Handle API requests
if (strpos($path, '/api/') === 0) {
    $file = $base_path . $path;
    if (file_exists($file) && is_file($file)) {
        require $file;
        exit;
    }
}

// Handle public requests
if (strpos($path, '/public/') === 0) {
    $file = $base_path . $path;
    if (file_exists($file) && is_file($file)) {
        require $file;
        exit;
    }
}

// Default homepage
if ($path === '/' || $path === '') {
    require $base_path . '/public/index.php';
    exit;
}

// 404
http_response_code(404);
echo json_encode(['error' => 'Not found']);
?>
