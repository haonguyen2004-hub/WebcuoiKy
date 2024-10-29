<?php
session_start();
ob_start();

include 'includes/db_conn.inc';
include("sidebar.php");

?>
<div class="col-lg-9 col-md-7">
    <div class="product__discount">
        <div class="section-title product__discount__title">
            <h2>Khuyến mãi</h2>
        </div>
        <div class="row">
            <div class="product__discount__slider owl-carousel">
                <?php
                include 'includes/SaleProduct.php';
                ?>
            </div>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>