<?php $v->layout('_theme');?>

<Edit content='<?= json_encode([
    "server" => $server->data,
    "rote" => url('config/'.$server->server_id)
])?>'>

</Edit>