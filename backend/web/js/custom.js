/**
 * Created by theta-php on 28/2/17.
 */
$(document).ready(function () {
    //time picker for
    $('.time-picker').timepicker({
        timeFormat: 'h:mm p',
        interval: 15,
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    //making everything disabled
    $(".disabled").click(function (e) {
        e.preventDefault();
        return false;
    })
    //fancybox
    $("[data-fancybox]").fancybox({
        // Options will go here
    });

});