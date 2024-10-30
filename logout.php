<?php
session_start();

// Kiểm tra xem session của user có tồn tại hay không
if (isset($_SESSION['user'])) {
    // Xóa session chỉ cho user
    unset($_SESSION['user']);
}

// Chuyển hướng về trang chủ hoặc trang đăng nhập
header("Location: index.php");
exit();
?>