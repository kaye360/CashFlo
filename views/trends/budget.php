<?php declare(strict_types=1); ?>

<a href="/trends/budgets" class="btn-back">
    <span class="material-icons-round">keyboard_backspace</span>
    Back to budgets
</a>

<!-- Bar Graph -->
<?php if ( $data->transactions->monthly_net_totals ): ?>

    <section id="bar-graph"
        class="
            relative flex items-center h-[230px] w-full max-w-[calc(100vw-50px)]
            overflow-x-auto snap-mandatory snap-x scrollbar-hidden 
            bg-gradient-to-r from-primary-700 to-primary-900
            rounded-lg text-primary-100
        "
    >

        <!-- X Axis Line -->
        <div id="x-axis-line" class="absolute top-[100px] left-0 h-[2px] z-10 bg-primary-500"></div>

        <?php foreach ( $data->transactions->monthly_net_totals as $month => $total ): ?>


            <?php $bar_height = (int) ($total * $data->monthly_ratio); ?>

            <div class="relative h-[100%] w-24 px-6 flex-shrink-0 snap-start">

                <!-- Date -->
                <div class="absolute bottom-0 left-0 right-0 min-w-max text-center">
                    <?= (new DateTimeImmutable( $month ))->format('M \'y'); ?>
                </div>

                <!-- Amount -->
                <div 
                    class="
                        absolute left-0 right-0 text-center text-xs
                        <?= $total >= 0
                            ? 'top-[105px]'
                            : 'bottom-[135px]';
                        ?>
                    "
                >
                    <?= $total >= 0 
                        ? "+$$total" 
                        : "-$" . abs((int) $total); 
                    ?>
                </div>

                <!-- Bar -->
                <div 
                    class="
                        absolute left-[0.75rem] right-[0.75rem] rounded-sm min-h-[5px] anim-bar-vertical
                        <?= $bar_height >= 0 
                            ? 'bottom-[128px] bg-gradient-to-b from-teal-600 to-teal-400'
                            : 'top-[100px]    bg-gradient-to-t from-rose-600  to-rose-400';  
                        ?>
                    "

                    style="height : <?= abs($bar_height) ?>px"
                ></div>
            </div>

        <?php endforeach; ?>
        
    </section>

<?php else: ?>

    <section class="flex flex-col gap-6">
        <p>
            This budget doesn't have any transactions yet. <a href="/transactions" class="underline">Add some</a>
        </p>
    </section>

<?php endif; ?>

<section class="mt-8">

    <?php foreach ( $data->transactions->transactions_chunked_by_month as $key => $month ): ?>

        <div class="my-8">

            <h3 class="flex items-center justify-between font-bold text-xl border-b border-slate-200 pb-1 mb-2 text-teal-800">

                <?= (new DateTimeImmutable( $key ))->format('F Y'); ?>

                <span class="text-md font-normal">
                    <?= $data->transactions->monthly_net_totals[ $key ] > 0 ? '+' : ''; ?>
                    <?= $data->transactions->monthly_net_totals[ $key ]; ?>
                </span>

            </h3>


            <div class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-2 items-end">

                <?php foreach ( $month as $transaction ): ?>

                    <span class="font-bold">
                        <?= $transaction->name; ?>
                    </span>

                    <span class="text-sm">
                        <?= $transaction->amount; ?>
                    </span>

                <?php endforeach; ?>

            </div>

        </div>

    <?php endforeach; ?>

</section>


<script>

function setbarGraphWidth() {
    const barGraphWidth = document.querySelector('#bar-graph').scrollWidth
    const xAxisLine = document.querySelector('#x-axis-line')
    xAxisLine.style.width = barGraphWidth + 'px'
}

window.addEventListener('DOMContentLoaded', setbarGraphWidth)
window.addEventListener('resize', setbarGraphWidth)


</script>