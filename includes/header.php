<?php
// Kiểm tra nếu session chưa được khởi động thì mới gọi session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_conn.inc'; // Kết nối tới cơ sở dữ liệu

$totalAmount = 0; // Tổng tiền giỏ hàng
$totalItems = 0; // Tổng số sản phẩm trong giỏ hàng

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];

    // Truy vấn giỏ hàng của người dùng từ bảng `cartitems` và `carts`
    $sql = "SELECT SUM(ci.quantity) AS total_items, SUM(ci.quantity * ci.price) AS total_amount
            FROM cartitems ci
            JOIN carts c ON ci.cart_id = c.cart_id
            WHERE c.customer_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartData = $result->fetch_assoc();

    // Nếu có kết quả trong giỏ hàng
    if ($cartData) {
        $totalItems = $cartData['total_items'] ? $cartData['total_items'] : 0;
        $totalAmount = $cartData['total_amount'] ? $cartData['total_amount'] : 0;
    }
}
?>
<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>

<!-- Humberger Begin -->
<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="index.php"><img src="img/logo.png" alt=""></a>
    </div>
    <div class="humberger__menu__cart">
        <ul>
            <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
            <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
        </ul>
        <div class="header__cart__price">item: <span>$150.00</span></div>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__language">

            <div>Việt Nam</div>
            <span class="arrow_carrot-down"></span>
            <ul>
                <li><a href="#">Việt Nam</a></li>
                <li><a href="#">English</a></li>
            </ul>
        </div>
        <div class="header__top__right__auth">
            <a href="#"><i class="fa fa-user"></i> Đăng nhập</a>
        </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li class="active"><a href="./index.php">Trang chủ</a></li>
            <li><a href="./shop-grid.php">Cửa hàng</a></li>
            <li><a href="#">Pages</a>
                <ul class="header__menu__dropdown">
                    <li><a href="./shop-details.php">Sản phẩm</a></li>
                    <li><a href="./shoping-cart.php">Giỏ hàng</a></li>
                    <li><a href="./checkout.php">Thanh toán</a></li>

                </ul>
            </li>

            <li><a href="./contact.php">Contact</a></li>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">

    </div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> hello@colorlib.com</li>
            <li>Free Shipping for all Order of $99</li>
        </ul>
    </div>
</div>
<!-- Humberger End -->

<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__left">
                        <ul>
                            <li><i class="fa fa-envelope"></i> h&h_bakery@gmail.com</li>
                            <li>Miễn phí vận chuyển cho đơn hàng từ 99k</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">

                        </div>
                        <div class="header__top__right__language">
                            <img src="img/language.png" alt="">
                            <div>Tiếng Việt</div>
                            <span class="arrow_carrot-down"></span>
                            <ul>
                                <li><a href="#">Tiếng Việt</a></li>
                                <li><a href="#">Tiếng Anh</a></li>
                            </ul>
                        </div>
                        <div class="header__top__right__auth">
                            <?php include "login.php"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="header__logo">
                    <a href="./index.php"><img src="img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="header__menu">
                    <ul>
                        <li class="active"><a href="./index.php">Trang chủ</a></li>
                        <li><a href="./shop-grid.php">Sản phẩm</a></li>
                        <li><a href="#">Pages</a>
                            <ul class="header__menu__dropdown">
                                <li><a href="./shop-details.php">Shop Details</a></li>
                                <li><a href="./shoping-cart.php">Shoping Cart</a></li>
                                <li><a href="./checkout.php">Check Out</a></li>
                                <li><a href="./blog-details.php">Blog Details</a></li>
                            </ul>
                        </li>

                        <li><a href="./contact.php">Liên hệ</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="header__cart">
                    <ul>
                        <?php if ($totalItems > 0): ?>
                            <li><a href="./shoping-cart.php"><i class="fa fa-shopping-bag"></i>
                                    <span><?php echo $totalItems; ?></span></a></li>
                            <div class="header__cart__price">Tổng giỏ hàng:
                                <span><?php echo number_format($totalAmount, 0); ?> VNĐ</span></div>
                        <?php else: ?>
                            <li><a href="./shoping-cart.php"><i class="fa fa-shopping-bag"></i> <span>0</span></a></li>
                            <div class="header__cart__price">Giỏ hàng trống</div>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header Section End -->