<?php
include '../admin/config.php';

$product_id = $_GET['product_id'];

$sql = "SELECT * FROM products WHERE product_id = ?";

if($_SERVER['REQUET_METHOD'] == 'POST') {

    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
     // Tính tổng tiền
     $total = $product['price'] * $quantity;
    
     // Hiển thị kết quả
     echo 'Tổng tiền: ' . number_format($total, 0, ',', '.') . ' ₫';
}
?>