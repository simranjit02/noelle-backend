<?php
// CORS and routing handled by .router.php - just process the request

require_once __DIR__ . '/../config/Database.php';

$db = new Database();
$conn = $db->connect();

// Get query parameters
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : null;
$popular = isset($_GET['popular']) ? (int)$_GET['popular'] : null;
$is_new = isset($_GET['new']) ? (int)$_GET['new'] : null;
$product_id = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

// Build query
$query = "SELECT * FROM products WHERE 1=1";

if ($category) {
    $query .= " AND category = '$category'";
}
if ($popular !== null) {
    $query .= " AND popular = $popular";
}
if ($is_new !== null) {
    $query .= " AND is_new = $is_new";
}
if ($product_id) {
    $query .= " AND product_id = '$product_id'";
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
        'id' => $row['product_id'],
        'img' => $row['img'],
        'img2' => $row['img2'],
        'name' => $row['name'],
        'price' => '$' . $row['price'],
        'des' => $row['description'],
        'code' => $row['sku'],
        'category' => $row['category'],
        'popular' => $row['popular'] ? 'true' : 'false',
        'newProducts' => $row['is_new'] ? 'new' : ''
    ];
}

echo json_encode($products);
$conn->close();
?>
