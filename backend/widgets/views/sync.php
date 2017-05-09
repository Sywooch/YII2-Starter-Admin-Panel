<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 25/1/17
 * Time: 12:23 PM
 */
?>

<button class="btn btn-success btn-lg link" ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl(['site/get-controllers-and-actions-sync']) ?>">
    <span class="glyphicon glyphicon-flash"></span> Sync Controllers & Actions</button>

<?php
$this->registerJS("
    $(document).on('click', '.link', function(event){
    var sync = $(this);
    var AJAX_URL = $('.link').attr('ajax-url');
    sync.button('loading');       
    event.preventDefault();
        $.ajax({
            type: 'POST',
            url: AJAX_URL,
            success: function(data){
                sync.button('reset');
                location.reload();
            }
        });
    });
");
?>