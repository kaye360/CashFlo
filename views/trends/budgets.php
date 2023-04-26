<?php
$data->title = 'Budget Trends';
$data->h1 = 'Budget Trends';
?>

<div class="my-4">
    <a href="/trends" class="underline">
        Back to Trends
    </a>
</div>

<div class="my-4">
    Select a budget to view spending over time
</div>

<section class="text-lg w-fit">

    <?php foreach ($data->budgets as $budget): ?>

        <div class="grid grid-cols-[15ch_1fr] gap-4 my-4 border-b-2 border-primary-50">
            
            <a href="/trends/budgets/<?= $budget->id; ?>" class="font-bold">
                <?= $budget->name ?>
            </a>
            
            <span class="text-primary-400">
                <?= $budget->type === 'income' ? '+' : '-' ?>$<?= $budget->amount ?>
            </span>

        </div>
        
    <?php endforeach; ?>

</section>