<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 21/6/16
 * Time: 6:39 PM
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<div class="right_col" role="main" style="height: 100%;
    min-height: 928px;">
    <div class="">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit User</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">

            <?php echo $this->render("_form",['model'=>$model]);?>

            </div>
        </div>
    </div>
</div>

