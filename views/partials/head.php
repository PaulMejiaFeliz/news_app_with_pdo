<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>News App<?= isset($title)? " - $title" : "" ?></title>
    <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/public/css/site.css">
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">News App</a>
            </div>
            <ul class="nav navbar-nav">
                <?php if(isset($_SESSION['logged'])) : ?>
                <li><a href="/newPost">New Post</a></li>
                <li><a href="/myPosts">My Posts</a></li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php if(isset($_SESSION['logged'])) : ?>
                <li><a><?= "{$_SESSION['user']['name']} {$_SESSION['user']['lastName']}"; ?></a></li>
                <li><a href="/logout">Logout</a></li>
                <?php else : ?>
                <li><a href="/register">Register</a></li>
                <li><a href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class='container'>