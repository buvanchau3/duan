<?php
include '../admin/config.php';

// Lấy các tham số từ AJAX
$product_id = $_GET['product_id'];
$color = $_GET['color'];
$size = $_GET['size'];

// Lấy giá của biến thể dựa trên màu sắc và kích thước
$sql = "SELECT price FROM product_variants WHERE product_id = ? AND color = ? AND size = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $product_id, $color, $size);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra xem có kết quả không
if ($result->num_rows > 0) {
    $variant = $result->fetch_assoc();
    $price = $variant['price'];

    // Trả về giá dưới dạng JSON
    echo json_encode(['success' => true, 'price' => $price]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
?>
