<section class="flex items-center justify-between">

    <div class="flex items-center justify-center gap-4 text-lg py-4">

        <span>
            <?php

use lib\Auth\Auth;

 if( $data->page !== 1 ): ?>
                <a href="/transactions/<?= $data->page - 1; ?>" class="block px-4 py-2 hover:bg-teal-200 rounded-lg">
                    Previous
                </a>
            <?php endif; ?>
        </span>
            
        <?php for($i = 1; $i <= $data->total_pages; $i++): ?>
                
            <a 
                href="/transactions/<?= $i; ?>" 
                class="block px-4 py-2 hover:bg-teal-200 rounded-lg
                        <?= $i === $data->page ? 'font-bold border border-teal-200' : '' ?>"
            >
                <?= $i; ?>
            </a>
        <?php endfor; ?>
        
        <span>
            <?php if( $data->page !== $data->total_pages ): ?>
                <a href="/transactions/<?= $data->page + 1; ?>" class="block px-4 py-2 hover:bg-teal-200 rounded-lg">
                    Next
                </a>
            <?php endif; ?>
        </span>

    </div>

    <div>
        Showing 
        <a 
            href="/settings/transactions_per_page/10" 
            class="inline-block p-2 border border-transparent hover:border-teal-500 text-teal-600 rounded-lg
            <?= Auth::settings()->transactions_per_page === 10 ? 'font-bold' : '' ?>"
        >
            10
        </a>
        <a 
            href="/settings/transactions_per_page/25" 
            class="inline-block p-2 border border-transparent hover:border-teal-500 text-teal-600 rounded-lg
            <?= Auth::settings()->transactions_per_page === 25 ? 'font-bold' : '' ?>"
        >
            25
        </a>
        <a 
            href="/settings/transactions_per_page/50" 
            class="inline-block p-2 border border-transparent hover:border-teal-500 text-teal-600 rounded-lg
            <?= Auth::settings()->transactions_per_page === 50 ? 'font-bold' : '' ?>"
        >
            50
        </a>
        <a 
            href="/settings/transactions_per_page/100" 
            class="inline-block p-2 border border-transparent hover:border-teal-500 text-teal-600 rounded-lg
            <?= Auth::settings()->transactions_per_page === 100 ? 'font-bold' : '' ?>"
        >
            100
        </a>
        per page
    </div>

</section>