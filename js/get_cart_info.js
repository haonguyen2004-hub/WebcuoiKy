function updateCartInfo() {
    fetch("../includes/get_cart_info.php")
    .then(response => response.json())
    .then(data => {
        document.querySelector(".header__cart__price span").innerText = `${data.totalAmount.toLocaleString()} VNĐ`;
        document.querySelector(".fa-shopping-bag + span").innerText = data.totalItems;
    })
    .catch(error => console.error("Lỗi:", error));
}

document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("includes/login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            updateCartInfo(); // Gọi API cập nhật giỏ hàng
            document.getElementById('id01').style.display = 'none';
            this.reset();
        } else {
            document.getElementById('thongbao').innerHTML = "Vui lòng kiểm tra lại SĐT và mật khẩu";
        }
    })
    .catch(error => console.error("Lỗi:", error));
});
