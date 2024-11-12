<?php
session_start();
ob_start();

include 'includes/db_conn.inc';
?>
<section class="bobyinfo">
    <div class="profile-container">
        <!-- Header -->
        <div class="d-flex align-items-center profile-header mb-4">
            <img src="img/avatar.png" alt="Avatar">
            <div class="ms-3 profile-info fs-4">
                <h3>Hoài</h3>
                <p>nhathoai2004</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="d-flex justify-content-between text-center stats mb-4 fs-4">
            <div>
                <h4>26</h4>
                <p>Đơn hàng</p>
            </div>
            <div>
                <h4>8</h4>
                <p>Đánh giá</p>
            </div>
            <div>
                <h4>2000</h4>
                <p>Tổng tiền</p>
            </div>
        </div>

        <!-- Form -->
        <form>
            <div class="mb-3">
                <label for="displayName" class="form-label fs-4">Tên hiển thị</label>
                <input type="text" class="form-control fs-4" id="displayName" placeholder="Hoài">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fs-4" >Email</label>
                <input type="email" class="form-control fs-4" id="email" placeholder="nhathoai2004@gmail.com">
            </div>
            <div class="mb-3">
                <label for="currentPassword" class="form-label fs-4">Mật khẩu</label>
                <input type="password" class="form-control fs-4" id="currentPassword" placeholder="Password">
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label fs-4">Mật khẩu mới</label>
                <input type="password" class="form-control fs-4" id="newPassword" placeholder="New password">
            </div>
            <div class="mb-4">
                <label for="confirmPassword" class="form-label fs-4">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control fs-4" id="confirmPassword" placeholder="Re new password">
            </div>
            <button type="submit" class=" btn btn-danger w-100 btn-lg fs-4">Lưu thay đổi</button>
        </br>
        <button type="button" class="btn btn-secondary w-100 mt-2 btn-lg fs-4">Xem giỏ hàng</button>
        </form>
    </div>
    </section>
    <style>
        .bobyinfo {
            background-color: #fff;
            color: #000000;
            min-height: 100vh;
          display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .profile-container {
            background-color: #ffffff;
          
            border-radius: 8px;
            width: 100%;
            max-width: 1000px;
        }

        .profile-header img {
            border-radius: 50%;
            width: 130px;
            height: 130px;
        }

        .profile-info h5 {
            margin-bottom: 0;
        }

        .profile-info p {
            color: #000000;
            margin-bottom: 0;
        }

        .form-control {
            background-color: #f6f6f6;
            border: none;
            color: #000000;
            height: 50px;
        }

        .form-control::placeholder {
            color: #646464;
        }

        .btn-save {
            background-color: #d73a4a;
            border: none;
        }

        .stats div {
            text-align: center;
        }

        .stats div h5 {
            font-weight: bold;
        }
    </style>
<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>