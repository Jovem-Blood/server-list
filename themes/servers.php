<?php $v->layout('_theme');
$v->start('styles')?>
<style>
    .server-name {
        margin-top: 1rem;
    }
    .server-info {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .description {
        max-width: 15rem;
        text-align: justify;
    }
</style>
<?php $v->end()?>

<h1 class="server-name"><?= $server->name?></h1>
<div class="container">
    <Timer description="Tempo Restante" enter-time="2020-10-19T16:37:30-03:00"></Timer>
    <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?= url('servers/' . $server->server_id . '/vote')?>">
            <button type="button" class="btn btn-success">Votar</button>
        </a>
        <a target="_blank" href="<?= url('servers/' . $server->server_id . '/join')?>">
            <button type="button" class="btn btn-success">Entrar</button>
        </a>
    </div>
    <div class="server-info">
        <div class="image">
            <img src="<?=$server->icon==''? url('themes/dist/images/noicon.png'):$server->icon.'?size=512'?>" alt="server icon" width="260" class="shadow">
        </div>
        <div class="description">
            <span class="badge badge-secondary"><?=$server->votes?> Votos</span>
            <p><?= $server->description?></p>
        </div>
        <div class="counters">
            <div class="btn btn-primary"><?= number_format($server->usersCount, '0','.',',')?> Membros</div>
            <div class="btn btn-primary"><?= number_format($server->emoteCount, '0','.',',')?> Emotes</div>
        </div>
    </div>
</div>

