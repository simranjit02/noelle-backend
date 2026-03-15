<?php
// CORS Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/Database.php';

$db = new Database();
$conn = $db->connect();

// Get query parameters
$category = isset($_GET['category']) ? trim($conn->real_escape_string($_GET['category'])) : null;
$popular = isset($_GET['popular']) ? (int)$_GET['popular'] : null;
$is_new = isset($_GET['new']) ? (int)$_GET['new'] : null;
$product_id = isset($_GET['id']) ? trim($conn->real_escape_string($_GET['id'])) : null;  // Keep as string - product_id is VARCHAR
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

// Build query
$query = "SELECT * FROM products WHERE 1=1";

if ($category) {
    $query .= " AND TRIM(category) = '$category'";
}
if ($popular !== null) {
    $query .= " AND popular = $popular";
}
if ($is_new !== null) {
    $query .= " AND is_new = $is_new";
}
if ($product_id) {
    $query .= " AND TRIM(product_id) = '$product_id'";  // Add quotes since product_id is VARCHAR
}

$query .= " LIMIT $limit";

$result = $conn->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => $conn->error]);
    exit;
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'id' => trim($row['product_id']),
        'img' => $row['img'],
        'img2' => $row['img2'],
        'name' => $row['name'],
        'price' => '$' . $row['price'],
        'des' => $row['description'],
        'code' => $row['sku'],
        'category' => trim($row['category']),
        'popular' => $row['popular'] ? 'true' : 'false',
        'newProducts' => $row['is_new'] ? 'new' : ''
    ];
}

echo json_encode($products);
$conn->close();
?>
