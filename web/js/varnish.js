$(function () {
    $('.varnish-link').on('change', function (event) {
        let isChecked = $(this).prop('checked') ? 1 : 0;
        let varnishId = $(this).data('varnish');
        let websiteId = $(this).data('website');

        $.ajax({
            url: '/link',
            method: 'post',
            dataType: 'json',
            data: {
                link: isChecked,
                varnishId: varnishId,
                websiteId: websiteId
            }
        });
    });
});