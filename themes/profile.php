<?php $v->layout('_theme'); ?>

<div class="container-fluid">
    <ul class="list-group">
        <?php
        foreach ($guilds as $guild) : ?>
            <li class="list-group-item d-flex justify-content-between">
                <?= $guild->name;
                if ($guild->exists) : ?>
                    <a href="<?= url('config/' . $guild->id) ?>">
                        <button class="btn btn-sm btn-primary">Configs</button>
                    </a>
                <?php else : ?>
                    <a href="<?= addBot() ?>">
                        <button class="btn btn-sm btn-success">Add bot</button>
                    </a>
            </li>
    <?php
                endif;
            endforeach;
    ?>
    </ul>
</div>