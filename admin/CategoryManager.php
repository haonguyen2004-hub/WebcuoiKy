<?php ob_start(); ?>
<?php
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database
if (!isset( $_SESSION['admin_logged_in'])) {
    header("Location: loginadmin.php");
  exit;
  }
// Xử lý xóa danh mục
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM categories WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: CategoryManager.php");
    exit();
}

// Truy vấn danh sách các danh mục
$categories_result = $conn->query("SELECT * FROM categories");
?>

<div class="container mt-5">
    <h1 class="mb-4">Quản lý danh mục</h1>

    <!-- Nút chuyển đến trang thêm danh mục -->
    <a href="AddCategory.php" class="btn btn-primary mb-4">Thêm danh mục mới</a>

    <!-- Hiển thị danh sách danh mục -->
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Hình ảnh</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $categories_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['category_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td>
                        <?php if ($row['category_image']): ?>
                            <img src="../img/categories/<?php echo $row['category_image']; ?>" width="50" alt="Category Image">
                        <?php else: ?>
                            Không có ảnh
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_category.php?id=<?php echo $row['category_id']; ?>" class="btn btn-primary btn-sm">Sửa</a>
                        <a href="CategoryManager.php?delete_id=<?php echo $row['category_id']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc muốn xóa danh mục này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>