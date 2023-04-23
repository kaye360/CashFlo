<?php use lib\Auth\Auth; ?>



<div class="text-sm">

    <div class="flex items-center gap-2 py-2">

        <span>
            <?php if( $data->page !== 1 ): ?>
                <a href="/transactions/<?= $data->page - 1; ?>" class="">
                    Previous
                </a>
            <?php else: ?>
                <span>
                    Previous
                </span>
            <?php endif; ?>
        </span>
            
        <?php for($i = 1; $i <= $data->total_pages; $i++): ?>
                
            <a 
                href="/transactions/<?= $i; ?>" 
                class="block  <?= $i === $data->page ? 'font-bold' : '' ?>"
            >
                <?= $i; ?>
            </a>
        <?php endfor; ?>
        
        <span>
            <?php if( $data->page !== $data->total_pages ): ?>
                <a href="/transactions/<?= $data->page + 1; ?>" class="">
                    Next
                </a>
            <?php else: ?>
                <span class="">
                    Next
                </span>
            <?php endif; ?>
                
        </span>

    </div>

    <div class="flex items-center gap-2">
        Showing 
        <a 
            href="/settings/transactions_per_page/10" 
            class="
            <?= Auth::settings()->transactions_per_page === 10 ? 'font-bold' : '' ?>"
        >
            10
        </a>
        <a 
            href="/settings/transactions_per_page/25" 
            class="
            <?= Auth::settings()->transactions_per_page === 25 ? 'font-bold' : '' ?>"
        >
            25
        </a>
        <a 
            href="/settings/transactions_per_page/50" 
            class="
            <?= Auth::settings()->transactions_per_page === 50 ? 'font-bold' : '' ?>"
        >
            50
        </a>
        <a 
            href="/settings/transactions_per_page/100" 
            class="
            <?= Auth::settings()->transactions_per_page === 100 ? 'font-bold' : '' ?>"
        >
            100
        </a>
        per page
    </div>

</div>