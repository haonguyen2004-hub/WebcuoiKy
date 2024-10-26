document.addEventListener("DOMContentLoaded", () => {
    const filterButtons = document.querySelectorAll('.featured__controls li');
    const productItems = document.querySelectorAll('.featured__filter .mix');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Xóa lớp "active" khỏi tất cả các nút và thêm vào nút được chọn
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Lấy giá trị filter từ nút
            const filterValue = button.getAttribute('data-filter');

            // Lọc sản phẩm dựa vào lớp của sản phẩm
            productItems.forEach(item => {
                if (filterValue === '*' || item.classList.contains(filterValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
