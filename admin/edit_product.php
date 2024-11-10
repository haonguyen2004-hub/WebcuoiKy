<?php ob_start(); ?>
<?php
include '../includes/db_conn.inc'; // Kết nối tới database

// Lấy ID sản phẩm từ URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Truy vấn sản phẩm dựa trên ID
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Kiểm tra nếu sản phẩm không tồn tại
    if (!$product) {
        echo "Sản phẩm không tồn tại.";
        exit();
    }
} else {
    echo "ID sản phẩm không hợp lệ.";
    exit();
}

// Cập nhật thông tin sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $product_image = $product['product_image']; // Giữ lại ảnh cũ nếu không tải ảnh mới

    // Kiểm tra nếu người dùng tải lên một ảnh mới
    if (!empty($_FILES['product_image']['name'])) {
        $product_image = $_FILES['product_image']['name'];
        move_uploaded_file($_FILES['product_image']['tmp_name'], "../img/product/" . $product_image);
    }

    // Cập nhật sản phẩm vào database
    $sql = "UPDATE products SET product_name = ?, description = ?, price = ?, stock_quantity = ?, product_image = ?, category_id = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisii", $product_name, $description, $price, $stock_quantity, $product_image, $category_id, $product_id);

    if ($stmt->execute()) {
        header("Location: product_management.php?message=updated");
        exit();
    } else {
        echo "Lỗi khi cập nhật sản phẩm.";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Sửa sản phẩm</h1>
    <form action="" method="post" enctype="multipart/form-data" class="bg-light p-5 rounded shadow-sm">
        <div class="form-group mb-4">
            <label for="product_name">Tên sản phẩm:</label>
            <input type="text" class="form-control" id="product_name" name="product_name"
                value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="description">Mô tả:</label>
            <textarea class="form-control" id="description" name="description"
                rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="form-group mb-4">
            <label for="price">Giá:</label>
            <input type="number" class="form-control" id="price" step="0.01" name="price"
                value="<?php echo $product['price']; ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="stock_quantity">Số lượng tồn:</label>
            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                value="<?php echo $product['stock_quantity']; ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="product_image">Hình ảnh:</label>
            <input type="file" class="form-control-file" id="product_image" name="product_image">
            <small class="form-text text-muted">Để trống nếu không muốn thay đổi ảnh.</small>
            <div class="mt-2">
                <img src="../img/product/<?php echo $product['product_image']; ?>" width="100" alt="Ảnh hiện tại">
            </div>
        </div>

        <div class="form-group mb-4">
            <label for="category_id">Danh mục:</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Chọn danh mục</option>
                <?php
                $categories_result = $conn->query("SELECT category_id, category_name FROM categories");
                while ($category = $categories_result->fetch_assoc()):
                    ?>
                    <option value="<?php echo $category['category_id']; ?>" <?php if ($product['category_id'] == $category['category_id'])
                           echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="product_management.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>