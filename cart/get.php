<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit();
}

// Get user_id from URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'User ID required']);
    exit();
}

$user_id = intval($_GET['user_id']);

$db = new Database();
$conn = $db->connect();

// Get all cart items for user with product details
$query = "
    SELECT 
        c.id,
        c.user_id,
        c.product_id,
        c.quantity,
        c.created_at,
        p.name,
        p.price,
        p.img
    FROM cart c
    JOIN products p ON c.product_id = p.product_id COLLATE utf8mb4_unicode_ci
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = [
        'cart_id' => $row['id'],
        'product_id' => $row['product_id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'img' => $row['img'],
        'total_price' => $row['price'] * $row['quantity']
    ];
}

$stmt->close();
$conn->close();

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'data' => $items,
    'count' => count($items),
    'total' => array_sum(array_column($items, 'total_price'))
]);
?>
