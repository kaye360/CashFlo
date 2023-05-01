<?php
$data->h1 = 'Add Multiple Transactions';
$data->title = 'Add multiple transactions';
?>

<a href="/transactions" class="btn-back">
    <span class="material-icons-round">keyboard_backspace</span>
    Back to Transactions
</a>


<form method="post" action="/transactions/add-multiple">
    <table class="w-full text-left">

        <thead>
            <tr>
                <th class="pl-2">#</th>
                <th class="pl-2">Name</th>
                <th class="pl-2">Amount</th>
                <th class="pl-2">Type</th>
                <th class="pl-2">Budget</th>
                <th class="pl-2">Date</th>
            </tr>
        </thead>

        <tbody>

            <?php for($i = 1; $i <= 10; $i++): ?>

                <tr class="<?= $i % 2 !== 0 ? 'bg-primary-50' : '' ?>" >

                    <td class="pl-4 rounded-l-xl">
                        <?= $i ?>.
                    </td>

                    <td class="py-4">
                        <input type="text" name="name-<?= $i ?>" class="user-input" value="<?= @$data->transactions[$i]->transaction->name ?>">
                    </td>

                    <td>
                        <input type="number" name="amount-<?= $i ?>" class="user-input" value="<?= @$data->transactions[$i]->transaction->amount ?: '' ?>">
                    </td>

                    <td class="px-2">
                        <div class="flex flex-col justify-center text-sm">
                            <label>
                                <input type="radio" name="type-<?= $i ?>" value="spending" 
                                    <?= @$data->transactions[$i]->transaction->type ==='spending' ||
                                        @!$data->transactions[$i]
                                             ? 'checked' 
                                             : '' 
                                    ?> 
                                /> 
                                Spending
                            </label>
                            
                            <label>
                                <input type="radio" name="type-<?= $i ?>" value="income" <?= @$data->transactions[$i]->transaction->type ==='income' ? 'checked' : '' ?> /> 
                                Income
                            </label>
                        </div>
                    </td>

                    <td class="px-2">
                        <select name="budgets-<?= $i ?>" class="user-input">
                            <?php include '_select_budget.php' ?>
                        </select>
                    </td>

                    <td class="px-2 rounded-r-xl">
                        <input type="date" name="date-<?= $i ?>" class="user-input" value="<?= @$data->transactions[$i]->transaction->date ?: date('Y-m-d'); ?>" />
                    </td>

                </tr>

                <?php if( @$data->errors[$i] && !$data->errors[$i]->success ): ?>

                    <tr>
                        <td colspan="6">
                            <div class="border border-red-300 rounded-xl pt-4 mb-4">
                                <?= @$data->errors[$i]->errors->{'name-'    . $i}->show_error ?>
                                <?= @$data->errors[$i]->errors->{'amount-'  . $i}->show_error ?>
                                <?= @$data->errors[$i]->errors->{'type-'    . $i}->show_error ?>
                                <?= @$data->errors[$i]->errors->{'budgets-' . $i}->show_error ?>
                                <?= @$data->errors[$i]->errors->{'date-'    . $i}->show_error ?>
                            </div>
                        </td>
                    </tr>

                <?php endif ?>

            <?php endfor ?>

        </tbody>

    </table>

    <button type="submit" class="btn-secondary-filled flex items-center gap-2 my-8 px-4 py-2">
        <span class="material-icons-round">view_list</span>
        Add transactions
    </button>

    <p class="my-8 p-4 border border-primary-100 rounded-lg bg-primary-50">
        <span class="font-bold">*Note:</span> If the amount is 0 or blank, the transaction will not be added.
    </p>

</form>