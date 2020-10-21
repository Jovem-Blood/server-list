<?php $v->layout('_theme'); ?>

<div class="content">
    <?php if($canVote): ?>
        <h1>Obrigado por votar no <?=$serverName?></h1>
        <h1>Atualmente com <?=$votes?></h1>
    <?php else: ?>
        <h1>Você não pode votar ainda...</h1>
    <?php endif; ?>
    <Timer enter-time="<?=$timer?>" description="Tempo Restante"></Timer>
</div>

<a href="<?=url()?>">
    <button class="btn btn-lg btn-primary">Voltar</button>
</a>
