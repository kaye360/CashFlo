<?php
use lib\Auth\Auth;
$data->title = 'Dashboard';
$data->h1 = 'Dashboard: ' . Auth::username();
$data->username = Auth::username();
?>


<section class="grid grid-cols-2 md:grid-cols-[9ch_1fr] gap-4 items-center p-4 rounded-lg bg-gradient-to-r from-primary-700 to-primary-900 text-primary-100 overflow-x-auto">
    
    <h2 class="col-span-2 text-xl font-bold">
        Current Month
    </h2>

    <div class="font-bold text-lg">
        <?= date('M Y') ?> <br>
        <?= $data->current_month_net_total > 0 ? '+$' : '-$' ?><?= abs( $data->current_month_net_total ) ?>
    </div>

    <div class="col-span-2 md:col-span-1">

        <div class="grid grid-cols-[10ch_10ch_auto] gap-x-2 items-center w-full">

            <div data-graph="net">
                Net:
            </div>

            <div>
                <?= $data->current_month_net_total > 0 ? '+$' : '-$' ?><?= abs( $data->current_month_net_total ) ?>
            </div>

            <div 
                data-graph="net" 
                class="border-l-2 border-primary-600 py-2"
            >
                <div 
                    class="bg-gradient-to-r from-violet-500 to-violet-300 h-8 rounded-r-lg drop-shadow-md anim-bar-horizontal"
                    style="width: <?= abs($data->current_month_net_total) * $data->ratio ?>%"
                >
                </div>
                        
            </div>

            <div data-graph="income">
                Income: 
            </div>

            <div>
                +$<?= $data->current_month_net_income ?>
            </div>

            <div 
                data-graph="income"
                class="border-l-2 border-slate-300 py-2"
            >
                <div 
                    class="bg-gradient-to-r from-teal-500 to-teal-300 h-8 rounded-r-lg drop-shadow-md anim-bar-horizontal"
                    style="width: <?= abs($data->current_month_net_income) * $data->ratio ?>%"
                ></div>
            </div>

            <div data-graph="spending">
                Spending: 
            </div>

            <div>
                -$<?= abs( $data->current_month_net_spending ) ?>
            </div>

            <div 
                data-graph="spending"
                class="border-l-2 border-slate-300 py-2"
            >
                <div 
                    class="bg-gradient-to-r from-rose-500 to-rose-300 h-8 rounded-r-lg drop-shadow-md anim-bar-horizontal"
                    style="width: <?= abs($data->current_month_net_spending) * $data->ratio ?>%"
                ></div>
            </div>
    
        </div>
        
    </div>    

</section>



<section class="md:grid md:grid-cols-2 gap-8 mt-8">

    <div>
        <h2 class="col-span-2 text-xl font-bold py-2">Recent Transactions</h2>

        <?php if( empty( $data->recent_transactions ) ): ?>

            <div class="">
                No recent transactions
            </div>

        <?php else: ?>

            <table class="w-full">
                
                <?php foreach( $data->recent_transactions as $transaction ): ?>

                    <tr class="odd:bg-primary-50 rounded-md">
                        <td class="p-2 font-bold">
                            <?= $transaction->name ?>
                        </td>

                        <td class="p-2">
                            <?= $transaction->type === 'spending' ? '-$' : '+$' ?><?= $transaction->amount ?>
                        </td>
                    </tr>
                    
                <?php endforeach ?>
            </table>
            
        <?php endif; ?>

        <a href="/transactions" class="btn-primary-outlined my-8">View Transactions</a>
    </div>


    <div>
        <h2 class="col-span-2 text-xl font-bold py-2">Your Budgets</h2>

        <?php if( empty( $data->budgets ) ): ?>

            <div class="">
                No budgets to show.
            </div>

        <?php else: ?>

            <table class="w-full">
                
                <?php foreach( $data->budgets as $budget ): ?>

                    <tr class="even:bg-primary-50 rounded-md">
                        <td class="p-2 font-bold">
                            <?= $budget->name ?>
                        </td>

                        <td class="p-2">
                            <?= $budget->type === 'spending' ? '-$' : '+$' ?><?= $budget->amount ?>
                        </td>
                    </tr>
                    
                <?php endforeach ?>
            </table>
            
        <?php endif; ?>

        <a href="/budgets" class="btn-primary-outlined my-8">View Budgets</a>
    </div>

</section>

