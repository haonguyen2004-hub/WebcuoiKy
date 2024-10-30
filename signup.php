<?php
ob_start();
include 'includes/db_conn.inc';
session_start();
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass = $_POST['pass'];
    $repass = $_POST['repass'];

    // Kiểm tra đầu vào
    if (empty($first_name) || empty($last_name)) {
        $thongbao = "Họ và tên không được để trống.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $thongbao = "Địa chỉ email không hợp lệ.";
    } elseif (empty($phone)) {
        $thongbao = "Số điện thoại không được để trống.";
    } elseif (empty($pass)) {
        $thongbao = "Mật khẩu không được để trống.";
    } elseif ($pass != $repass) {
        $thongbao = "Mật khẩu và xác nhận mật khẩu không khớp.";
    } else {
        // Kiểm tra xem email đã tồn tại chưa
        $checkUser = "SELECT * FROM customers WHERE email='$email'";
        $result = mysqli_query($conn, $checkUser);

        if (mysqli_num_rows($result) > 0) {
            $thongbao = "Email đã tồn tại!";
        } else {
            // Thực hiện truy vấn SQL để thêm người dùng mới vào bảng customers
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customers (first_name, last_name, email, phone, password) 
                    VALUES ('$first_name', '$last_name', '$email', '$phone', '$hashedPassword')";

            if (mysqli_query($conn, $sql)) {
                $thongbao = "Đăng ký thành công!";
                $mail = new PHPMailer(true);
                try {
                    // Cấu hình SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'nhathoai2004@gmail.com';
                    $mail->Password = 'gakh cbdw xrny ahcv';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';
                    // Cài đặt thông tin người gửi và người nhận
                    $mail->setFrom('nhathoai2004@gmail.com', 'H&H.Bakery');
                    $mail->addAddress($email, $first_name . ' ' . $last_name);

                    // Nội dung email
                    $mail->isHTML(true);
                    $mail->Subject = 'Xác nhận đăng ký thành công';
                    $mail->Body = "Chào $first_name, <br><br>Cảm ơn bạn đã đăng ký tài khoản. Chúng tôi rất vui khi có bạn đồng hành!";
                    $mail->AltBody = "Chào $first_name, Cảm ơn bạn đã đăng ký.";

                    // Gửi email
                    $mail->send();
                    $thongbao = "Đăng ký thành công! Email xác nhận đã được gửi tới $email.";
                } catch (Exception $e) {
                    $thongbao = "Đăng ký thành công, nhưng gửi email thất bại. Error: {$mail->ErrorInfo}";
                }
            } else {
                $thongbao = "Lỗi trong quá trình đăng ký, vui lòng thử lại.";
            }
        }
    }
    mysqli_close($conn);
}
?>

<!-- Form đăng ký HTML -->
<section class="">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <h2 class="text-left mt-5 mb-4 w-100">Đăng ký tài khoản</h2>
                <form action="signup.php" method="POST">
                    <?php if (!empty($thongbao)): ?>
                        <p style="color:red; text-align:center;"><?php echo $thongbao; ?></p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="first_name">Tên</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nhập tên của bạn" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Họ</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nhập họ của bạn" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email của bạn" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Nhập mật khẩu của bạn" required>
                    </div>
                    <div class="form-group">
                        <label for="repass">Xác nhận mật khẩu</label>
                        <input type="password" class="form-control" id="repass" name="repass" placeholder="Xác nhận mật khẩu của bạn" required>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block p-2 mb-2 btn-lg">Đăng ký</button>
                    <div class="text-center mt-3">
                        <a href="index.php" class="text-primary">Quay lại trang chủ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include "includes/layout.php";
?>
