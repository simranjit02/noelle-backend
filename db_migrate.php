<?php
require_once __DIR__ . '/config/Database.php';

$json = file_get_contents(__DIR__ . '/../noelle-main/public/api.json');
$data = json_decode($json, true);

$db = new Database();
$conn = $db->connect();

$migrated = 0;
$errors = 0;

foreach ($data as $product) {
    if (!is_array($product) || empty($product['id'])) {
        continue;
    }

    $product_id = $conn->real_escape_string($product['id']);
    $img = $conn->real_escape_string($product['img']);
    $img2 = $conn->real_escape_string($product['img2']);
    $name = $conn->real_escape_string($product['name']);
    $price = (float)$product['Price'];
    $description = $conn->real_escape_string($product['des']);
    $sku = $conn->real_escape_string($product['code']);
    $category = $conn->real_escape_string($product['category']);
    $popular = ($product['popular'] === 'true') ? 1 : 0;
    $is_new = (isset($product['newProducts']) && $product['newProducts'] === 'new') ? 1 : 0;

    $query = "INSERT INTO products 
              (product_id, img, img2, name, price, description, sku, category, popular, is_new) 
              VALUES 
              ('$product_id', '$img', '$img2', '$name', $price, '$description', '$sku', '$category', $popular, $is_new)
              ON DUPLICATE KEY UPDATE
              img='$img', img2='$img2', name='$name', price=$price, description='$description', 
              sku='$sku', category='$category', popular=$popular, is_new=$is_new";

    if ($conn->query($query)) {
        $migrated++;
    } else {
        $errors++;
        echo "Error: " . $conn->error . "\n";
    }
}

echo "Migration complete!\n";
echo "Migrated: $migrated\n";
echo "Errors: $errors\n";

$conn->close();
?>
