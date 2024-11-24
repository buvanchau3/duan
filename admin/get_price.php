<?php
include_once 'config.php'; // Kết nối với cơ sở dữ liệu

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Truy vấn giá sản phẩm
    $stmt = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_price);
    $stmt->fetch();
    $stmt->close();

    // Trả về giá sản phẩm dưới dạng JSON
    echo json_encode(['price' => $product_price]);
}
?>
