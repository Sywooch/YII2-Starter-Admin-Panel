<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="col-md-12">
    <div class="col-middle">
        <div class="text-center text-center">
            <?php if($error->statusCode == 404){ ?>
                <h1 class="error-number">404</h1>
                <h2>Sorry but we couldnt find this page</h2>
                <p>This page you are looking for does not exist <a href="#">Report this?</a>
                </p>

            <?php } else if($error->statusCode == 403){?>
                <h1 class="error-number">403</h1>
                <h2>Unauthorized Access</h2>
                <p>you are unauthorized for this page, but if you this it is problem then feel free to contact us. In the meantime, try refreshing.
                </p>
             <?php }else if($error->statusCode == 500){?>
                <h1 class="error-number">500</h1>
                <h2>Internal Server Error</h2>
                <p>We track these errors automatically, but if the problem persists feel free to contact us. In the meantime, try refreshing.
                </p>
            <?php } else {?>

                <h1 class="error-number">500</h1>
                <h2>Internal Server Error</h2>
                <p>We track these errors automatically, but if the problem persists feel free to contact us. In the meantime, try refreshing.
                </p>

             <?php  }?>



        </div>
    </div>
</div>