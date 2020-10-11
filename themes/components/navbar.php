<?php ?>

<nav class="navbar navbar-expand-lg h-25 navbar-dark bg-dark">
    <a class="navbar-brand" href="<?=url();?>">Home</a>
    <?php if (isset($user)):?>
        <div class="userName dropdown">
            <a class="dropdown-toggle"  role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?= userIcon($user->id, $user->avatar);?>" alt="user avatar"><span style="font-weight: bold"><?= $user->username?></span></a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href=<?= url('logout') ?>>Logout</a>
                <a class="dropdown-item <?=$profile==true?'disabled':''?>" href=<?= url('profile') ?>>Perfil</a>
            </div>
        </div>
    <?php else: ?>
        <a class="navbar-brand" style="margin-left: auto" href=<?= url('login') ?>>Login</a>
    <?php endif; ?>
</nav>