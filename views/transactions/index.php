<?php
$data->title = 'Transactions';
$data->h1 = 'Transactions';
?>


<section>

    <h2 class="mb-4">
        <button id="add-transaction-btn" class="btn-secondary-outlined">
            <span class="material-icons-round">post_add</span>
            Add a Transaction
        </button>
    </h2>

    <form method="POST" id="add-transaction-form" action="/transactions" class="
        flex items-center gap-12 mb-8 overflow-hidden max-h-0 max-h-[300px] transition-all duration-500
    ">

        <?= @$data->errors->name->show_error; ?>

        <div class="grid grid-cols-3 gap-x-2 gap-y-6 w-full p-4 bg-gradient-to-r from-primary-50 to-primary-100 rounded-lg">


            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-50 ">Transaction name:</span>
                
                <input 
                    type="text" 
                    name="name" 
                    class="px-2 border border-primary-150 rounded-lg" 
                    value="<?= @$data->budget->name ?>" 
                    required 
                />
            </label>

            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-50 ">Amount:</span>
                
                <input 
                    type="number" 
                    id="amount"
                    name="amount" 
                    class="px-2 border border-primary-150 rounded-lg" 
                    value="<?= @$data->budget->name ?>" 
                    required 
                />
            </label>

            <label class="flex items-center gap-2 p-2 border border-primary-150 rounded-lg">

                Type:

                <select name="type" class="bg-transparent">
                    <option value="spending">Spending</option>
                    <option value="income">Income</option>
                </select>

            </label>

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
                            $selected = $budget->name === $data->budget ? 'selected' : '';
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


<?php include '_pagination.php'; ?>


<section class="flex flex-col gap-4">

    <table>
        <thead class="hidden md:table-header-group ">
            <tr>
                <th class="text-left p-2">Name</th>
                <th class="text-left p-2">Amount</th>
                <th class="text-left p-2">Date</th>
                <th class="text-left p-2">Category</th>
                <th class="text-left p-2">Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php $date_counter = null; ?>
        
            <?php foreach($data->transactions as $transaction): ?>

                <?php if ($date_counter !== $transaction->date_month): ?>

                    <tr>
                        <td colspan="5" class="px-2 py-4 bg-primary-150 font-bold rounded-lg">
                            <?= $transaction->date_month;  ?>
                        </td>
                    </tr>
                    
                    <?php $date_counter = $transaction->date_month; ?>
                <?php endif; ?>

                <tr class="grid grid-cols-3 items-center w-full md:table-row odd:bg-primary-50 hover:bg-secondary-50 rounded-lg">

                    <td class=" col-span-2 px-2 md:py-4 text-xl font-bold">
                            <?= $transaction->name; ?>
                    </td>
                    
                    <td class="px-2 md:py-2 font-bold">
                        <?= $transaction->type === 'spending' ? '-' : '+'; ?>$<?= $transaction->amount; ?>
                    </td>
                    
                    <td class=" col-span-3 px-2 md:py-2 text-sm">
                        <?= $transaction->date_english; ?>
                    </td>
                    
                    <td class=" col-span-2 px-2 md:py-2">
                        <?= $transaction->budget; ?>
                    </td>
                    
                    <td class="px-2 md:py-2 text-sm">
                        <a href="/transaction/<?php echo $transaction->id; ?>/edit" class="text-gray-400 hover:text-gray-600 underline">
                        <span class="material-icons-round">edit_note</span>
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

    <?php include '_pagination.php'; ?>

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

    // Show/Hide Add budget Form

    const addTransactionBtn = document.querySelector('#add-transaction-btn')
    console.log(addTransactionBtn)
    const addTransactionForm = document.querySelector('#add-transaction-form')

    addTransactionBtn.addEventListener('click', () => 
    {
        addTransactionForm.classList.toggle('max-h-0')
        addTransactionForm.classList.toggle('max-h-[300px]')
        addTransactionForm.classList.toggle('p-4')
    })
})

</script>