document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Ngăn hành động mặc định của form

    let formData = new FormData(this);

    fetch("includes/login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        const thongbaoDiv = document.getElementById('thongbao'); // Lấy phần tử thông báo

        if (data === "success") {
            thongbaoDiv.innerHTML = ""; // Xóa thông báo khi đăng nhập thành công
            document.getElementById('id01').style.display = 'none'; // Đóng modal
            this.reset(); // Làm mới form
           
        } else {
            thongbaoDiv.innerHTML = "Vui lòng kiểm tra lại SĐT và mật khẩu"; // Hiển thị thông báo lỗi
        }
    })
    .catch(error => console.error("Lỗi:", error));
});
