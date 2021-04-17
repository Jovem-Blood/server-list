<?php $v->layout('_theme');
$v->start('styles'); ?>
<style>
    .section-title {
        display: flex;
        align-items: center;
    }

    .section-title img {
        width: 40px;
    }
</style>
<?php $v->end(); ?>

<div class="content">
    <div class="section-title">
        <img src="<?= url('themes/dist/images/ranking.png') ?>" alt="Primeiro lugar">
        <h4>Top Voted Servers</h4>
    </div>
    <main class="servers">
        <?php foreach ($servers as $server) : ?>
            <div class="server">
                <img src="<?= $server->icon == '' ? url('themes/dist/images/noicon.png') : $server->icon ?>" alt="server icon" class="server-icon">
                <p><?= $server->name ?> <span class="votes"><?= $server->votes ?></span></p>
                <span class="description"><?= $server->description ?></span>
                <div class="server-action">
                    <a href="<?= url('servers/' . $server->server_id . '/vote') ?>">
                        <button type="button" class="btn btn-outline-primary">Votar</button>
                    </a>
                    <a href="<?= url('servers/' . $server->server_id) ?>">
                        <button type="button" class="btn btn-outline-success">Ver</button>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
    <hr>
    <aside>
        <div class="section-title">
            <img src="<?= url('themes/dist/images/ranking.png') ?>" alt="Primeiro lugar">
            <h4>Coming Soon</h4>
        </div>
        <div class='servers'>
            <div class="server">
            </div>
            <div class="server">
            </div>
            <div class="server">
            </div>
            <div class="server">
            </div>
        </div>
    </aside>
</div>