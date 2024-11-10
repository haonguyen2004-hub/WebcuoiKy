<?php ob_start(); ?>
<?php
include '../includes/db_conn.inc'; // Kết nối tới database

// Lấy danh sách các danh mục
$categories_result = $conn->query("SELECT category_id, category_name FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $product_image = $_FILES['product_image']['name'];

    // Upload ảnh
    move_uploaded_file($_FILES['product_image']['tmp_name'], "../img/product/" . $product_image);

    // Chèn sản phẩm vào database
    $sql = "INSERT INTO products (product_name, description, price, stock_quantity, product_image, category_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $product_name, $description, $price, $stock_quantity, $product_image, $category_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Lỗi khi thêm sản phẩm.";
    }
}
?>
<div class="container mt-5">
    <h1 class="mb-4">Thêm sản phẩm mới</h1>
    <form action="" method="post" enctype="multipart/form-data" class="bg-light p-5 rounded shadow-sm">
        <div class="form-group mb-4">
            <label for="product_name">Tên sản phẩm:</label>
            <input type="text" class="form-control" id="product_name" name="product_name" required>
        </div>

        <div class="form-group mb-4">
            <label for="description">Mô tả:</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <div class="form-group mb-4">
            <label for="price">Giá:</label>
            <input type="number" class="form-control" id="price" step="0.01" name="price" required>
        </div>

        <div class="form-group mb-4">
            <label for="stock_quantity">Số lượng tồn:</label>
            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
        </div>

        <div class="form-group mb-4">
            <label for="product_image">Hình ảnh:</label>
            <input type="file" class="form-control-file" id="product_image" name="product_image" required>
        </div>

        <div class="form-group mb-4">
            <label for="category_id">Danh mục:</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Chọn danh mục</option>
                <?php while ($row = $categories_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
    </form>
</div>


<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>
