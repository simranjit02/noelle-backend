<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (empty($data['user_id']) || empty($data['product_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'User ID and Product ID required']);
    exit();
}

$user_id = intval($data['user_id']); // Convert to integer
$product_id = strval($data['product_id']); // Keep as string - product_id is VARCHAR

$db = new Database();
$conn = $db->connect();

// Delete item from cart
$delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
$delete_stmt = $conn->prepare($delete_query);

if (!$delete_stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$delete_stmt->bind_param("is", $user_id, $product_id);

if ($delete_stmt->execute()) {
    if ($delete_stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Product removed from cart'
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Product not found in cart'
        ]);
    }
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to remove from cart']);
}

$delete_stmt->close();
$conn->close();
?>
