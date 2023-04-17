<?php use lib\Auth\Auth;
use lib\Router\Route\Route;

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/static/css/build.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <title>
        {{title}} - CashFlo
    </title>
</head>
<body>

<div id="app" class="">

<header class="text-primary-50">

    <nav class="flex justify-between items-center max-w-6xl mx-auto p-4">


        <a href="/" class="inline-flex text-2xl font-bold">
            <span class="text-[#79EAD6]">Cash</span>Flo
            <img src="/static/img/logo.svg" >
        </a>

        <div class="flex items-center gap-2">

            <?php if( !Auth::is_logged_in() ): ?>
                <a href="/signup" class="btn-secondary-filled">Sign Up</a>
            <?php endif; ?>

            <button class="md:hidden hover:text-secondary-300">
                <span class="material-icons-round text-4xl ">menu</span>
            </button>

        </div>

        <ul class="hidden md:flex items-center gap-4 font-bold">

            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/signin">Sign In</a></li>

            <?php if( Auth::is_logged_in() ): ?>
                <li><a href="/dashboard" class="btn-secondary-filled">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/signup">Sign Up</a></li>
            <?php endif; ?>
        </ul>

    </nav>

    <?php if( Route::path() === '/' ) {
        include 'hero.php';
    } ?>
    
</header>


<?php if( Auth::is_logged_in() && ( Route::path() !== '/' ) ): ?>
    <div class="max-w-6xl mx-auto text-right px-6 py-0 text-primary-700 font-bold">
        Signed in as: <?= Auth::username(); ?>
    </div>
<?php endif; ?>

<div class="
    max-w-6xl mx-auto px-4 
    <?= Route::path() !== '/' ? 'grid grid-cols-[200px_1fr] gap-8 items-start' : 'pt-12'; ?>
">
    
    <?php if( Auth::is_logged_in() && ( Route::path() !== '/') ){
        include 'sidebar.php';
    } ?>

    <main>

        <h1 class="mb-6 pb-2 text-3xl text-primary-800 font-semibold border-b border-primary-100">{{h1}}</h1>