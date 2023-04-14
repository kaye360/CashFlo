<?php
$data->title = 'Monthly Net Spending Trends';
$data->h1 = 'Monthly Net Spending Trends';
?>

<section class="flex flex-start items-center gap-6 my-4">

    Show: 
    
    <button class="netBtn font-bold">Net Total</button>
    <button class="incomeBtn font-bold">Income</button>
    <button class="spendingBtn font-bold">Spending</button>

</section>

<section class="flex flex-col gap-4">

    <?php foreach( $data->transactions->transactions_chunked_by_month as $month => $transactions ): ?>

        <div class="grid grid-cols-[9ch_9ch_1fr] gap-x-4 hover:bg-teal-50">

            <div class="font-bold text-lg">
                <?= (new DateTimeImmutable( $month ))->format('M Y'); ?> <br>
            </div>

            <div class="">
                <?= $data->transactions->monthly_net_totals[$month]; ?>
            </div>

            <div class="">

                <div class="grid grid-cols-[auto_auto] gap-x-2 w-min">

                    <div class="graph_net">
                        Net:
                    </div>

                    <div class="bar_graph graph_net border-l-2 border-slate-300 py-2">
                        <div class="bg-violet-300 h-8 w-[<?= abs($data->transactions->monthly_net_totals[$month]) /10; ?>px] "></div>
                    </div>

                    <div class="graph_income">
                        Income: 
                    </div>

                    <div class="bar_graph graph_income border-l-2 border-slate-300 py-2"">
                        <div class="bg-teal-300 h-8 w-[<?= abs($data->transactions->monthly_net_income[$month]) /10; ?>px] "></div>
                    </div>

                    <div class="graph_spending">
                        Spending: 
                    </div>

                    <div class="bar_graph graph_spending border-l-2 border-slate-300 py-2"">
                        <div class="bg-red-300 h-8 w-[<?= abs($data->transactions->monthly_net_spending[$month]) /10; ?>px] "></div>
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
    document.querySelectorAll('.graph_net').forEach( graph => graph.classList.toggle('hidden'))
})

const incomeBtn = document.querySelector('.incomeBtn')
incomeBtn.addEventListener('click', () =>
{
    incomeBtn.classList.toggle('font-bold')
    document.querySelectorAll('.graph_income').forEach( graph => graph.classList.toggle('hidden'))
})

const spendingBtn = document.querySelector('.spendingBtn')
spendingBtn.addEventListener('click', () =>
{
    spendingBtn.classList.toggle('font-bold')
    document.querySelectorAll('.graph_spending').forEach( graph => graph.classList.toggle('hidden'))
})

</script>


<style>

@keyframes bar-graph {
    from {
        scale: 0% 100%;
    }
    to {
        scale: 100% 100%;
    }
}

.bar_graph {
    transform-origin: left;
    animation : bar-graph 1000ms ease-in-out both;
}

</style>