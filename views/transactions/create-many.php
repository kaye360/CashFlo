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

        <thead class="hidden md:table-header-group">
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

                <tr class="<?= $i % 2 !== 0 ? 'bg-primary-50' : '' ?>  flex flex-col gap-2 justify-stretch px-2 py-12 md:py-0 md:table-row" >

                    <td class="hidden md:table-cell pl-4 rounded-l-xl">
                        <?= $i ?>.
                    </td>

                    <td class="py-4 block md:table-cell">
                        <span class="inline-block md:hidden w-[10ch]">Name:</span>
                        <input type="text" name="name-<?= $i ?>" class="user-input w-full" value="<?= @$data->transactions[$i]->transaction->name ?>">
                    </td>

                    <td class="block md:table-cell">
                        <span class="inline-block md:hidden w-[10ch]">Amount:</span>
                        <input type="number" name="amount-<?= $i ?>" class="user-input w-full" value="<?= @$data->transactions[$i]->transaction->amount ?: '' ?>">
                    </td>

                    <td class="px-2 flex md:table-cell">
                        <span class="inline-block md:hidden w-[10ch]">Type:</span>
                        <div class="flex md:flex-col md:justify-center gap-4 md:gap-0 md:text-sm">
                            <label class=" w-max">
                                <input type="radio" name="type-<?= $i ?>" value="spending" 
                                    <?= @$data->transactions[$i]->transaction->type ==='spending' ||
                                        @!$data->transactions[$i]
                                             ? 'checked' 
                                             : '' 
                                    ?> 
                                /> 
                                Spending
                            </label>
                            
                            <label class=" w-max">
                                <input type="radio" name="type-<?= $i ?>" value="income" <?= @$data->transactions[$i]->transaction->type ==='income' ? 'checked' : '' ?> /> 
                                Income
                            </label>
                        </div>
                    </td>

                    <td class="px-2 block md:table-cell">
                        <span class="inline-block md:hidden w-[10ch]">Budget:</span>
                        <select name="budgets-<?= $i ?>" class="user-input">
                            <?php include '_select_budget.php' ?>
                        </select>
                    </td>

                    <td class="px-2 block md:table-cell rounded-r-xl">
                        <span class="inline-block md:hidden w-[10ch]">Date:</span>
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