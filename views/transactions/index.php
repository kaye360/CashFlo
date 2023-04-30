<?php
$data->title = 'Transactions';
$data->h1 = 'Transactions';
?>

<section class="md:grid md:grid-cols-2 items-end">

    <?php if( !empty( $data->transactions )): ?>
        <?php include '_pagination.php'; ?>
    <?php else: ?>
        <div>
            You have no transactions to show.
        </div>
    <?php endif ?>

    <button id="add-transaction-btn" class="btn-secondary-outlined mt-4 w-full md:w-auto md:ml-auto">
        <span class="material-icons-round">post_add</span>
        Add a Transaction
    </button>

    <?php if( @!$data->success ): ?>
        <div class="col-span-2 mt-4">
            <?= @$data->errors->name->show_error; ?>
            <?= @$data->errors->amount->show_error; ?>
            <?= @$data->errors->type->show_error; ?>
            <?= @$data->errors->budget->show_error; ?>
            <?= @$data->errors->date->show_error; ?>
        </div>
    <?php endif ?>

    <form 
        method="POST" 
        id="add-transaction-form" 
        action="/transactions" 
        class="
            col-span-2 flex items-center gap-12 mb-8 overflow-hidden  transition-all duration-500
            <?= $_SERVER['REQUEST_METHOD'] === 'POST' 
                ? 'max-h-[300px] p-4'
                : 'max-h-0'
            ?>
        "
    >

        <div class="grid grid-cols-3 items-center gap-x-2 gap-y-6 w-full p-4 bg-gradient-to-r from-primary-25 to-white rounded-lg">

            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-25 ">Transaction name:</span>
                
                <input 
                    type="text" 
                    name="name" 
                    class="px-2 border border-primary-150 rounded-lg" 
                    value="<?= @$data->transaction->name ?>" 
                    required 
                />
            </label>

            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-25 ">Amount:</span>
                
                <input 
                    type="number" 
                    id="amount"
                    name="amount" 
                    step="any"
                    class="px-2 border border-primary-150 rounded-lg" 
                    value="<?= @$data->transaction->amount ?>" 
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
                            $selected = @$budget->name === $data->transaction->budget ? 'selected' : '';
                            echo "<option value='$budget->name' $selected >$budget->name</option> \n";
                        }
                    ?>
                    <option value="none">None</option>
                </select>

            </label>

            <label>
                
                <h3>Date</h3>
        
                <input type="date" name="date" value="<?= $data?->transaction?->date ?? $data->date ?>" class="bg-transparent" />
            </label>

            <div>
                <input type="submit" value="Add Transaction" class="btn-primary-filled" />
            </div>

        </div>
    </form>

</section>


<section class="flex flex-col gap-4 text-md text-primary-600">
    
    <?php if( !empty($data->transactions) ): ?>
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
                            <td colspan="5" class="px-2 pb-4 pt-6 border-b-2 border-primary-100 font-bold rounded-lg">
                                <?= $transaction->date_month;  ?>
                            </td>
                        </tr>
                        
                        <?php $date_counter = $transaction->date_month; ?>
                    <?php endif; ?>

                    <tr class="grid grid-cols-3 items-center w-full md:table-row odd:bg-primary-25 hover:bg-primary-50 rounded-lg">

                        <td class=" col-span-2 px-2 pt-2 md:py-2 text-primary-900 font-medium">
                                <?= $transaction->name; ?>
                        </td>
                        
                        <td class="px-2 ">
                            <?= $transaction->type === 'spending' ? '-' : '+'; ?>$<?= $transaction->amount; ?>
                        </td>
                        
                        <td class=" col-span-3 px-2 ">
                            <?= $transaction->date_english; ?>
                        </td>
                        
                        <td class=" col-span-2 px-2 pb-2 md:py-0">
                            <?= $transaction->budget; ?>
                        </td>
                        
                        <td class="px-2 ">
                            <a href="/transaction/<?php echo $transaction->id; ?>/edit" class="text-gray-400 hover:text-gray-600 underline">
                            <span class="material-icons-round">edit_note</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

        <?php include '_pagination.php'; ?>

    <?php endif ?>

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