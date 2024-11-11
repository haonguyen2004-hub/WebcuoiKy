<?php
session_start();
ob_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Kiểm tra và lấy `category_id` từ URL
if (!isset($_GET['id'])) {
    header("Location: CategoryManager.php");
    exit();
}

$category_id = $_GET['id'];

// Truy vấn thông tin danh mục hiện tại
$sql = "SELECT * FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    echo "Danh mục không tồn tại!";
    exit();
}

// Cập nhật danh mục nếu form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $category_image = $_FILES['category_image']['name'];

    // Xử lý upload ảnh mới (nếu có)
    if (!empty($category_image)) {
        move_uploaded_file($_FILES['category_image']['tmp_name'], "../img/category/" . $category_image);
    } else {
        $category_image = $category['category_image']; // Giữ nguyên ảnh cũ nếu không upload ảnh mới
    }

    // Thực hiện cập nhật danh mục
    $update_sql = "UPDATE categories SET category_name = ?, category_image = ? WHERE category_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $category_name, $category_image, $category_id);

    if ($stmt->execute()) {
        header("Location: CategoryManager.php?message=updated");
        exit();
    } else {
        echo "Lỗi khi cập nhật danh mục.";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Sửa danh mục</h1>

    <form action="" method="post" enctype="multipart/form-data" class="bg-light p-5 rounded shadow-sm">
        <div class="form-group mb-4">
            <label for="category_name">Tên danh mục:</label>
            <input type="text" class="form-control" id="category_name" name="category_name" 
                   value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="category_image">Ảnh danh mục:</label>
            <?php if ($category['category_image']): ?>
                <img src="../img/categories/<?php echo $category['category_image']; ?>" alt="<?php echo $category['category_name']; ?>" width="100" class="d-block mb-2">
            <?php endif; ?>
            <input type="file" class="form-control-file" id="category_image" name="category_image">
            <small class="text-muted">Để trống nếu không muốn thay đổi ảnh.</small>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="CategoryManager.php" class="btn btn-danger">Hủy bỏ</a>
    </form>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>


