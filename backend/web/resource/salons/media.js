/**
 * Created by theta-php on 21/2/17.
 */
(function($) {
    $(document).on('ready', function () {
        $('.delete').on('click', function (e) {
            var Url     = $(this).attr('ajax-url');
            bootbox.confirm("<h3 class='text-center'>Are you sure you want to delete?</h3>", function(result){
                e.preventDefault();
                var dialog ='';
                $.ajax({
                    url:   Url,
                    type:  'post',
                    beforeSend: function(){
                        // Handle the beforeSend event
                        dialog = bootbox.dialog({
                            message: '<p class="text-center">Please wait while we do something...</p>',
                            closeButton: false
                        });
                    },
                    error: function (xhr, status, error) {
                        alert('There was an error with your request.'
                            + xhr.responseText);
                    }
                }).done(function (data) {
                    dialog.modal('hide');
                    if(data==true){
                        window.location.reload();
                    }

                });
            });
        });
    });
})(jQuery);