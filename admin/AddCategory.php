<?php
ob_start();
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Xử lý thêm danh mục
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $category_image = $_FILES['category_image']['name'];

    // Upload ảnh danh mục nếu có
    if ($category_image) {
        move_uploaded_file($_FILES['category_image']['tmp_name'], "../img/categories/" . $category_image);
    }

    // Thêm danh mục mới vào database
    $sql = "INSERT INTO categories (category_name, category_image) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $category_name, $category_image);

    if ($stmt->execute()) {
        header("Location: CategoryManager.php?message=added");
        exit();
    } else {
        echo "Lỗi khi thêm danh mục.";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Thêm danh mục mới</h1>
    <form action="" method="post" enctype="multipart/form-data" class="bg-light p-5 rounded shadow-sm">
        <div class="form-group mb-4">
            <label for="category_name">Tên danh mục:</label>
            <input type="text" class="form-control" id="category_name" name="category_name" required>
        </div>
        <div class="form-group mb-4">
            <label for="category_image">Hình ảnh danh mục:</label>
            <input type="file" class="form-control-file" id="category_image" name="category_image">
        </div>
        <button type="submit" class="btn btn-primary">Thêm danh mục</button>
        <a href="CategoryManager.php" class="btn btn-danger">Hủy</a>
    </form>
</div>
<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>