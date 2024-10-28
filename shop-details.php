<?php
session_start();
ob_start();
include 'includes/db_conn.inc';
if (isset($_GET['id'])) {
    $maSP = intval($_GET['id']);
    // Lấy thông tin chi tiết của sách
    $sql = "SELECT * FROM products WHERE product_id = " . $maSP;
    $result = mysqli_query($conn, $sql);
    $cake = mysqli_fetch_assoc($result);
} else {

    echo "Không tìm thấy sách.";
    exit;
} ?>

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
                        <h3> <?php echo htmlspecialchars($cake['product_name']); ?></h3>
                        <div class="product__details__rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <span>(18 reviews)</span>
                        </div>
                        <div class="product__details__price"><?php echo number_format(num: $cake['price'], decimals: 00, decimal_separator: ',', thousands_separator: '-'); ?> VND</div>
                        <p> <?php echo nl2br(htmlspecialchars($cake['description'])); ?></p>
                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="text" value="1">
                                </div>
                            </div>
                        </div>
                        <a href="#" class="primary-btn">ADD TO CARD</a>
                        <a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
                        <ul>
                            <li><b>Availability</b> <span>In Stock</span></li>
                            <li><b>Shipping</b> <span>01 day shipping. <samp>Free pickup today</samp></span></li>
                            <li><b>Weight</b> <span>0.5 kg</span></li>
                            <li><b>Share on</b>
                                <div class="share">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
            
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->
    <style >


</style>
<?php
$content = ob_get_clean();
include "includes/layout.php";
?>
