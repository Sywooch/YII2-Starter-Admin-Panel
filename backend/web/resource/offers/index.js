/**
 * Created by theta-php on 17/2/17.
 */
(function($) {
    $(document).on('ready pjax:success', function () {
        $('.ajaxStatus').on('click', function (e) {
            e.preventDefault();
            var Url     = $(this).attr('status-url');
            var pjaxContainer = $(this).attr('pjax-container');
            $.ajax({
                url:   Url,
                type:  'post',
                error: function (xhr, status, error) {
                    alert('There was an error with your request.'
                        + xhr.responseText);
                }
            }).done(function (data) {
                $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
            });

        });
    });
})(jQuery);