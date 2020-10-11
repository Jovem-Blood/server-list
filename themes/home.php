<?php $v->layout('_theme'); ?>
<div class="content">

    <div class="container">
        <div class="servers">
            <?php foreach ($servers as $server): ?>

                <div class="server">
                    <img src="<?=$server->icon==''? url('themes/static/noicon.png'):$server->icon?>" alt="server icon" class="server-icon">
                    <p><?=$server->name?> <span class="votes"><?=$server->votes?></span></p>
                    <span class="description"><?=$server->description?></span>
                    <div class="server-action">
                        <button type="button" class="btn btn-outline-primary">Votar</button>
                        <a href="<?=url('servers/' . $server->server_id)?>"><button type="button" class="btn btn-outline-success">Ver</button></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>