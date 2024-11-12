<?php
ob_start();
session_start();

include 'includes/db_conn.inc';

// Nhận từ khóa tìm kiếm từ URL
$query = isset($_GET['query']) ? $_GET['query'] : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$resultsPerPage = 10;
$offset = ($page - 1) * $resultsPerPage;

$results = [];
$totalPages = 1; // Giá trị mặc định

if ($query) {
    // Tìm kiếm sản phẩm trong cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM products WHERE product_name LIKE ? OR description LIKE ? LIMIT ? OFFSET ?");
    $likeQuery = '%' . $query . '%';
    $stmt->bind_param("ssii", $likeQuery, $likeQuery, $resultsPerPage, $offset);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $results = $result->fetch_all(MYSQLI_ASSOC); // Lưu kết quả vào mảng

        // Lấy tổng số bản ghi để tính tổng số trang
        $totalResults = $conn->query("SELECT FOUND_ROWS()")->fetch_row()[0];
        $totalPages = ceil($totalResults / $resultsPerPage);
    } else {
        die("Lỗi truy vấn SQL: " . $conn->error);
    }
}
?>

<div class="container">
    <h2 class="text-center">Kết quả tìm kiếm cho "<?php echo htmlspecialchars($query); ?>"</h2>
    <?php if (!empty($results)): ?>
        <div class="row">
            <?php foreach ($results as $row): ?>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product__item">
                        <div class="product__item__pic"
                            style="background-image: url('img/product/<?php echo htmlspecialchars($row['product_image']); ?>'); background-size: cover; background-position: center;">
                            <ul class="product__item__pic__hover">
                                <li title="Xem sản phẩm"><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><i
                                            class="fa-solid fa-eye"></i></a></li>
                                <li title="Chuyển Ảnh"><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li title="Thêm vào giỏ hàng">
                                    <a href="javascript:void(0);" onclick="addToCart(<?php echo $row['product_id']; ?>)"><i
                                            class="fa fa-shopping-cart"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a
                                    href="shop-details.php?id=<?php echo $row['product_id']; ?>"><?php echo htmlspecialchars($row['product_name']); ?></a>
                            </h6>
                            <h5><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Phân trang -->
        <div class="product__pagination text-center">
            <?php if ($page > 1): ?>
                <a href="?query=<?php echo urlencode($query); ?>&page=<?php echo $page - 1; ?>"><i
                        class="fa fa-long-arrow-left"></i></a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>" <?php if ($i == $page)
                          echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?query=<?php echo urlencode($query); ?>&page=<?php echo $page + 1; ?>"><i
                        class="fa fa-long-arrow-right"></i></a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="text-center">Không tìm thấy sản phẩm nào.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>