<?php
include '../admin/config.php';
// include 'process_order.php';

$product_id = $_GET['product_id'];

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

$sql = "SELECT DISTINCT color, size FROM product_variants WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$variants_result = $stmt->get_result();

// Lấy các biến thể từ bảng variants, sắp xếp theo giá tăng dần
// $variants_stmt = $conn->prepare("SELECT v.variant_id, v.product_id, v.size, v.color, v.price, v.stock
//                                  FROM product_variants v
//                                  WHERE v.product_id = ?
//                                  ORDER BY v.price ASC");
// $variants_stmt->bind_param("i", $product_id);  // $product_id là ID của sản phẩm bạn muốn lấy biến thể
// $variants_stmt->execute();
// $variants_result = $variants_stmt->get_result();


// Mảng lưu các biến thể với giá và số lượng
$variants = [];
while ($variant = $variants_result->fetch_assoc()) {
    $variants[] = $variant;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        .image-container {
            display: flex;                /* Sử dụng Flexbox */
            flex-direction: column;       /* Sắp xếp hình ảnh theo chiều dọc */
            align-items: center;          /* Canh giữa các hình ảnh theo chiều ngang */
            gap: 10px;                    /* Khoảng cách giữa các hình ảnh */
        }

        .image-item {
            width: 100px;                 /* Đặt chiều rộng của ảnh */
            height: 100px;                /* Đặt chiều cao của ảnh */
            object-fit: cover;            /* Đảm bảo ảnh không bị méo và sẽ được cắt cho vừa khung */
        }

    </style>
</head>

<body class="bg-gray-100">
    <?php include '../include/navbar.php'; ?>
    
    <!-- Hiển thị thông tin sản phẩm -->
    <div class="product-details container">
    <div class="row" style="padding: 20px;">
        
        <!-- Phần ảnh sản phẩm -->
        <div class="col-md-5 product-image">
            <!-- Ảnh chính -->
            <img style="height: 550px; width: 100%; object-fit: cover; margin-bottom: 20px;" 
                 src="<?php echo $product['image_path']; ?>" 
                 alt="Product Image" 
                 class="img-fluid">
        </div>
        
        <!-- Phần ảnh phụ -->
        <div class="col-md-2 image-container">  
                <img src="http://localhost/duan/PHP-1/uploads/be-trai-2.png" alt="ảnh phụ" class="image-item">
                <img src="http://localhost/duan/PHP-1/uploads/be-trai-2.png" alt="ảnh phụ" class="image-item">
                <img src="http://localhost/duan/PHP-1/uploads/be-trai-2.png" alt="ảnh phụ" class="image-item">
                <img src="http://localhost/duan/PHP-1/uploads/be-trai-2.png" alt="ảnh phụ" class="image-item">
                <img src="http://localhost/duan/PHP-1/uploads/be-trai-2.png" alt="ảnh phụ" class="image-item">  
        </div>

        <!-- Phần thông tin sản phẩm -->
        <div class="col-md-5 product-info">
            <h6 style="font-size: 20px; margin-bottom: 44px;font-weight: 300; "><?php echo $product['name']; ?></h6>
            <h2 class="h6">Giá: <span class="price"><?php echo number_format($product['price'], 0, ',', '.') . ' ₫'; ?></span></h2>
                    <!-- Số lượng với nút tăng/giảm -->
            <div class="form-group">
                <label for="quantity" class="h6">Số lượng</label>
                <div class="input-group">
                    <button type="button" class="btn btn-secondary" id="decrease" onclick="updateQuantity(-1)">-</button>
                    <input style="text-align: center;" type="number" name="quantity" id="quantity" value="1" min="1" max="99">
                    <button type="button" class="btn btn-secondary" id="increase" onclick="updateQuantity(1)">+</button>
                </div>
            </div>
                    
            <img src="https://media.canifa.com/attribute/swatch/f/r/freeship_tagdetail_desktop-02oct_1.webp" alt="" style="max-width: 100%; height: auto;">
            
            <div class="form-group">
                <label for="color" class="h6">Chọn màu</label>
                <select id="color" name="color" class="form-control" style="margin: 10px 0;">
                    <?php
                    // Mảng lưu các màu sắc để tránh trùng lặp
                    $colors = []; 
                    $variants_result->data_seek(0); 
                    while ($variant = $variants_result->fetch_assoc()) {
                        if (!in_array($variant['color'], $colors)) { 
                            $colors[] = $variant['color'];
                            echo "<option value='{$variant['color']}'>{$variant['color']}</option>";
                        }
                    }
                    ?>
                </select>

                <label for="size" class="h6">Chọn Size</label>
                <select id="size" name="size" class="form-control" style="margin: 10px 0;">
                    <?php
                    // Mảng lưu các kích thước để tránh trùng lặp
                    $sizes = []; 
                    $variants_result->data_seek(0); // Reset kết quả về đầu
                    while ($variant = $variants_result->fetch_assoc()) {
                        if (!in_array($variant['size'], $sizes)) { 
                            $sizes[] = $variant['size'];
                            echo "<option value='{$variant['size']}'>{$variant['size']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <h6 id="total-price">Tổng tiền: <span id="total"><?php echo number_format($product['price'], 0, ',', '.') . ' ₫'; ?></span></h6>

       <div class="form-group" style="display: flex;">
          <!-- Form cho "Mua hàng" -->
          <form method="POST" style="width: 100%;  margin-right: 5px;" action="checkout.php">
            <!-- Thông tin sản phẩm -->
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
            <input type="hidden" name="quantity" id="quantity-form" value="1"> <!-- Số lượng sản phẩm -->

            <!-- Nút "Mua hàng" -->
            <button type="submit" name="action" value="buy" class="btn btn-danger" style=" width: 100%;  padding: 8px;">Mua hàng</button>
        </form>

        <!-- Form cho "Thêm vào giỏ hàng" -->
        <form method="POST" style="width: 100%;"  action="cart_page.php">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
            <input type="hidden" name="quantity" id="quantity-form" value="1">

            <button type="submit" name="action" class="btn btn-outline-light" style=" width: 100%; padding: 8px; color: #000; border: 1px solid #000;">Thêm vào giỏ hàng</button>
        </form>
       </div>

            
            <hr>
            
            <!-- Mô tả sản phẩm -->
            <div class="form-group">
                <h4>Mô tả</h4>
                <p class="lead"><?php echo $product['description']; ?></p>
            </div>
        </div>
    </div>
</div>
<hr/>
<div class="container mx-auto p-6">
    <h3 class="text-3xl font-bold text-center mb-8">Gợi ý mua hàng</h3>
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



    <?php include '../include/footer.php'; ?>

</body>

<script>
   // Lắng nghe sự kiện thay đổi chọn màu và kích thước
document.getElementById('color').addEventListener('change', updateTotalPrice);
document.getElementById('size').addEventListener('change', updateTotalPrice);

// Lắng nghe sự kiện thay đổi số lượng
document.getElementById('quantity').addEventListener('change', updateTotalPrice);

// Hàm tăng giảm số lượng
function updateQuantity(change) {
    var quantityInput = document.getElementById('quantity');
    var newQuantity = parseInt(quantityInput.value) + change;
    if (newQuantity >= 1 && newQuantity <= 99) {
        quantityInput.value = newQuantity;
        updateTotalPrice(); // Cập nhật lại tổng tiền
    }
}

// Hàm tính toán lại tổng tiền
function updateTotalPrice() {
    var quantity = parseInt(document.getElementById('quantity').value);
    var selectedColor = document.getElementById('color').value;
    var selectedSize = document.getElementById('size').value;

    // Kiểm tra xem người dùng đã chọn cả màu và kích thước chưa
    if (selectedColor && selectedSize) {
        // Gửi yêu cầu AJAX để lấy giá của biến thể từ server
        fetch(`get_variant_price.php?product_id=<?php echo $product_id; ?>&color=${selectedColor}&size=${selectedSize}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var price = data.price;
                    var total = price * quantity;
                    document.getElementById('total').innerText = total.toLocaleString() + ' ₫'; // Cập nhật tổng tiền
                } else {
                    document.getElementById('total').innerText = 'Không tìm thấy biến thể';
                }
            })
            .catch(error => {
                console.log('Lỗi: ', error);
            });
    }
}


    // Lắng nghe sự kiện thay đổi chọn màu và kích thước
document.getElementById('color').addEventListener('change', updatePrice);
document.getElementById('size').addEventListener('change', updatePrice);

function updatePrice() {
    const selectedColor = document.getElementById('color').value;
    const selectedSize = document.getElementById('size').value;
    
    if (selectedColor && selectedSize) {
        // Gửi yêu cầu AJAX để lấy giá của biến thể từ server
        fetch(`get_variant_price.php?product_id=<?php echo $product_id; ?>&color=${selectedColor}&size=${selectedSize}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật giá hiển thị trên trang
                    document.getElementById('total').textContent = numberWithCommas(data.price) + ' ₫';
                } else {
                    document.getElementById('total').textContent = 'Không tìm thấy biến thể';
                }
            })
            .catch(error => {
                console.log('Lỗi: ', error);
            });
    }
}

// Hàm định dạng số với dấu phẩy
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
</script>


</html>
