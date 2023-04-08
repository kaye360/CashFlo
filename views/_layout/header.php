<?php use lib\Auth\Auth; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/static/css/main.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>
        {{title}} - CashFlow
    </title>
</head>
<body>

<div id="app" class="grid grid-cols-[auto_1fr] p-4 min-h-screen">

<nav class="flex flex-col gap-8 bg-teal-50 text-teal-900 text-xl py-8 pl-8 pr-16 rounded-lg">
    <div class="text-2xl font-black text-teal-700">
        <span class="text-teal-400">Cash</span>Flow
    </div>

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

    <ul class="flex flex-col gap-2">
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/error">Error</a></li>
    </ul>
</nav>

<main class="px-4 py-8">

<h1 class="mb-6 pb-2 text-3xl font-semibold border-b border-slate-200">{{h1}}</h1>