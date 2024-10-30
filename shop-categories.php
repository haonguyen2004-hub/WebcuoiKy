<?php
session_start();
ob_start();
include 'includes/db_conn.inc';

if (isset($_GET['id'])) {
    $machude = intval($_GET['id']);
    $tenchude = $_GET['name'];
    
    // Số sản phẩm trên mỗi trang
    $productsPerPage = 6;
    
    // Xác định trang hiện tại
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $productsPerPage;
    
    // Lấy tổng số sản phẩm trong danh mục
    $sqlTotal = "SELECT COUNT(*) AS total FROM products WHERE category_id = '$machude'";
    $resultTotal = mysqli_query($conn, $sqlTotal);
    $totalRow = mysqli_fetch_assoc($resultTotal);
    $totalProducts = $totalRow['total'];
    $totalPages = ceil($totalProducts / $productsPerPage);
    
    // Lấy sản phẩm cho trang hiện tại
    $sqlchude = "SELECT * FROM products WHERE category_id = '$machude' LIMIT $productsPerPage OFFSET $offset";
    $resultSach = mysqli_query($conn, $sqlchude);

    if (!$resultSach) {
        die("Lỗi truy vấn SQL: " . mysqli_error($conn));
    }

} else {
    echo "Không tìm thấy dữ liệu.";
    exit;
}

include "sidebar.php";
?>

<div class="col-lg-9 col-md-7">
    <div class="filter__item">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="filter__sort">
                    <span>Sort By</span>
                    <select>
                        <option value="0">Default</option>
                        <option value="0">Default</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="filter__found">
                    <h4><span>Sản phẩm </span><?php echo $tenchude; ?></h4>
                </div>
            </div>
            <div class="col-lg-4 col-md-3">
                <div class="filter__option">
                    <span class="icon_grid-2x2"></span>
                    <span class="icon_ul"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php
        while ($row = $resultSach->fetch_assoc()):
            ?>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="featured__item">
                    <div class="featured__item__pic">
                        <a href="shop-details.php?id=<?php echo $row['product_id']; ?>"> 
                            <img src="img/product/<?php echo htmlspecialchars($row['product_image']); ?>"
                                alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                                style="width: 100%; height: auto; object-fit: cover;">
                        </a>
                        <ul class="featured__item__pic__hover">
                            <li title="Xem sản phẩm"><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><i class="fa-solid fa-eye"></i></a></li>
                            <li title="Chuyển Ảnh"><a href="#"><i class="fa fa-retweet"></i></a></li>
                            <li title="Thêm vào giỏ hàng"><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                    <div class="featured__item__text">
                        <h6><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><?php echo htmlspecialchars($row['product_name']); ?></a></h6>
                        <h5><?php echo number_format($row['price'], 0) . ' VNĐ'; ?></h5>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Phân trang -->
    <div class="product__pagination">
        <?php if ($page > 1): ?>
            <a href="?id=<?= $machude ?>&name=<?= urlencode($tenchude) ?>&page=<?= $page - 1 ?>"><i class="fa fa-long-arrow-left"></i></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?id=<?= $machude ?>&name=<?= urlencode($tenchude) ?>&page=<?= $i ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?id=<?= $machude ?>&name=<?= urlencode($tenchude) ?>&page=<?= $page + 1 ?>"><i class="fa fa-long-arrow-right"></i></a>
        <?php endif; ?>
    </div>
</div>
</div>
</div>
</section>
<!-- Product Section End -->
<?php
$content = ob_get_clean();
include "includes/layout.php";
