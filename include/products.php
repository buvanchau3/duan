<?php
// session_start(); // Khởi động session
include "../admin/config.php";  // Kết nối database



// Lấy danh sách sản phẩm từ database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-8">Sản Phẩm</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        // Kiểm tra xem có sản phẩm nào không
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <!-- Product Card -->
                <div class="bg-white rounded-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <a href="details.php?product_id=<?php echo $row['product_id']; ?>">
                            <img class="w-full h-64 object-cover transition-transform duration-300 hover:scale-105" src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>">
                        </a>
                        <!-- Nút Yêu thích -->
                        <button class="absolute top-2 right-2 bg-white p-2 rounded-full hover:bg-gray-100 group transition duration-200">
                            <svg class="w-5 h-5 text-red-500 group-hover:hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                            <span class="hidden group-hover:inline-block text-red-500 text-sm font-semibold">Yêu thích</span>
                        </button>
                        <!-- Hiển thị số lượng -->
                        <div class="absolute bottom-2 left-2">
                            <span style="color: #ffffff;">Số lượng còn lại:</span>
                            <span class="font-bold <?php echo ($row["quantity"] > 10) ? 'text-green-500' : (($row["quantity"] > 0) ? 'text-yellow-500' : 'text-red-500'); ?>">
                                <?php echo $row["quantity"]; ?>
                            </span>
                            <?php if ($row["quantity"] > 10): ?>
                                <svg class="w-4 h-4 inline text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l-5-5 1.41-1.41L10 12.17l7.59-7.59L19 5z" /></svg>
                            <?php elseif ($row["quantity"] > 0): ?>
                                <svg class="w-4 h-4 inline text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l-5-5 1.41-1.41L10 12.17l7.59-7.59L19 5z" /></svg>
                            <?php else: ?>
                                <span class="text-red-500">Hết hàng</span>
                                <svg class="w-4 h-4 inline text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10.59l-5.29-5.3L5.3 5.3 12 12l6.7-6.7 1.41 1.41z" /></svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo $row["name"]; ?></h3>
                       
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-lg font-bold text-blue-500"><?php echo number_format($row["price"], 0, ',', '.') . 'đ'; ?></span>
                        </div>
                        <div class="mt-3 flex space-x-2">
                        <form action="cart_page.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>"> 
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button class="btn btn-success" type="submit">Thêm vào Giỏ</button>
                        </form>
                        <form action="../html/details.php" method="GET">
                        <!-- Gửi thông tin sản phẩm đến trang chi tiết -->
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <button class="flex items-center bg-blue-500 text-white px-2 py-1 rounded-lg hover:bg-blue-600 transition-colors duration-200" type="submit">
                            Mua Ngay
                        </button>
                    </form>
                        </div>
                    </div>
                </div>

        <?php
            }
        } else {
            echo "<p class='col-span-full text-center'>Không có sản phẩm nào</p>";
        }
        ?>
    </div>
</div>


</body>

</html>