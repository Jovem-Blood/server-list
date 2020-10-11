<?php $v->layout('_theme');?>

<div class="container-fluid">
    <ul class="list-group">
        <?php
        foreach ($guilds as $guild):
            if($guild->permissions == '2147483647'):?>
                <li class="list-group-item d-flex justify-content-between"><?=$guild->name;
                if(in_array($guild->id, $servers)): ?>
                    <a href="#configs">
                        <button class="btn btn-sm btn-primary">Configs</button>
                    </a>
                <?php else: ?>
                    <a href="<?=addBot()?>">
                        <button class="btn btn-sm btn-success">Add bot</button>
                    </a>
                    </li>
                <?php
                endif;
            endif;
        endforeach; ?>
    </ul>
</div>