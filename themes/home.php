<?php $v->layout('_theme'); ?>
<div class="content">
    <?php $v->start('nav')?>
    <?php if (isset($user)) :
        ?>
        <div class="userName">
            <a class="navbar-brand" href="#">
                <img src="<?= userIcon($user->id, $user->avatar);?>" alt="user avatar"><span><?= $user->username?></span></a>
        </div>
        <ui><a class="navbar-brand" href=<?= url('logout') ?>>Logout</a></ui>
    <?php
    else :
        ?>
        <ui><a class="navbar-brand"  href=<?= url('login?action=login') ?>>Login</a></ui>
    <?php
    endif;
    $v->end();
    ?>
    <div class="container-fluid">
        <div class="servers">
            <?php foreach ($servers as $server):
                ?>

                <div class="server">
                    <img src="<?=$server->icon==''? url('themes/static/noicon.png'):$server->icon?>" alt="server icon" class="server-icon">
                    <h2><?=$server->name?></h2>
                    <span class="description"><?=$server->description?></span>
                    <div class="server-action">
                        <button type="button" class="btn btn-outline-primary"><?=$server->votes?>, Votar</button>
                        <button type="button" class="btn btn-outline-success"><?=$server->votes?>, Entrar</button>
                    </div>
                </div>

            <?php
            endforeach;
            ?>
        </div>
    </div>
</div>