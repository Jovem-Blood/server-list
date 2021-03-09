<?php $v->layout('_theme');
$content = json_encode([
    "server" => $server,
    "allTags" => $allTags,
    "rote" => url('config/' . $server->server_id),
    "csrf" => csrfGenerate()
]);
?>

<Edit content='<?= $content ?>'>
</Edit>