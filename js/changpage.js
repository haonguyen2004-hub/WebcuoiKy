
    $(document).ready(function () {
        // Tự động tải nội dung "Nổi bật" khi trang vừa tải
        $.ajax({
            url: 'includes/fetch_products.php',
            type: 'GET',
            data: { filter: 'all' },
            success: function (response) {
                $('.featured__filter').html(response);
                $('.featured__controls ul li[data-filter="all"]').addClass('active');
            }
        });

        // Sự kiện click cho các tab
        $('.featured__controls ul li').on('click', function () {
            const filterType = $(this).data('filter');
            $('.featured__controls ul li').removeClass('active');
            $(this).addClass('active');

            // Thêm hiệu ứng fade
            $('.featured__filter').fadeOut(1000, function() {
                $.ajax({
                    url: 'includes/fetch_products.php',
                    type: 'GET',
                    data: { filter: filterType },
                    success: function (response) {
                        $('.featured__filter').html(response).fadeIn(500);
                    }
                });
            });
        });
    });
