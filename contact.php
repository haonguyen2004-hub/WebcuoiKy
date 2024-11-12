<?php
ob_start();
session_start();
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Lấy dữ liệu từ form
    $first_name = trim($_POST['first_name']);
    $contentEmail = trim($_POST['contentEmail']);
    $email = trim($_POST['email']);

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
    $mail->setFrom('nhathoai2004@gmail.com', '$first_name');
    $mail->addAddress("999hoai@gmail.com", $first_name);

    // Nội dung email
    $mail->isHTML(true);
    $mail->Subject = 'Email liên hệ từ khách hàng';
    $mail->Body = " $contentEmail";
    $mail->AltBody = "Chào $first_name, Tui có điều muốn nói.";

    // Gửi email
    $mail->send();
    $thongbao = "Cảm ơn bạn đã liên hệ";
} catch (Exception $e) {
    $thongbao = "Chủ tiệm đi vắng rồi. liên hệ sau nhé";
}
}
?>

<!-- Contact Section Begin -->
<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_phone"></span>
                    <h4>Phone</h4>
                    <p>0899 414 692</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_pin_alt"></span>
                    <h4>Địa chỉ</h4>
                    <p>KTKT Bình Dương</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_clock_alt"></span>
                    <h4>Giờ mở cửa</h4>
                    <p>10:00 am - 23:00 pm</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_mail_alt"></span>
                    <h4>Email</h4>
                    <p>hello@colorlib.com</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<!-- Map Begin -->
<div class="map">
  
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3917.0893054639673!2d106.70927937451938!3d10.956626455845088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174d751e024b99d%3A0x20b3f9b4c8fdc732!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLaW5oIHThur8gLSBL4bu5IHRodeG6rXQgQsOsbmggRMawxqFuZw!5e0!3m2!1svi!2s!4v1731419315971!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    <div class="map-inside">
        <i class="icon_pin"></i>
        <div class="inside-widget">
            <h4>KTKT BinhDuong</h4>
            <ul>
                <li>Điện thoại: 0908677351</li>
                <li>Địa chỉ: Thuận Giao, Thuận An, Bình Dương</li>
            </ul>
        </div>
    </div>
</div>
<!-- Map End -->

<!-- Contact Form Begin -->
<div class="contact-form spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact__form__title">
                    <h2>Có gì muốn nói với chúng tôi</h2>
                    <?php if (!empty($thongbao)): ?>
                        <p style="color:red; text-align:center;"><?php echo $thongbao; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <form action="contact.php" method="POST" >
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <input type="text" placeholder="Tên" name="first_name" required>
                </div>
                <div class="col-lg-6 col-md-6">
                    <input type="text" placeholder="Email" required>
                </div>
                <div class="col-lg-12 text-center">
                    <textarea placeholder="Tin nhắn" name="contentEmail" required ></textarea>
                    <button type="submit" class="site-btn" name="email">Gửi tin nhắn</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Contact Form End -->
<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>