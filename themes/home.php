<?php $v->layout('_theme');
$v->start('styles'); ?>
<style>
    .content {
        width: 100%;
        padding: 3px;
    }

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
                <img src="https://cdn.discordapp.com/icons/539596661891006502/04972d637ecdb6e2b2e99bfb913ef7ce.webp" alt="server icon" class="server-icon">
                <p>Bot-teste <span class="votes">53</span></p>
                <span class="description">servidor para testes top security do JB</span>
                <div class="server-action">
                    <a href="http://localhost/opis-increment/servers/539596661891006502/vote">
                        <button type="button" class="btn btn-outline-primary">Votar</button>
                    </a>
                    <a href="http://localhost/opis-increment/servers/539596661891006502">
                        <button type="button" class="btn btn-outline-success">Ver</button>
                    </a>
                </div>
            </div>
            <div class="server">
                <img src="https://cdn.discordapp.com/icons/537308107714330629/19fedc49e81314f9cbf4d1fe32c0bf1d.webp" alt="server icon" class="server-icon">
                <p>Lab do Game Over <span class="votes">42</span></p>
                <span class="description">área de testes do melhor servidor do mundo, quem sabe?</span>
                <div class="server-action">
                    <a href="http://localhost/opis-increment/servers/537308107714330629/vote">
                        <button type="button" class="btn btn-outline-primary">Votar</button>
                    </a>
                    <a href="http://localhost/opis-increment/servers/537308107714330629">
                        <button type="button" class="btn btn-outline-success">Ver</button>
                    </a>
                </div>
            </div>
            <div class="server">
                <img src="https://cdn.discordapp.com/icons/536563326767857683/78a73c1a8c1c0ccd07e66317e0268ffd.webp" alt="server icon" class="server-icon">
                <p>FOL3Y <span class="votes">9</span></p>
                <span class="description">Um servidor de um criador de conteúdo</span>
                <div class="server-action">
                    <a href="http://localhost/opis-increment/servers/536563326767857683/vote">
                        <button type="button" class="btn btn-outline-primary">Votar</button>
                    </a>
                    <a href="http://localhost/opis-increment/servers/536563326767857683">
                        <button type="button" class="btn btn-outline-success">Ver</button>
                    </a>
                </div>
            </div>
            <div class="server">
                <img src="https://cdn.discordapp.com/icons/400044059562868758/5fa1433d264acf7340a208e208432cc3.webp" alt="server icon" class="server-icon">
                <p>&#x1F47E; Game Over © &#x1F47E; <span class="votes">8</span></p>
                <span class="description">game over, o servidor mais legal que você vai ver no discord</span>
                <div class="server-action">
                    <a href="http://localhost/opis-increment/servers/400044059562868758/vote">
                        <button type="button" class="btn btn-outline-primary">Votar</button>
                    </a>
                    <a href="http://localhost/opis-increment/servers/400044059562868758">
                        <button type="button" class="btn btn-outline-success">Ver</button>
                    </a>
                </div>
            </div>
        </div>
    </aside>
</div>