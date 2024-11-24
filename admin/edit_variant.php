<?php
include 'config.php';

if (isset($_GET['variant_id'])) {
    $variant_id = $_GET['variant_id'];

    // Lấy thông tin biến thể từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM product_variants WHERE variant_id = ?");
    $stmt->bind_param("i", $variant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $variant = $result->fetch_assoc();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Cập nhật biến thể
        $size = $_POST['size'];
        $color = $_POST['color'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $stmt = $conn->prepare("UPDATE variants SET size = ?, color = ?, price = ?, stock = ? WHERE variant_id = ?");
        $stmt->bind_param("ssdis", $size, $color, $price, $stock, $variant_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Biến thể đã được cập nhật thành công!'); window.location.href = 'manage_variant.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Chỉnh sửa Biến Thể</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Chỉnh Sửa Biến Thể</h2>
        <form action="edit_variant.php?variant_id=<?= $variant['variant_id'] ?>" method="POST">
            <div class="mb-3">
                <label for="size" class="form-label">Kích thước:</label>
                <input type="text" class="form-control" name="size" value="<?= htmlspecialchars($variant['size']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Màu sắc:</label>
                <input type="text" class="form-control" name="color" value="<?= htmlspecialchars($variant['color']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Giá:</label>
                <input type="number" class="form-control" name="price" value="<?= htmlspecialchars($variant['price']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Số lượng tồn kho:</label>
                <input type="number" class="form-control" name="stock" value="<?= htmlspecialchars($variant['stock']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật Biến Thể</button>
        </form>
    </div>
</body>
</html>
