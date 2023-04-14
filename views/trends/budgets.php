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

<section class="grid grid-cols-5 gap-8 text-lg">

    <?php foreach ($data->budgets as $budget): ?>

        <a href="/trends/budgets/<?= $budget->id; ?>" class="block py-8 text-center border border-slate-300 bg-slate-50 hover:border-teal-300 hover:bg-teal-50 rounded-xl">
            <?= $budget->name; ?>
        </a>

    <?php endforeach; ?>

</section>