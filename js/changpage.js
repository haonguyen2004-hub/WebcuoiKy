$(document).ready(function () {
    $.ajax({
        url: 'includes/fetch_products.php',
        type: 'GET',
        data: { filter: 'all' },
        success: function (response) {
            $('.featured__filter').html(response);
            $('.featured__controls ul li[data-filter="all"]').addClass('active');
        }
    });

    $('.featured__controls ul li').on('click', function () {
        const filterType = $(this).data('filter');
        $('.featured__controls ul li').removeClass('active');
        $(this).addClass('active');

        $('.featured__filter').animate({ left: '-100%', opacity: 0 }, 500, function() {
            $.ajax({
                url: 'includes/fetch_products.php',
                type: 'GET',
                data: { filter: filterType },
                success: function (response) {
                    $('.featured__filter')
                        .css({ left: '100%', opacity: 0 })
                        .html(response)
                        .animate({ left: '0%', opacity: 1 }, 500);
                }
            });
        });
    });
});
