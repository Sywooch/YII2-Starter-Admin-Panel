<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 22/2/17
 * Time: 5:23 PM
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">Manage <strong><?php echo ucfirst($controller);?></strong></div>
    <div class="list-group">
        <?php foreach ($menus as $key => $menu): ?>
            <a class="list-group-item <?= $menu['active'] ?>" href="<?= $menu['actionName'] ?>"><i
                        class="<?= $menu['icon'] ?>"></i><span> <?= $menu['slug'] ?> </span></a>
        <?php endforeach; ?>
    </div>
</div>
