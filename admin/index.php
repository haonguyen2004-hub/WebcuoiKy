<?php
session_start();
ob_start();
include '../includes/db_conn.inc'; // Kết nối tới database

// Thiết lập số sản phẩm trên mỗi trang
$products_per_page = 5;

// Lấy số trang hiện tại từ URL, mặc định là trang 1
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

// Truy vấn tổng số sản phẩm để tính toán phân trang
$total_products_result = $conn->query("SELECT COUNT(*) AS total FROM products");
$total_products_row = $total_products_result->fetch_assoc();
$total_products = $total_products_row['total'];
$total_pages = ceil($total_products / $products_per_page);

// Truy vấn sản phẩm với phân trang và JOIN với bảng categories để lấy tên danh mục
$sql = "SELECT p.*, c.category_name 
        FROM products AS p 
        LEFT JOIN categories AS c ON p.category_id = c.category_id 
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $products_per_page);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="container mt-5">
  <h1 class="mb-4">Quản lý sản phẩm</h1>

  <!-- Nút Thêm sản phẩm -->
  <a href="add_product.php" class="btn btn-primary mb-3">Thêm sản phẩm mới</a>

  <!-- Bảng hiển thị sản phẩm -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Tên sản phẩm</th>
          <th>Mô tả</th>
          <th>Giá</th>
          <th>Số lượng tồn</th>
          <th>Hình ảnh</th>
          <th>Danh mục</th>
          <th>Ngày tạo</th>
          <th>Ngày cập nhật</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo number_format($row['price'], 2); ?> VND</td>
            <td><?php echo $row['stock_quantity']; ?></td>
            <td><img src="../img/product/<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>"
                width="50"></td>
            <td><?php echo $row['category_name'] ? $row['category_name'] : 'Không có danh mục'; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['updated_at']; ?></td>
            <td>
              <div class="btn-group" role="group">
                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>"
                  class="btn btn-sm btn-primary me-2">Sửa</a>
                <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-danger"
                  onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">Xóa</a>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($page = 1; $page <= $total_pages; $page++): ?>
        <li class="page-item <?php if ($page == $current_page)
          echo 'active'; ?>">
          <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>