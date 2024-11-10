<?php
session_start();
include '../includes/db_conn.inc'; // Kết nối tới database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn thông tin tài khoản admin từ bảng `admins`
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra kết quả truy vấn
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // So sánh trực tiếp mật khẩu nhập vào với mật khẩu trong cơ sở dữ liệu
        if ($password === $admin['password']) {
            // Đăng nhập thành công, lưu thông tin vào session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            // Chuyển hướng đến trang admin
            header("Location: ../admin/index.php");
            exit();
        } else {
            // Mật khẩu không đúng
            $_SESSION['login_error'] = "Mật khẩu không đúng.";
            header("Location: loginadmin.php");
            exit();
        }
    } else {
        // Tên tài khoản không tồn tại
        $_SESSION['login_error'] = "Tên tài khoản không đúng.";
        header("Location: loginadmin.php");
        exit();
    }
}
?>
