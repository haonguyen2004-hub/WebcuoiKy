// Định nghĩa hàm addToCart ở bên ngoài để có thể gọi từ HTML
function addToCart(productId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "includes/addToCart.php", true);  // Đảm bảo đường dẫn chính xác
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Xử lý phản hồi từ server
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (xhr.responseText.trim() === "success") {  // Dùng .trim() để loại bỏ khoảng trắng thừa
                    alert("Sản phẩm đã được thêm vào giỏ hàng.");
                } else {
                    alert("Không thể thêm sản phẩm vào giỏ hàng: " + xhr.responseText);
                }
            } else {
                alert("Có lỗi xảy ra: " + xhr.status);
            }
        }
    };

    // Gửi yêu cầu với dữ liệu product_id và quantity (1 mặc định)
    xhr.send("product_id=" + encodeURIComponent(productId) + "&quantity=1");
}

