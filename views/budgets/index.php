<?php
$data->title = 'Budgets';
$data->h1 = 'Budgets';
?>


<?php if( @$data->prompt === 'delete_budget' ): ?>
    <div class="<<success_prompt>> text-green-500 bg-green-100 border border-green 500">
        Budget was deleted.
    </div>
<?php endif; ?>

<section>

    <h2 class="text-lg font-medium">Add a budget</h2>

    <form method="POST" action="/budgets" class="flex flex items-center gap-12 mb-8">

        <div>
            <label>
                <div>Name:</div>

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

                <input type="text" name="name" class="border" value="{{name}}" />
            </label>
        
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
        </div>

        <div>
            <div>Type:</div>
            <label class="block">
                <input type="radio" name="type" value="spending" checked /> Spending
            </label>
            <label class="block">
                <input type="radio" name="type" value="income" /> Income
            </label>
        </div>

        <input type="submit" value="Add Budget" class="<<button>> <<button_main>>" />

    </form>

    <?php if( @$data->success ): ?>
        <span class="<<success_prompt>>">
            Budget added successfully.
        </span>
    <?php endif; ?>

</section>


<section class="flex flex-col gap-4">

    <h2 class="mb-2 p-2 text-lg font-medium bg-green-100">Your Budgets</h2>

        <?php if( count($data->budgets) === 0): ?>
            <div>
                You have no budgets to show.
            </div>
        <?php endif; ?>

        <div>
            Income: ${{income_total}} <br>
            Spending: ${{spending_total}} <br>
            Net: 
            <span class="
                <?= $data->net_total > 0 ? 'text-green-400' : 'text-red-400'; ?>
            ">
                ${{net_total}}
            </span>
        </div>

        <div class="grid grid-cols-3 items-end gap-4 w-fit">
            <?php foreach($data->budgets as $budget): ?>
                <span class="font-bold text-xl">
                    <?php echo $budget->name; ?>
                </span>
                <span class="
                    <?php echo $budget->type === 'spending' ? 'text-red-500' : 'text-green-500'; ?>
                ">
                    <?php echo $budget->type === 'spending' ? '-' : '+'; ?>
                    $<?php echo $budget->amount; ?>
                </span>
                <a href="/budget/<?php echo $budget->id; ?>/edit" class="text-gray-400 hover:text-gray-600 underline">
                    Edit
                </a>
            <?php endforeach; ?>
        </div>

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
})

</script>