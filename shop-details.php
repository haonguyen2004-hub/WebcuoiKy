<?php
session_start();
ob_start();
include 'includes/db_conn.inc';

if (isset($_GET['id'])) {
    $maSP = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE product_id = " . $maSP;
    $result = mysqli_query($conn, $sql);
    $cake = mysqli_fetch_assoc($result);
} else {
    echo "Không tìm thấy dữ liệu.";
    exit;
}

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'])) {
    if (!isset($_SESSION['customer_id'])) {
        echo "Bạn cần đăng nhập để gửi đánh giá.";
        exit;
    }
    $customer_id = $_SESSION['customer_id'];
    $rating = (int) $_POST['rating'];
    $review = trim($_POST['review']);

    $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, customer_id, rating, review_text, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $maSP, $customer_id, $rating, $review);
    $stmt->execute();
    echo "Đánh giá của bạn đã được lưu.";
}

// Lấy danh sách đánh giá
$review_query = "SELECT r.rating, r.review_text, r.created_at, c.first_name, c.last_name 
                 FROM product_reviews r 
                 JOIN customers c ON r.customer_id = c.customer_id 
                 WHERE r.product_id = ? ORDER BY r.created_at DESC";
$review_stmt = $conn->prepare($review_query);
$review_stmt->bind_param("i", $maSP);
$review_stmt->execute();
$reviews = $review_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <img class="product__details__pic__item--large"
                        src="img/product/<?php echo $cake['product_image']; ?>" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?php echo htmlspecialchars($cake['product_name']); ?></h3>
                    <div class="product__details__price">
                        <?php echo number_format($cake['price'], 0, ',', '.'); ?> VNĐ
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($cake['description'])); ?></p>
                    <input type="hidden" id="product_id" value="<?php echo $cake['product_id']; ?>">
                    <a type="button" onclick="addToCart(<?php echo $maSP ?>)" class="primary-btn ">Thêm vào
                        giỏ hàng</a>
                       
                    <a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
                    <ul>
                        <li><b>Tình trạng</b>
                            <span><?php echo ($cake['stock_quantity'] > 0) ? 'Còn hàng' : 'Hết hàng'; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="review-form card p-4 shadow-sm">
                    <h4 class="mb-4">Đánh Giá Sản Phẩm</h4>
                    <form action="" method="POST">
                        <div class="form-group">
                            <select name="rating" id="rating" class="form-control" required>
                                <option value="5">5 sao</option>
                                <option value="4">4 sao</option>
                                <option value="3">3 sao</option>
                                <option value="2">2 sao</option>
                                <option value="1">1 sao</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="review" class="font-weight-bold">Viết đánh giá của bạn</label>
                            <textarea name="review" id="review" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Gửi Đánh Giá</button>
                    </form>
                </div>


                <hr class="my-5">

                <!-- Display Reviews -->
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review mb-4 p-3 bg-light rounded shadow-sm">
                            <h5 class="mb-1"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?>
                            </h5>
                            <small class="text-muted">
                                <span class="stars"><?php echo str_repeat("★", $review['rating']); ?></span>
                                <?php echo $review['rating']; ?> sao -
                                <?php echo date("d-m-Y", strtotime($review['created_at'])); ?>
                            </small>
                            <p class="mt-2"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
    .review {
        background-color: #f9f9f9;
        border-left: 4px solid #ffba00;
        /* Tạo viền bên trái để nhấn mạnh */
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Tên khách hàng */
    .review h5 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    /* Ngày và sao đánh giá */
    .review small {
        color: #666;
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    /* Biểu tượng sao */
    .review .stars {
        color: #ffba00;
        font-size: 16px;
        margin-right: 5px;
    }

    /* Nội dung đánh giá */
    .review p {
        font-size: 15px;
        margin-top: 10px;
        line-height: 1.6;
    }

    /* Form đánh giá */
    .review-form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Form đánh giá */
    .review-form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    /* Tiêu đề form */
    .review-form h4 {
        font-weight: bold;
        font-size: 20px;
        color: #333;
        margin-bottom: 20px;
    }

    /* Nhãn và phần chọn sao */
    .review-form label {
        font-weight: 500;
        color: #555;
    }

    /* Dropdown cho rating */
    .review-form select {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-top: 5px;
        font-size: 15px;
    }

    /* Textarea cho đánh giá */
    .review-form textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-top: 10px;
        font-size: 15px;
        resize: vertical;
    }

    /* Nút gửi đánh giá */
    .review-form button[type="submit"] {
        background-color: #ff6f61;
        color: #fff;
        font-weight: bold;
        padding: 10px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        margin-top: 15px;
    }

    .review-form button[type="submit"]:hover {
        background-color: #ff5439;
    }

    /* Căn giữa nội dung trong dropdown */
    .review-form select {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-top: 5px;
        font-size: 15px;
        text-align: center;
        /* Căn giữa nội dung */
        appearance: none;
    }
</style>




<?php
$content = ob_get_clean();
include "includes/layout.php";
?>