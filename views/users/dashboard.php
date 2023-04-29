<?php
use lib\Auth\Auth;
$data->title = 'Dashboard';
$data->h1 = 'Dashboard: ' . Auth::username();
$data->username = Auth::username();
?>


<div class="grid grid-cols-2 md:grid-cols-[9ch_9ch_1fr] gap-4 p-4 rounded-lg odd:bg-primary-50 overflow-x-auto">

    <div class="font-bold text-lg">
        <?= date('M Y') ?> <br>
    </div>

    <div class="text-right md:text-left">
        Net: <?= $data->current_month_net_total ?>
    </div>

    <div class="col-span-2 md:col-span-1">

        <div class="grid grid-cols-[auto_auto] gap-x-2 w-min">

            <div data-graph="net">
                Net:
            </div>

            <div 
                data-graph="net" 
                class="border-l-2 border-slate-300 py-2"
            >
                <div 
                    class="bg-gradient-to-r from-violet-500 to-violet-300 h-8 rounded-r-lg drop-shadow-md anim-bar-horizontal"
                    style="width: <?= abs($data->current_month_net_total) / 10 ?>px"
                >
                </div>
                        
            </div>

            <div data-graph="income">
                Income: 
            </div>

            <div 
                data-graph="income"
                class="border-l-2 border-slate-300 py-2"
            >
                <div 
                    class="bg-gradient-to-r from-teal-500 to-teal-300 h-8 rounded-r-lg drop-shadow-md anim-bar-horizontal"
                    style="width: <?= abs($data->current_month_net_income) / 10 ?>px"
                ></div>
            </div>

            <div data-graph="spending">
                Spending: 
            </div>

            <div 
                data-graph="spending"
                class="border-l-2 border-slate-300 py-2"
            >
                <div 
                    class="bg-gradient-to-r from-rose-500 to-rose-300 h-8 rounded-r-lg drop-shadow-md anim-bar-horizontal"
                    style="width: <?= abs($data->current_month_net_spending) / 10 ?>px"
                ></div>
            </div>
    
        </div>
        
    </div>    

</div>

<ul>
    <li>Recent transactions</li>
    <li>Budgets</li>
    <li>Current month net spending</li>
    <li></li>
    <li></li>
</ul>

<p>
    This page is protected
</p>

<p>
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi repudiandae odit tenetur doloribus facilis quod aspernatur earum, magnam ipsa amet!
</p>
