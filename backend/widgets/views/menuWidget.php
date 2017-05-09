<ul class="nav">
    <?php foreach ($menuArr as $key => $menu): ?>
        <li class="visible-md visible-lg <?=$menu['active'];?>">
            <a class="<?=$menu['active'];?>" href="<?= $menu['url'] ?>">
                <i class="<?= $menu['icon'] ?>"></i><br/><?= $menu['slug'] ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>