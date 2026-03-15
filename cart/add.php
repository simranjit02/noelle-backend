<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (empty($data['user_id']) || empty($data['product_id']) || empty($data['quantity'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

$user_id = $data['user_id'];
$product_id = $data['product_id'];
$quantity = intval($data['quantity']);

if ($quantity < 1) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Quantity must be at least 1']);
    exit();
}

$db = new Database();
$conn = $db->connect();

// Check if product already exists in cart
$check_query = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
$check_stmt = $conn->prepare($check_query);

if (!$check_stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$check_stmt->bind_param("is", $user_id, $product_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    // Product exists - update quantity
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;
    
    $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $update_stmt = $conn->prepare($update_query);
    
    if (!$update_stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    
    $update_stmt->bind_param("iis", $new_quantity, $user_id, $product_id);
    
    if ($update_stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Product quantity updated in cart'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update cart: ' . $update_stmt->error]);
    }
    
    $update_stmt->close();
} else {
    // Product doesn't exist - insert new record
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    
    if (!$insert_stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    
    $insert_stmt->bind_param("isi", $user_id, $product_id, $quantity);
    
    if ($insert_stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'message' => 'Product added to cart'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to add to cart: ' . $insert_stmt->error]);
    }
    
    $insert_stmt->close();
}

$check_stmt->close();
$conn->close();
?>
