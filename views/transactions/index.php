<?php
$data->title = 'Transactions';
$data->h1 = 'Transactions';
?>


<section class="md:grid md:grid-cols-2 items-end">

    <?php include '_pagination.php'; ?>

    <div class="md:flex mt-4 md:mt-0">
        <button id="add-transaction-btn" class="btn-secondary-outlined md:ml-auto">
            <span class="material-icons-round">post_add</span>
            Add a Transaction
        </button>
    </div>

    <form method="POST" id="add-transaction-form" action="/transactions" class=" col-span-2 flex items-center gap-12 mb-8 overflow-hidden max-h-0 transition-all duration-500
    ">

        <?= @$data->errors->name->show_error; ?>
        <?= @$data->errors->amount->show_error; ?>
        <?= @$data->errors->type->show_error; ?>
        <?= @$data->errors->budget->show_error; ?>
        <?= @$data->errors->date->show_error; ?>

        <div class="grid grid-cols-3 items-center gap-x-2 gap-y-6 w-full p-4 bg-gradient-to-r from-primary-25 to-white rounded-lg">


            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-25 ">Transaction name:</span>
                
                <input 
                    type="text" 
                    name="name" 
                    class="px-2 border border-primary-150 rounded-lg" 
                    value="<?= @$data->budget->name ?>" 
                    required 
                />
            </label>

            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-25 ">Amount:</span>
                
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

            <label>
                
                <div>
                    Budget:
                </div>
        
                <select name="budgets" class="bg-transparent">
                    <?php 
                        foreach( @$data->budgets as $budget )
                        {
                            $selected = $budget->name === $data->budget ? 'selected' : '';
                            echo "<option value='$budget->name' $selected >$budget->name</option> \n";
                        }
                    ?>
                </select>

            </label>

            <label>
                
                <h3>Date</h3>
        
                <input type="date" name="date" value="{{date}}" class="bg-transparent" />
            </label>

            <div>
                <input type="submit" value="Add Transaction" class="btn-primary-filled" />
            </div>

        </div>
    </form>

</section>



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