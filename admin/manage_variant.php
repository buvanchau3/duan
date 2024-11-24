<?php
include 'config.php';

// Lấy danh sách các biến thể
$sql = "SELECT v.variant_id, p.name AS product_name, v.size, v.color, v.price, v.stock 
        FROM product_variants v 
        JOIN products p ON v.product_id = p.product_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Thêm Biến Thể</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="sidebar">
        <h2 class="text-center">Welcome admin</h2>
        <ul class="list-group">
            <li class="list-group-item"><a href="index.php">Trang chủ</a></li>
            <li class="list-group-item"><a href="manage_products.php">Quản lý sản phẩm</a></li>
            <li class="list-group-item"><a href="manage_orders.php">Quản lý đơn hàng</a></li>
            <li class="list-group-item"><a href="manage_users.php">Quản lý người dùng</a></li>
            <li class="list-group-item"><a href="manage_categories.php">Quản lý danh mục</a></li>
            <li class="list-group-item"><a href="manage_coupons.php">Mã giảm giá</a></li>
            <li class="list-group-item"><a href="add_variant.php">Biến Thể</a></li>
        </ul>
    </div>
    <div class="container">
       <div class="row">
        <div class="col-md-12">
        <h2>Quản lý biến thể</h2>
                <table class="custom-table">
                        <thead>
                                <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Size</th>
                                        <th>Màu</th>
                                        <th>Giá</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                </tr>
                        </thead>
                        <tbody>
                        <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                <td><?= htmlspecialchars($row['size']) ?></td>
                                <td><?= htmlspecialchars($row['color']) ?></td>
                                <td><?= htmlspecialchars($row['price']) ?></td>
                                <td><?= htmlspecialchars($row['stock']) ?></td>
                                <td>
                                        <a href="edit_variant.php?variant_id=<?= $row['variant_id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                        <a href="delete_variant.php?variant_id=<?= $row['variant_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</a>
                                </td>
                                </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                                <td colspan="6">Không có biến thể nào.</td>
                        </tr>
                        <?php endif; ?>
                </tbody>
                </table><br>
                <a href="add_variant.php" class="btn btn-primary">Thêm Biến Thể Mới</a>
        </div>
        </div>

</body>
</html>
