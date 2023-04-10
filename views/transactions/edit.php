<?php
$data->title = 'Edit Transaction';
$data->h1 = 'Edit Transaction: ';
?>


<a href="{{referer}}" class="inline-block mb-4 underline">
    Back to Transactions
</a>

<section>

    <form method="POST" action="/transaction/<?= $data->transaction->id; ?>/edit" class="flex flex-col gap-4 items-start">

        <label>
            
            <h3>Name</h3>

            <?php if ( @$data->errors->name->has_error ): ?>
                <span class="<<input_error>>">
                    <?php if ( $data->errors->name->has_forbidden_chars ): ?>
                        <span>
                            Transaction Name must only have letters, numbers, and spaces.
                        </span>
                    <?php endif; ?>
                    <?php if ( $data->errors->name->required ): ?>
                        <span>
                            Transaction Name is required.
                        </span>
                    <?php endif; ?>
                    <?php if ( $data->errors->name->max ): ?>
                        <span>
                            Transaction name may have up to 20 characters.
                        </span>
                    <?php endif; ?>
                </span>
            <?php endif; ?>

            <input type="text" name="name" value="<?= $data->transaction->name; ?>" />

        </label>

        <label>

            <h3>Amount</h3>

            <?php if ( @$data->errors->amount->has_error ): ?>
                <span class="<<input_error>>">
                    <?php if ( $data->errors->name->has_forbidden_chars ): ?>
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

            <input type="number" name="amount" value="<?= $data->transaction->amount; ?>" step="any" class="border" />

        </label>


        <div>
            <h3>Type</h3>
    
            <label>
                <input 
                    type="radio" name="type" value="spending" 
                    <?= $data->transaction->type ==='spending' ? 'checked' : '' ?> /> 
                Spending
            </label>
            <label class="block">
                <input 
                    type="radio" name="type" value="income"
                    <?= $data->transaction->type ==='income' ? 'checked' : '' ?> /> 
                Income
            </label>
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
                <?php foreach( @$data->budgets as $budget ) : ?>
                    <option 
                        value="<?= $budget->name; ?>"
                        <?= $budget->name === $data->transaction->budget ? 'selected' : ''; ?>
                    >
                        <?= $budget->name; ?>
                    </option>
                <?php endforeach; ?>
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
    
            <input type="date" name="date" value="<?= $data->transaction->date; ?>" />
        </div>


        <div>
            <input type="hidden" name="referer" value="{{referer}}" />
            <input type="submit" value="Edit Transaction" class="<<button>> <<button_main>>" />
        </div>

        <?php if( @$data->success ): ?>
            <span class="<<success_prompt>>">
                Transaction saved.
            </span>
        <?php endif; ?>

    </form>

</section>


<section class="my-8">

<p>Would you like to delete this transaction?</p>

<form method="POST" action="/transaction/<?= $data->transaction->id; ?>/delete" id="delete-form">
    <input type="hidden" name="referer" value="{{referer}}" />
    <input type="submit" id="delete-transaction" class="<<button>> bg-red-400 text-white" data-clicked="false" value="Delete Transaction" />
</form>

</section>


<script>

    window.addEventListener('DOMContentLoaded', initDeleteBtn)

    function initDeleteBtn() {
        const deleteBtn = document.querySelector('#delete-transaction')
        const deleteForm = document.querySelector('#delete-form')
        deleteBtn.addEventListener('click', e => {
            e.preventDefault()
            if( deleteBtn.dataset.clicked === 'false'){
                deleteBtn.dataset.clicked = 'true'
                deleteBtn.value = 'Are you sure?'
            } else {
                deleteBtn.value = 'Deleting...'
                deleteForm.submit()
            }
        })
    }

</script>