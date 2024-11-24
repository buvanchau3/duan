<?php
include_once 'config.php'; // Kết nối với cơ sở dữ liệu

// Biến để lưu giá sản phẩm
$product_price = 0;
$selected_product_id = null;

// Lấy danh sách sản phẩm
$product_result = $conn->query("SELECT product_id, name FROM products");

// Kiểm tra nếu form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_product_id = $_POST['product_id']; // Lấy sản phẩm được chọn
    $size = $_POST['size']; // Lấy kích thước
    $color = $_POST['color']; // Lấy màu sắc
    $price_adjustment = $_POST['price_adjustment']; // Lấy giá biến thể
    $stock = $_POST['stock']; // Lấy số lượng tồn kho

    // Truy vấn giá sản phẩm
    $stmt = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $selected_product_id);
    $stmt->execute();
    $stmt->bind_result($product_price);
    $stmt->fetch();
    $stmt->close();

    // Tính giá biến thể
    $variant_price = $product_price + $price_adjustment;

    // Thêm biến thể vào cơ sở dữ liệu
    $insert_stmt = $conn->prepare("INSERT INTO product_variants (product_id, size, color, price, stock) VALUES (?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("issdi", $selected_product_id, $size, $color, $variant_price, $stock);
    $insert_stmt->execute();
    $insert_stmt->close();

    // Đảm bảo rằng dữ liệu đã được thêm vào thành công
    echo "<script>alert('Biến thể đã được thêm thành công!'); window.location.href = 'add_variant.php';</script>";
}
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
        <div class="col-md-6">
        <h2>Thêm Biến Thể Mới</h2>
        <form action="add_variant.php" method="POST">
            <!-- Dropdown chọn sản phẩm -->
            <div class="mb-3">
                <label for="product_id" class="form-label">Sản phẩm:</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    <option value="">Chọn sản phẩm</option>
                    <?php while ($product = $product_result->fetch_assoc()) { ?>
                        <option value="<?= $product['product_id'] ?>" 
                            <?= $selected_product_id == $product['product_id'] ? 'selected' : '' ?> >
                            <?= $product['name'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Hiển thị giá gốc sản phẩm -->
            <div class="mb-3">
                <label for="base_price" class="form-label">Giá gốc:</label>
                <input class="form-control" type="number" name="base_price" id="base_price" 
                    value="<?= $product_price ?>" readonly>
            </div>

            <!-- Các trường khác -->
            <div class="mb-3">
                <label for="size" class="form-label">Kích thước:</label>
                <select class="form-control" name="size" id="size" required>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Màu sắc:</label>
                <select name="color" id="color" class="form-control" required>
                    <option value="red">Đỏ</option>
                    <option value="blue">Xanh dương</option>
                    <option value="green">Xanh lá</option>
                    <option value="yellow">Vàng</option>
                    <option value="black">Đen</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="price_adjustment" class="form-label">Giá Biến Thể:</label>
                <input type="number" class="form-control" name="price_adjustment" id="price_adjustment" value="0" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Số lượng tồn kho:</label>
                <input class="form-control" type="number" name="stock" id="stock" min="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Thêm Biến Thể</button>
        </form>
        </div>
       </div>
    </div>

    <script>
    // Khi chọn sản phẩm, gửi yêu cầu AJAX lấy giá
    $('#product_id').change(function() {
        var product_id = $(this).val();
        
        if (product_id) {
            $.ajax({
                url: 'get_price.php', // URL đến file xử lý giá sản phẩm
                type: 'POST',
                data: { product_id: product_id },
                success: function(response) {
                    var data = JSON.parse(response);
                    // Cập nhật giá sản phẩm vào ô input
                    if (data.price) {
                        $('#base_price').val(data.price);
                    } else {
                        $('#base_price').val(0); // Nếu không có giá, đặt là 0
                    }
                }
            });
        } else {
            // Nếu không chọn sản phẩm, reset giá về 0
            $('#base_price').val(0);
        }
    });
</script>

</body>
</html>
