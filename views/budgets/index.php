<?php

use lib\InputHandler\InputErrors\InputErrors;

$data->title = 'Monthly Budgets';
$data->h1 = 'Monthly Budgets';
?>


<?php if( @$data->prompt === 'delete_budget' ): ?>
    <div class="<<success_prompt>> text-green-500 bg-green-100 border border-green 500">
        Budget was deleted.
    </div>
<?php endif; ?>

<div class="flex flex-col-reverse sm:flex-row sm:justify-between">

    <section class="flex flex-col">

        <?php if( count($data->budgets) === 0): ?>
            <div>
                You have no budgets to show.
            </div>
        <?php endif; ?>

  

        <?php $type = null; ?>
        
        <?php foreach($data->budgets as $budget): ?>

            <?php if( $budget->type !== $type ): ?>

                <h2 class="mx-2 my-4 py-2 text-lg font-bold border-b border-primary-200">
                    <?= ucwords($budget->type); ?>
                </h2>

                <?php $type = $budget->type; ?>

            <?php endif; ?>

            <div class="grid grid-cols-3 items-end gap-4 p-2 rounded-lg hover:bg-primary-50 monthly_budget_row">

                <span class="font-medium">
                    <?php echo $budget->name; ?>
                </span>

                <span class="text-primary-500">
                    $<?php echo $budget->amount; ?>
                </span>

                <a href="/budget/<?php echo $budget->id; ?>/edit" class="monthly_budget_edit">
                    <span class="material-icons-round">edit_note</span>
                </a>
            </div>
        <?php endforeach; ?>

    </section>

    <section class="flex flex-col justify-stretch gap-8 md:pt-8 min-w-[300px] ">

        <div class="grid grid-cols-[11ch_auto] gap-x-2 gap-y-4 bg-primary-50 drop-shadow-md p-6 rounded-lg font-medium">

            <h4 class="col-span-2 mb-2 text-md font-bold">
                Monthly Totals
            </h4>

            <span>
                Income: 
            </span>

            <span>
                ${{income_total}}
            </span>

            <span>
                Spending:
            </span>

            <span>
                ${{spending_total}}
            </span>

            <span class="col-span-2 border-t border-primary-200"></span>

            <span>
                Net Total: 
            </span>

            <span class="
                <?= $data->net_total > 0 ? 'text-green-500' : 'text-red-400'; ?>
            ">
                ${{net_total}}
            </span>

        </div>

        <button id="add-budget-btn" class="btn-secondary-outlined">
            <span class="material-icons-round">post_add</span>
            Add a budget
        </button>

        <form 
            method="POST" 
            action="/budgets" 
            id="add-budget-form" 
            class=" 
                flex flex-col gap-4 w-full overflow-hidden rounded-lg drop-shadow-md bg-primary-50
                <?= $_SERVER['REQUEST_METHOD'] === 'POST' ? 'border max-h-[1000px] p-4' : 'max-h-0'; ?>  
                transition-all ease-in-out duration-500"
        >

            <h2 class="font-bold">Enter budget info:</h2>

            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-50 ">Budget name:</span>
                
                <input 
                    type="text" 
                    name="name" 
                    class="px-2 border border-primary-300 rounded-lg" 
                    value="<?= @$data->budget->name ?>" 
                    required 
                />
            </label>

            <?= @$data->errors->name->show_error; ?>

            <label class="floating-label">
                <span class="ml-2 px-2 bg-primary-50">Amount per month:</span>

                <!-- #amount id is used in JS validation -->
                <input 
                    type="number" 
                    name="amount" 
                    id="amount" 
                    value="<?= @$data->budget->amount ?>" 
                    step="any" 
                    class="px-2 border border-primary-300 rounded-lg" 
                    required 
                />
            </label>

            <?= @$data->errors->amount->show_error; ?>

            <div>
                <div>Type:</div>

                <label class="block">
                    <input 
                        type="radio" 
                        name="type" 
                        value="spending" 
                        default
                        <?= @$data->budget->type === 'spending' ||
                            @$data->budget->type === null 
                                ? 'checked' 
                                : '' 
                        ?>
                    /> Spending
                </label>

                <label class="block">
                    <input 
                        type="radio" 
                        name="type" 
                        value="income" 
                        <?= @$data->budget->type === 'income' ? 'checked' : '' ?>
                    /> Income
                </label>

            </div>

            <input type="submit" value="Add Budget" class="btn-primary-filled" />

        </form>

    </section>

</div>


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

    const addBudgetBtn = document.querySelector('#add-budget-btn')
    const addBudgetForm = document.querySelector('#add-budget-form')

    addBudgetBtn.addEventListener('click', () => 
    {
        addBudgetForm.classList.toggle('max-h-0')
        addBudgetForm.classList.toggle('max-h-[1000px]')
        addBudgetForm.classList.toggle('p-4')
    })
})

</script>