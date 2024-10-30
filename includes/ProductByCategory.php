<?php
include 'db_conn.inc';

$productsPerPage = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $productsPerPage;

$totalProductsResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProductsRow = mysqli_fetch_assoc($totalProductsResult);
$totalProducts = $totalProductsRow['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

$sql = "SELECT * FROM products LIMIT $productsPerPage OFFSET $offset";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)):
    $productName = $row['product_name'];
    $productImage = $row['product_image'];
    $productPrice = number_format($row['price'], 0) . ' VNĐ';
    ?>

    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="product__item">
            <div class="product__item__pic set-bg" data-setbg="img/product/<?php echo htmlspecialchars($productImage); ?>">
                <ul class="product__item__pic__hover">
                    <li title="Xem sản phẩm"><a href="shop-details.php?id=<?php echo $row['product_id']; ?>"><i
                                class="fa-solid fa-eye"></i></a>
                    </li>
                    <li title="Chuyển Ảnh"><a href="#"><i class="fa fa-retweet"></i></a></li>
                    <li title="Thêm vào giỏ hàng"><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                </ul>
            </div>
            <div class="product__item__text">
                <h6><a href="#"><?php echo htmlspecialchars($productName); ?></a></h6>
                <h5><?php echo $productPrice; ?></h5>
            </div>
        </div>
    </div>

<?php endwhile; ?>

<div class="product__pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>"><i class="fa fa-long-arrow-left"></i></a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>"><i class="fa fa-long-arrow-right"></i></a>
    <?php endif; ?>
</div>