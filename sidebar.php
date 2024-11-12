<?php
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
?>
<section class="breadcrumb-section set-bg" data-setbg="img/banner-shop.png">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Bakery Shop</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Trang chủ</a>
                        <span>Sản phẩm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Danh mục</h4>
                        <ul>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<li ><a href="shop-categories.php?id='.$row['category_id'].'&name='.$row['category_name'].'">' . $row['category_name'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <div class="latest-product__text">
                            <h4>Sản phẩm mới</h4>
                            <div class="latest-product__slider owl-carousel">
                              <?php 
                              include'includes/NewProduct.php';
                              ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>