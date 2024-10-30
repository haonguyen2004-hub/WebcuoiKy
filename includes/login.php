<?php
ob_start();
include 'db_conn.inc';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$thongbao = "";

if (!isset($conn) || !$conn) {
    $thongbao = "Kết nối cơ sở dữ liệu thất bại.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['TenDN']);
    $password = trim($_POST['MatKhau']);
    
    if (empty($username) || empty($password)) {
        $thongbao = "Tên đăng nhập và mật khẩu không được trống.";
    } else {
        $sql = "SELECT * FROM customers WHERE phone = '$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $thongbao = "Tên đăng nhập không tồn tại.";
        } else {
            $user = mysqli_fetch_assoc($result);
            // So sánh mật khẩu đã mã hóa (bạn nên sử dụng `password_verify` thay vì so sánh trực tiếp)
            if ($password === $user['password']) {
                // Thiết lập session cho người dùng
                $_SESSION['user'] = $user;
                $_SESSION['customer_id'] = $user['customer_id']; // Lưu ID của khách hàng vào session
                echo "success";
                exit;
            } else {
                $thongbao = "Mật khẩu không đúng.";
            }
        }
    }
}
?>

<!-- Giao diện sau khi đăng nhập -->
<?php if (isset($_SESSION['user'])): ?>
    <?php $kh = $_SESSION['user']; ?>
    <button class="btn"> <i class="fas fa-user"></i> <?php echo $kh["first_name"] . " " . $kh["last_name"]; ?></button>
    <div class="dropdown">
        <button class="btn" style="border-left:1px solid navy">
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="#">Tài khoản</a>
            <a href="logout.php">Đăng xuất</a>
        </div>
    </div>
<?php else: ?>
    <!-- Giao diện đăng nhập -->
    <button onclick="document.getElementById('id01').style.display='block'">Đăng nhập</button>
    <div id="id01" class="modal">
        <form class="animate" id="loginForm" action="index.php" method="POST">
            <div class="login-container mt-5">
                <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Hủy đăng nhập">&times;</span>
                <div class="text-center mb-4">
                    <img src="img/Lg-login.png" alt="Avatar" class="avatar rounded-circle">
                    <h2>Đăng Nhập</h2>
                    <div id="thongbao" style="color: red;"><?php echo $thongbao; ?></div>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control mb-3" id="floatingInput" placeholder="Số điện thoại" name="TenDN" required>
                    <label for="floatingInput">Nhập số điện thoại</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control mb-3" id="floatingPassword" name="MatKhau" placeholder="Password" required>
                    <label for="floatingPassword">Mật khẩu</label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label>
                        <input type="checkbox" checked="checked" name="remember"> Ghi nhớ
                    </label>
                    <span><a href="#">Quên mật khẩu?</a></span>
                </div>
                <div class="mb-2">
                    <button type="submit" class="btn btn-primary w-100 p-2 mb-2 btn-lg">Đăng Nhập</button>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-2">Bạn chưa có tài khoản?</span>
                    <a href="signup.php" class="btn btn-link p-1 text-primary text-decoration-none">Đăng ký một tài khoản</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<style>
    .login-container { max-width: 400px; margin: auto; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); background-color: #fff; }
    .avatar { width: 100px; height: 100px; margin-bottom: 10px; }
    .form-control { height: 30px; }
    .animate { animation: animatezoom 0.6s; }
    @keyframes animatezoom { from { transform: scale(0) } to { transform: scale(1) } }
    .dropdown { position: absolute; display: inline-block; }
    .dropdown-content { display: none; position: absolute; background-color: #f1f1f1; min-width: 100px; z-index: 1; }
    .dropdown-content a { color: black; padding: 12px 16px; text-decoration: none; display: block; }
    .dropdown-content a:hover { background-color: #ddd; }
    .dropdown:hover .dropdown-content { display: block; }
    .btn { border: none; outline: none; }
    .btn:hover, .dropdown:hover .btn { background-color: #9e513b; }
</style>

<script>
    var modal = document.getElementById('id01');
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
