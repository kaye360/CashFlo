<?php
$data->title = 'Budget Trends';
$data->h1 = 'Budget Trends';
?>

<section class="grid grid-cols-4 gap-8 text-lg">

    <div class="col-span-4">
        Select a budget to view spending over time
    </div>

    <?php foreach ($data->budgets as $budget): ?>

        <a href="/trends/budgets/<?= $budget->id; ?>" class="block py-8 text-center border border-slate-300 bg-slate-50 hover:border-teal-300 hover:bg-teal-50 rounded-xl">
            <?= $budget->name; ?>
        </a>

    <?php endforeach; ?>

</section>