<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost"; // Địa chỉ máy chủ cơ sở dữ liệu
$username = "mainhathoai";
// Tài khoản người dùng
$password = "mainhathoai@123";
// Mật khẩu của người dùng
$dbname = "banbanh";
// Tên cơ sở dữ liệu
// Tạo kết nối
$conn = new mysqli ($servername, $username, $password, $dbname);
//$conn =
if(!$conn)
{
    die ("Could not connect to MySQL
Database".mysqli_error());
}
?>