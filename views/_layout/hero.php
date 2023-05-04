<?php

use lib\Auth\Auth;

?>

<div class="relative md:grid md:grid-cols-2 max-w-6xl mx-auto p-4">

    <div class="relative flex flex-col gap-6 md:py-16">

        <img src="/static/img/graph.svg" class="hidden md:block absolute bottom-0 left-0">

        <h1 class="
            relative text-6xl leading-[3rem] font-extrabold max-w-[12ch] py-4
            text-transparent bg-clip-text bg-gradient-to-r from-[#79EAD6] to-primary-50
        ">
            Master Your Money.
        </h1>

        <p class="relative max-w-[40ch] text-lg font-medium">
            Easily track and manage your expenses using CashFlo: the personal finance web app.
        </p>

        <div class="relative flex items-center gap-4">

            <?php if( Auth::is_logged_in() ): ?>

                <a href="/dashboard" class="btn-secondary-filled inline-flex items-center gap-2 ml-0 hover:no-underline">
                    <span class="material-icons-round">dashboard</span>
                    View Dashboard
                </a>
                
            <?php else: ?>
                    
                <a href="/signup" class="btn-secondary-filled inline-flex items-center gap-2 ml-0 hover:no-underline">
                    <span class="material-icons-round">person_add</span>
                    Sign Up
                </a>
                
            <?php endif ?>
                
            <a href="/about" class="inline-flex items-center gap-2 font-bold">
                Learn more
            </a>

        </div>
    </div>

    <div class="relative h-[60px]">

        <img src="/static/img/dieter.svg" class="absolute top-[-1000px] md:top-32 md:right-32 pointer-events-none">

        <img src="/static/img/hero2.png" class="
            absolute top-[-40px] right-[-40px] h-[200px] -translate-y-12 pointer-events-none
            md:relative md:top-0 md:right-0 md:h-auto md:translate-y-0
        ">
        
    </div>

    <div class="                absolute top-[40%] left-[10%] w-36 h-36 bg-teal-200 blur-3xl opacity-30 pointer-events-none "></div>
    <div class="                absolute top-[0%]  left-[40%] w-48 h-16 bg-teal-200 blur-3xl opacity-40 pointer-events-none "></div>
    <div class="hidden md:block absolute top-[60%] left-[80%] w-48 h-48 bg-teal-200 blur-3xl opacity-40 pointer-events-none "></div>
    
</div>

<img src="/static/img/logo.svg" class="absolute top-0 left-0 w-96 h-96 opacity-5 pointer-events-none">