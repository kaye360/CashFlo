
<option value="none">None</option>
<?php foreach( @$data->budgets as $budget ) : ?>
    <option 
        value="<?= $budget->name; ?>" 
        <?= @$budget->name === @$data->transactions[$i]->transaction->budget ? 'selected' : ''; ?>
    >
        <?= $budget->name; ?>
    </option>
<?php endforeach; ?>
