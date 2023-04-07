<?php
$data->title = 'Transactions';
$data->h1 = 'Transactions';
?>


<?php if( @$data->prompt === 'delete_transaction' ): ?>
    <div class="<<success_prompt>> text-green-500 bg-green-100 border border-green 500">
        Transaction was deleted.
    </div>
<?php endif; ?>

<section>

    <h2 class="mb-4">
        <button id="add_transaction_btn" class="text-lg font-medium border border-slate-300 hover:border-slate-600 px-4 py-2 rounded-md">
            Add a Transaction
        </button>
    </h2>

    <form method="POST" id="add_transaction_form" action="/transactions" class="flex flex items-center gap-12 mb-8 hidden">

        <div class="grid grid-cols-3 gap-x-2 gap-y-6 w-full p-4 bg-slate-50 border border-slate-200 rounded-lg">

            <div>
                <label>

                    <h3>Name</h3>
        
                    <?php if ( @$data->errors->name->has_error ): ?>
                        <span class="<<input_error>>">
                            <?php if ( @$data->errors->name->has_forbidden_chars ): ?>
                                <span>
                                    Transaction Name must only have letters, numbers, and spaces.
                                </span>
                            <?php endif; ?>
                            <?php if ( @$data->errors->name->required ): ?>
                                <span>
                                    Transaction Name is required.
                                </span>
                            <?php endif; ?>
                            <?php if ( @$data->errors->name->max ): ?>
                                <span>
                                    Transaction name may have up to 20 characters.
                                </span>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
        
                    <input type="text" name="name" class="border" value="{{name}}" />
                </label>
            </div>

            <div>
                <label>
                    
                    <h3>Amount</h3>
        
                    <?php if ( @$data->errors->amount->has_error ): ?>
                        <span class="<<input_error>>">
                            <?php if ( $data->errors->amount->has_forbidden_chars ): ?>
                                <span>
                                    Amount must only have letters, numbers, and spaces.
                                </span>
                            <?php endif; ?>
                            <?php if ( $data->errors->amount->required ): ?>
                                <span>
                                    Amount is required.
                                </span>
                            <?php endif; ?>
                            <?php if ( $data->errors->amount->number ): ?>
                                <span>
                                    Amount must be a number.
                                </span>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
        
                    <!-- #amount id is used in JS validation -->
                    <input type="number" name="amount" id="amount" value="{{amount}}" step="any" class="border " />
                </label>
            </div>

            <div>

                <h3>Type</h3>

                <select name="type">
                    <option value="spending">Spending</option>
                    <option value="income">Income</option>
                </select>

            </div>

            <div>
                
                <h3>Budget</h3>
        
                <?php if ( @$data->errors->budgets->has_error ): ?>
                    <span class="<<input_error>>">
                        <?php if ( $data->errors->budgets->has_forbidden_chars ): ?>
                            <span>
                                Budget must only have letters, numbers, and spaces.
                            </span>
                        <?php endif; ?>
                        <?php if ( $data->errors->budgets->required ): ?>
                            <span>
                                Budget is required.
                            </span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
        
                <select name="budgets">
                    <?php 
                        foreach( @$data->budgets as $budget )
                        {
                            $selected = $budget->name === $data->selected_budget ? 'selected' : '';
                            echo "<option value='$budget->name' $selected >$budget->name</option> \n";
                        }
                    ?>
                </select>
            </div>

            <div>
                
                <h3>Date</h3>
        
                <?php if ( @$data->errors->date->has_error ): ?>
                    <span class="<<input_error>>">
                        <?php if ( $data->errors->date->has_forbidden_chars ): ?>
                            <span>
                                Date must only have letters, numbers, and spaces.
                            </span>
                        <?php endif; ?>
                        <?php if ( $data->errors->date->required ): ?>
                            <span>
                                Date is required.
                            </span>
                        <?php endif; ?>
                        <?php if ( $data->errors->date->date ): ?>
                            <span>
                                Date is in an incorrect format.
                            </span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
        
                <input type="date" name="date" value="{{date}}" />
            </div>

            <div>
                <input type="submit" value="Add Transaction" class="<<button>> <<button_main>>" />
            </div>

        </div>
    </form>

    <?php if( @$data->success ): ?>
        <span class="<<success_prompt>>">
            Transaction added successfully.
        </span>
    <?php endif; ?>

</section>


<section class="flex flex-col gap-4">

    <h2 class="mb-2 p-2 text-lg font-medium bg-teal-100 rounded-lg">Your Transactions</h2>

        <table>
            <thead>
                <tr>
                    <td class="p-2">Name</td>
                    <td class="p-2">Amount</td>
                    <td class="p-2">Date</td>
                    <td class="p-2">Category</td>
                    <td class="p-2"></td>
                </tr>
            </thead>
            <tbody>
            
                <?php foreach($data->transactions as $transaction): ?>

                    <tr class="px-4 odd:bg-teal-50 hover:bg-slate-100">

                        <td class="p-2 font-bold text-xl">
                                <?= $transaction->name; ?>
                        </td>
                        
                        <td class="p-2">
                            <?= $transaction->type === 'spending' ? '-' : '+'; ?>
                            $<?= $transaction->amount; ?>
                        </td>
                        
                        <td class="p-2">
                            <?= $transaction->date_as_words; ?>
                        </td>
                        
                        <td class="p-2">
                            <?= $transaction->budget; ?>
                        </td>
                        
                        <td class="p-2">
                            <a href="/transaction/<?php echo $transaction->id; ?>/edit" class="text-gray-400 hover:text-gray-600 underline">
                                Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

        <?php if( count($data->transactions) === 0): ?>
            <div class="pl-2">
                You have no transactions to show.
            </div>
        <?php endif; ?>

</section>


<script>

window.addEventListener('DOMContentLoaded', () => 
{

    // Shorten Amount Input to 2 decimal places
    const amountInput = document.querySelector('#amount')

    amountInput.addEventListener('change', () => 
    {
        amountInput.value = parseFloat( amountInput.value ).toFixed(2)
    })

    // Show/Hide Add transaction form
    const addTransactionBtn  = document.querySelector('#add_transaction_btn')
    const addTransactionForm = document.querySelector('#add_transaction_form')

    addTransactionBtn.addEventListener('click', () =>
    {
        addTransactionForm.classList.toggle('hidden')
    })
})

</script>