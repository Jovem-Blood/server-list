<?php $v->layout('_theme');
$v->start('styles');
?>

<style>
    h2 {
        margin-top: 2%;
        margin-bottom: 2%;
    }

    article {
        padding-bottom: 3%;
    }
</style>

<?php $v->end(); ?>
<div class="container-fluid text-left">
    <h2>Meus Servidores</h2>
    <article>

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
    </article>
    <h2>Notificação</h2>
    <article>
        <p>
            Notificações, são úteis quando queremos certo? saber se você já pode votar novamente no seu servidor favorito deve lhe poupar tempo,
            se é isso que você deseja simplesmente clique no botão <span class="text-success">Verde</span> abaixo,
            Caso você não queria mais receber notificações basta clicar no botão <span class="text-danger">Vermelho</span>
        </p>
        <p>
            Um aviso que deve ser considerado é que existem problemas de compatibilidade entre os navegadores, portanto é possível que você
            não receba as notificações.
        </p>
        <div>
            <button type="button" class="btn btn-success" onclick="createInstance">Quero receber!</button>
            <button type="button" class="btn btn-danger" onclick="deleteInstance">Me arrependi!</button>
        </div>
    </article>
    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
    <script>
        const beamsClient = new PusherPushNotifications.Client({
            instanceId: "4f5d12e3-ffcb-48ad-91e5-c37dc4a4af00",
        });
        const tokenProvider = new PusherPushNotifications.TokenProvider({
            url: "<?= url("pusher/beams-auth") ?>",
            queryParams: {
                user_id: "<?= $userId ?>", // URL query params your auth endpoint needs
            }
        });
        function  createInstance() {

        }
        function deleteInstance() {

        }
    </script>
</div>