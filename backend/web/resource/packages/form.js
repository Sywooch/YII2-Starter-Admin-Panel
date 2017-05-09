/**
 * Created by theta-php on 17/2/17.
 */
(function($) {
    $(document).on('ready', function () {
        $('#add-service').on('click', function (e) {
            e.preventDefault();
            var Url     = $(this).attr('ajax-url')+'?index='+i;
            $.ajax({
                url:   Url,
                type:  'post',
                error: function (xhr, status, error) {
                    alert('There was an error with your request.'
                        + xhr.responseText);
                }
            }).done(function (data) {
                $(".services").append(data);
                i = parseInt(i)+1;
            });

        });
    });
})(jQuery);