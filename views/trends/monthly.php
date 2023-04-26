<?php
$data->title = 'Monthly Net Spending Trends';
$data->h1 = 'Monthly Net Spending Trends';
?>

<section class="flex flex-start items-center gap-2 my-4 text-sm">

    Showing: 
    
    <button class="netBtn      font-bold rounded-lg px-2 py-1 border border-slate-300">Net Total</button>
    <button class="incomeBtn   font-bold rounded-lg px-2 py-1 border border-slate-300">Income</button>
    <button class="spendingBtn font-bold rounded-lg px-2 py-1 border border-slate-300">Spending</button>

</section>

<section class="flex flex-col gap-4">

    <?php foreach( $data->transactions->transactions_chunked_by_month as $month => $transactions ): ?>

        <div class="grid grid-cols-2 md:grid-cols-[9ch_9ch_1fr] gap-4 p-4 rounded-lg odd:bg-primary-50 overflow-x-auto">

            <div class="font-bold text-lg">
                <?= (new DateTimeImmutable( $month ))->format('M Y'); ?> <br>
            </div>

            <div class="text-right md:text-left">
                <?= $data->transactions->monthly_net_totals[$month]; ?>
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
                            style="width: <?= abs($data->transactions->monthly_net_totals[$month]) /10; ?>px"
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
                            style="width: <?= abs($data->transactions->monthly_net_income[$month]) /10; ?>px"
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
                            style="width: <?= abs($data->transactions->monthly_net_spending[$month]) /10; ?>px"
                        ></div>
                    </div>
            
                </div>
                
            </div>    

        </div>

    <?php endforeach; ?>

</section>


<script>

const netBtn = document.querySelector('.netBtn')
netBtn.addEventListener('click', () =>
{
    netBtn.classList.toggle('font-bold')
    netBtn.classList.toggle('border')
    document.querySelectorAll('[data-graph="net"]').forEach( graph => graph.classList.toggle('hidden'))
})

const incomeBtn = document.querySelector('.incomeBtn')
incomeBtn.addEventListener('click', () =>
{
    incomeBtn.classList.toggle('font-bold')
    incomeBtn.classList.toggle('border')
    document.querySelectorAll('[data-graph="income"]').forEach( graph => graph.classList.toggle('hidden'))
})

const spendingBtn = document.querySelector('.spendingBtn')
spendingBtn.addEventListener('click', () =>
{
    spendingBtn.classList.toggle('font-bold')
    spendingBtn.classList.toggle('border')
    document.querySelectorAll('[data-graph="spending"]').forEach( graph => graph.classList.toggle('hidden'))
})

</script>
