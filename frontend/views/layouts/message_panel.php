<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 20/6/16
 * Time: 1:46 AM
 */
//use Yii;
use yii\helpers\Html;
use kartik\growl\Growl;
?>
<?php

 foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
     <div class="alert <?php echo (!empty($message['type'])) ? $message['type'] : 'alert-danger'?> alert-dismissible" role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <strong><?php echo (!empty($message['title'])) ? $message['title'] : 'Message Not Set!'?></strong> <?php echo (!empty($message['message'])) ? $message['message'] : 'Message Not Set!'?> :
     </div>
<?php endforeach; ?>



