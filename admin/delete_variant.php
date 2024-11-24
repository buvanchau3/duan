<?php
include 'config.php';

if (isset($_GET['variant_id'])) {
    $variant_id = $_GET['variant_id'];

    // Xóa biến thể từ cơ sở dữ liệu
    $stmt = $conn->prepare("DELETE FROM variants WHERE variant_id = ?");
    $stmt->bind_param("i", $variant_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Biến thể đã được xoá thành công!'); window.location.href = 'manage_variant.php';</script>";
    exit;
}
