<?php
ob_start();
session_start();
?>

<div class="d-flex justify-content-center align-items-center vh-100 mt-n5">
    <form action="login_process.php" method="POST" class="p-5 w-50 border rounded-3 shadow-lg">
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['login_error'];
                unset($_SESSION['login_error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="form-outline mb-4">
            <label class="form-label" for="form2Example1">Tài khoản</label>
            <input type="text" name="username" id="form2Example1" class="form-control" required />
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="form2Example2">Mật khẩu</label>
            <input type="password" name="password" id="form2Example2" class="form-control" required />
        </div>

        <button type="submit" class="btn btn-primary btn-block mb-4">Đăng nhập</button>
    </form>
</div>

<?php
$contentadmin = ob_get_clean();
include "layoutadmin.php";
?>