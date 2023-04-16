<?php use lib\Auth\Auth; ?>

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
        {{title}} - CashFlow
    </title>
</head>
<body>

<div id="app" class="">

<header>

    <nav class="flex justify-between items-center max-w-6xl mx-auto p-4">
        <div class="text-xl font-bold text-primary-50">
            <span class="text-[#79EAD6]">Cash</span>Flow
        </div>

        <button class="md:hidden text-primary-50 hover:text-secondary-300">
            <span class="material-icons-round text-4xl ">menu</span>
        </button>

        <ul class="flex items-center gap-4 text-primary-50 font-bold">

            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>

            <?php if( Auth::is_logged_in() ): ?>
                <li><a href="/dashboard" class="btn-secondary-filled">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/signup">Sign Up</a></li>
            <?php endif; ?>
        </ul>

    </nav>
    
</header>

<?php if( Auth::is_logged_in() ): ?>
    <div class="max-w-6xl text-right px-6 py-0 text-primary-700 font-bold">
        Signed in as: <?= Auth::username(); ?>
    </div>
<?php endif; ?>

<main class="px-4 py-8">
    
    <div class="sidebar">
        <?php if( Auth::is_logged_in() ): ?>
            <ul class="flex flex-col gap-4">
                <li class="font-bold"><?= Auth::username(); ?>, id: <?= Auth::user_id(); ?></li>
                <li><a href="/dashboard" class="hover:underline">Dashboard</a></li>
                <li><a href="/budgets" class="hover:underline">Budgets</a></li>
                <li><a href="/transactions" class="hover:underline">Transactions</a></li>
                <li><a href="/trends" class="hover:underline">Trends</a></li>
                <li><a href="/settings" class="hover:underline">Settings</a></li>
                <li><a href="/signout" class="hover:underline">Logout</a></li>
            </ul>
        <?php endif; ?>
    </div>

<h1 class="mb-6 pb-2 text-3xl font-semibold border-b border-slate-200">{{h1}}</h1>