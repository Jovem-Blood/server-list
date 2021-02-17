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
    <link rel="shortcut icon" href="<?= url('themes/dist/images/favicon.ico') ?>" type="image/x-icon">
    <title><?= $title ?></title>
    <style>
        body {
            padding: 0;
            margin: 0;
            font-family: Avenir, Helvetica, Arial, sans-serif;
            max-width: 100vw;
            max-height: 100vh;
            text-align: center;
        }

        .servers {
            display: flex;
            align-items: center;
            margin: auto;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }

        .server-icon {
            border-radius: 100%;
        }

        .votes {
            display: inline-flex;
            align-items: center;
            border: solid 1px #ccd6f8;
            border-radius: 2px;
            padding: 2px 10px;
            font: 600 16px "Karla", sans-serif;
            color: #0b1017;
        }

        .server {
            width: 283px;
            height: 320px;
            background: #d6d6d6;
            border-radius: 5%;
            padding: 1%;
            margin-top: 0.9%;
            display: flex;
            align-items: center;
            font-size: 1rem;
            justify-content: center;
            flex-direction: column;
            text-align: justify;
        }

        .content {

            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-align: center;
            color: #2c3e50;
            margin-top: 60px;
            justify-content: center;

        }

        .server-action {
            margin-top: auto;
        }

        .link {
            color: #fff;
            text-decoration: none;
            margin-left: 50px;
        }

        .userName {
            margin-left: auto;
            font-weight: 400;
        }

        .userName img {
            max-width: 50px;
            border-radius: 100%;
        }

        .userName a:first-child {
            align-items: center;
            display: flex;
            text-decoration: none;
            color: #fff;
        }

        .userName span {
            font-weight: 200;
        }

        .dropdown-menu a {
            color: black !important;
        }
    </style>

    <?= $v->section('styles') ?>
</head>


<body>
    <?php $v->insert('components/navbar', ['profile' => isset($profile) ? true : false]); // Navbar
    ?>

    <div id="app">
        <?= $v->section('content') ?>
    </div>


    <?= $v->insert('components/scripts') // Global Scripts 
    ?>
    <?= $v->section('scripts') ?>
</body>

</html>