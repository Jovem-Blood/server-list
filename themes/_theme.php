<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title><?= $title ?></title>
    <style>
        body {
            padding: 0;
            margin: 0;
            font-family: Avenir, Helvetica, Arial, sans-serif;
            max-width: 100vw;
            max-height: 100vh;

        }

        .servers {
            display: flex;
            align-items: center;
            margin: auto;
            flex-wrap: wrap;

        }

        .server-icon {
            border-radius: 100%;
        }

        .server {
            width: 277px;
            height: 320px;
            margin: 2%;
            background: #d6d6d6;
            border-radius: 5%;
            padding: 1%;
        }

        .content {

            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-align: center;
            color: #2c3e50;
            margin-top: 60px;
            justify-content: center;

        }



        .link {
            color: #fff;
            text-decoration: none;
            margin-left: 50px;
        }
        .userName img {
            width: 50px;
            border-radius: 100%;
        }
        .userName a {
            align-items: center;
            display: flex;
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-light bg-light h-25 navbar-dark bg-dark">
    <?= $v->section('nav') ?>
</nav>
    <?= $v->section('content')  ?>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</body>

</html>