<?php
session_start();
ob_start();
include 'includes/db_conn.inc';

?>

<section class=" ">
<div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <h2 class="text-left mt-5 mb-4 w-100">Đăng ký tài khoản</h2>
                <form>
                    <!-- First Name -->
                    <div class="form-group">
                        <label for="first_name">Tên</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nhập tên của bạn">
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name">Họ</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nhập họ của bạn">
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email của bạn">
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại">
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ của bạn">
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn btn-dark btn-block p-2 mb-2 btn-lg">Đăng ký</button>

                    <!-- Back Link -->
                    <div class="text-center mt-3">
                        <a href="#" class="text-primary">Quay lại trang chủ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<style >
    .hero__item 
    {
        display: none;
    }
    .unactive{
        display: none;
    }
</style>
<?php
$content = ob_get_clean();
include "includes/layout.php";

?>