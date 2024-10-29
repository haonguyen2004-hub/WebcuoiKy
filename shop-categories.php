<?php
session_start();
ob_start();
include 'includes/db_conn.inc';
$sql = "SELECT 
    p.product_id,
    p.product_name,
    p.price AS original_price,
    d.discount_name,
    d.discount_type,
    d.discount_value,
    d.start_date,
    d.end_date,
    CASE 
        WHEN d.discount_type = 'percentage' THEN p.price * (1 - d.discount_value / 100)
        WHEN d.discount_type = 'fixed' THEN p.price - d.discount_value
        ELSE p.price
    END AS discounted_price
FROM 
    products p
JOIN 
    productdiscounts pd ON p.product_id = pd.product_id
JOIN 
    discounts d ON pd.discount_id = d.discount_id
WHERE 
    CURRENT_DATE BETWEEN d.start_date AND d.end_date
    AND (d.discount_type = 'percentage' OR d.discount_type = 'fixed')
    AND (d.discount_type = 'percentage' OR p.price > d.discount_value);";
$result = mysqli_query($conn, $sql);
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
                                    <h6><span>16</span> Products found</h6>
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
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="#">Crab Pool Security</a></h6>
                                    <h5>$30.00</h5>
                                </div>
                            </div>
                        </div>
                     
                    </div>
                    <div class="product__pagination">
                        <a href="#">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#"><i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
<?php
$content = ob_get_clean();
include "includes/layout.php";
?>

</style>