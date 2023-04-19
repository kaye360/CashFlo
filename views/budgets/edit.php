<?php
$data->title = 'Edit budget';
$data->h1 = 'Edit Budget: ' . $data->budget->name;
?>


<a href="{{referer}}" class="inline-block mb-4 underline">
    Back to budgets
</a>

<section>

    <form method="POST" action="/budget/<?= $data->budget->id ?>/edit" class="flex flex-col gap-4 items-start">

        <label>
            
            <div>Name</div>

            <?php if ( @$data->errors->name->has_error ): ?>
                <span class="<<input_error>>">
                    <?php if ( $data->errors->name->has_forbidden_chars ): ?>
                        <span>
                            Budget Name must only have letters, numbers, and spaces.
                        </span>
                    <?php endif; ?>
                    <?php if ( $data->errors->name->required ): ?>
                        <span>
                            Budget Name is required.
                        </span>
                    <?php endif; ?>
                    <?php if ( $data->errors->name->max ): ?>
                        <span>
                            Budget name may have up to 20 characters.
                        </span>
                    <?php endif; ?>
                </span>
            <?php endif; ?>

            <input 
                type="text" 
                name="name" 
                value="<?= $data->budget->name ?>" 
                class="p-2 border border-primary-800 rounded-lg" 
            />

        </label>

        <label>

            <div>Amount</div>

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

            <input 
                type="number" 
                name="amount" 
                value="<?= (float) $data->budget->amount ?>" 
                step="any" 
                class="p-2 border border-primary-800 rounded-lg" 
            />

        </label>


        <div>Type</div>

            <label>
                <input 
                    type="radio" name="type" value="spending" 
                    <?php if($data->budget->type ==='spending') echo 'checked' ?> /> 
                Spending
            </label>
            <label class="block">
                <input 
                    type="radio" name="type" value="income"
                    <?php if($data->budget->type ==='income') echo 'checked' ?> /> 
                Income
            </label>

        <input type="hidden" name="referer" value="{{referer}}" />

        <input type="submit" value="Edit Budget" class="btn-primary-filled" />
    
    </form>

</section>


<section class="my-8 p-4 rounded-lg border border-rose-200 bg-rose-50">

    <p class="mb-2">
        Would you like to delete this budget?
    </p>

    <form method="POST" action="/budget/<?= $data->budget->id ?>/delete" id="delete-form">

        <input type="hidden" name="referer" value="{{referer}}" />

        <input type="submit" id="delete-budget" class="bg-red-400 p-2 text-white rounded-lg" data-clicked="false" value="Delete Budget" />
        
    </form>

</section>


<script>

    window.addEventListener('DOMContentLoaded', initDeleteBtn)

    function initDeleteBtn() {
        const deleteBtn = document.querySelector('#delete-budget')
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