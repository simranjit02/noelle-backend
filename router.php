<?php
$base_path = __DIR__;
$request_uri = $_SERVER['REQUEST_URI'];

// Remove query string
$path = parse_url($request_uri, PHP_URL_PATH);

// Handle API requests
if (strpos($path, '/api/') === 0) {
    $file = $base_path . $path;
    if (file_exists($file) && is_file($file)) {
        include $file;
        exit;
    }
}

// Handle static files
if (strpos($path, '/public/') === 0) {
    $file = $base_path . $path;
    if (file_exists($file) && is_file($file)) {
        include $file;
        exit;
    }
}

// Default to public/index.php
include $base_path . '/public/index.php';
?>
