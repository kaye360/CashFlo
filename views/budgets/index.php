<?php
$data->title = 'Monthly Budgets';
$data->h1 = 'Monthly Budgets';
?>


<?php if( @$data->prompt === 'delete_budget' ): ?>
    <div class="<<success_prompt>> text-green-500 bg-green-100 border border-green 500">
        Budget was deleted.
    </div>
<?php endif; ?>

<div class="flex justify-between">

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

    <section class="flex flex-col gap-8 pt-8">

        <div class="grid grid-cols-2 gap-x-8 gap-y-4 bg-primary-50 drop-shadow-md p-6 rounded-lg font-medium">

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

        <button class="flex items-center justify-center gap-2 w-full py-2 border-2 border-secondary-400 text-secondary-400 font-bold rounded-xl">
            <span class="material-icons-round">post_add</span>
            Add a budget
        </button>

        <form method="POST" action="/budgets" class="flex flex-col gap-2">

            <div class="relative border h-8">
                <label for="name" class="absolute top-0 left-0 z-20">
                    Name:
                </label>

                <input type="text" name="name" id="name" class="absolute top-0 left-0 z-10 w-full border border-primary-400" value="{{name}}" />
            </div>

            <?php if ( @$data->errors->name->has_error ): ?>
                <span class="<<input_error>>">
                    <?php if ( @$data->errors->name->has_forbidden_chars ): ?>
                        <span>
                            Budget Name must only have letters, numbers, and spaces.
                        </span>
                    <?php endif; ?>
                    <?php if ( @$data->errors->name->required ): ?>
                        <span>
                            Budget Name is required.
                        </span>
                    <?php endif; ?>
                    <?php if ( @$data->errors->name->max ): ?>
                        <span>
                            Budget name may have up to 20 characters.
                        </span>
                    <?php endif; ?>
                </span>
            <?php endif; ?>

                
            
            <label>
                <div>Amount:</div>

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

            <div>
                <div>Type:</div>
                <label class="block">
                    <input type="radio" name="type" value="spending" checked /> Spending
                </label>
                <label class="block">
                    <input type="radio" name="type" value="income" /> Income
                </label>
            </div>

            <input type="submit" value="Add Budget" class="" />

        </form>

        <?php if( @$data->success ): ?>
            <span class="<<success_prompt>>">
                Budget added successfully.
            </span>
        <?php endif; ?>

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
})

</script>