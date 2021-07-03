<nav class="navbar navbar-expand-lg h-25 navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= url(); ?>">Home</a>
    <form action="<?= url("search/"); ?>" method="GET" class="form-inline my-2 my-lg-0">
        <div class="input-group">
            <input class="form-control" type="search" name="q" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
                <button type="submit" class="input-group-text" id="basic-addon1">Search</button>
            </div>
        </div>
    </form>
    <?php if (isset($user)) : ?>
        <div class="userName dropdown">
            <a class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?= $user->getAvatar(); ?>" alt="user avatar"><span style="font-weight: bold"><?= $user->getName() ?></span></a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item <?= $profile == true ? 'disabled' : '' ?>" href="<?= url('profile') ?>">Perfil</a>
                <a class="dropdown-item" href="<?= url('logout') ?>">Logout</a>
            </div>
        </div>
    <?php else : ?>
        <a class="navbar-brand" style="margin-left: auto" href="<?= url('login') ?>">Login</a>
    <?php endif; ?>
</nav>