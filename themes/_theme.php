<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">

    <meta name="author" content="Jovem_Blood" />
    <meta name="theme-color" content="#4e6c9d">
    <meta property="og:url" content="http://jchat.epizy.com">
    <meta property="og:type" content="website">
    <meta property="og:title" content="ServerList - Com certeza não é uma cópia do top.gg :)" />
    <meta property="og:description" content="Uma lista de servidores do discord baseada em votos, criada por um dev burro, onde o mais votado será o mais destacado.">
    <meta property="og:image" content="https://avatars3.githubusercontent.com/u/50130174?s=460&u=af94d1b1bce9a39e0272e66003fcb6835139fff1&v=4">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?= url('themes/dist/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= url('themes/dist/global.css') ?>">
    <link rel="shortcut icon" href="<?= url('themes/dist/images/favicon.ico') ?>" type="image/x-icon">
    <title><?= $title ?></title>

    <?= $v->section('styles') ?>
</head>


<body>
    <?php $v->insert('components/navbar', ['profile' => ($profile ?? false)]); // Navbar
    ?>

    <div id="app">
        <?= $v->section('content') ?>
    </div>


    <?= $v->insert('components/scripts') // Global Scripts 
    ?>
    <?= $v->section('scripts') ?>
</body>

</html>