<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/static/css/main.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>
        {{title}}
    </title>
</head>
<body>

<div id="app" class="grid grid-cols-[300px_1fr] p-4">

<nav class="flex flex-col gap-8">
    <div class="text-2xl">
        Spendly
    </div>

    <?php if( AUTH->is_logged_in() ): ?>
        <ul class="flex flex-col gap-2">
            <li class="font-bold"><?= AUTH->username(); ?>, id: <?= AUTH->user_id(); ?></li>
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/budgets">Budgets</a></li>
            <li><a href="/settings">Settings</a></li>
            <li><a href="/signout">Logout</a></li>
        </ul>
    <?php endif; ?>

    <ul class="flex flex-col gap-2">
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/error">Error</a></li>
    </ul>
</nav>

<main class="px-4">

<h1 class="mb-6 text-xl font-semibold">{{h1}}</h1>