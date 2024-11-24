<?php
// cart.php
// session_start();

include_once '../admin/config.php';
// $cart = new Cart($_SESSION['user_id'], $conn);

class Cart {
    private $user_id;
    private $conn;

    public function __construct($user_id, $conn) {
        $this->user_id = $user_id;
        $this->conn = $conn;
    }

    // Thêm sản phẩm vào giỏ
    public function addItem($product_id, $quantity) {
        global $conn;

        // Kiểm tra xem người dùng đã có giỏ hàng chưa
        $sql = "SELECT cart_id FROM carts WHERE user_id = '$this->user_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cart_id = $row['cart_id'];

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $sql = "SELECT * FROM cart_items WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // Nếu có, cập nhật số lượng
                $sql = "UPDATE cart_items SET quantity = quantity + $quantity WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
                $conn->query($sql);
            } else {
                // Nếu chưa có, thêm sản phẩm vào giỏ hàng
                $sql = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', '$quantity')";
                $conn->query($sql);
            }
        } else {
            // Tạo giỏ hàng mới cho người dùng
            $sql = "INSERT INTO carts (user_id) VALUES ('$this->user_id')";
            $conn->query($sql);
            $cart_id = $conn->insert_id;

            // Thêm sản phẩm vào giỏ hàng
            $sql = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', '$quantity')";
            $conn->query($sql);
        }
    }

    // Cập nhật số lượng sản phẩm
    public function updateQuantity($product_id, $quantity) {
        global $conn;

        // Lấy cart_id của người dùng
        $sql = "SELECT cart_id FROM carts WHERE user_id = '$this->user_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cart_id = $row['cart_id'];

            $sql = "UPDATE cart_items SET quantity = '$quantity' WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
            $conn->query($sql);
        }
    }

    // Xoá sản phẩm khỏi giỏ
    public function removeItem($product_id) {
        global $conn;

        // Lấy cart_id của người dùng
        $sql = "SELECT cart_id FROM carts WHERE user_id = '$this->user_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cart_id = $row['cart_id'];

            $sql = "DELETE FROM cart_items WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
            $conn->query($sql);
        }
    }

    // Lấy tất cả sản phẩm trong giỏ
    public function getItems() {
        global $conn;

        $sql = "SELECT p.*, ci.quantity FROM products p JOIN cart_items ci ON p.product_id = ci.product_id JOIN carts c ON ci.cart_id = c.cart_id WHERE c.user_id = '$this->user_id'";
        $result = $conn->query($sql);
        $items = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[$row['product_id']] = $row;
            }
        }
        return $items;
    }

    // Tính tổng giá trị giỏ hàng
  public function getTotal() {
      global $conn;
  
      $total = 0;
      $items = $this->getItems();
      foreach ($items as $item) {
          $total += $item['price'] * $item['quantity'];
      }
      return $total;
  }

    // Xóa giỏ hàng
    public function clearCart() {
        global $conn;

        // Lấy cart_id của người dùng
        $sql = "SELECT cart_id FROM carts WHERE user_id = '$this->user_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cart_id = $row['cart_id'];

            $sql = "DELETE FROM cart_items WHERE cart_id = '$cart_id'";
            $conn->query($sql);
        }
    }
}
?>