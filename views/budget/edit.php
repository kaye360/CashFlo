
<a href="{{referer}}" class="inline-block mb-4 underline">
    Back to budgets
</a>

<section>

    <form method="POST" action="/budget/{{id}}/edit" class="flex flex-col gap-4 items-start">

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

            <input type="text" name="name" value="{{name}}" />

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

            <input type="number" name="amount" value="{{amount}}" class="border" />

        </label>


        <div>Type</div>

            <label>
                <input 
                    type="radio" name="type" value="spending" 
                    <?php if($data->type ==='spending') echo 'checked' ?> /> 
                Spending
            </label>
            <label class="block">
                <input 
                    type="radio" name="type" value="income"
                    <?php if($data->type ==='income') echo 'checked' ?> /> Income
            </label>

        <input type="hidden" name="referer" value="{{referer}}" />
        <input type="hidden" name="id" value="{{id}}" />

        <input type="submit" value="Edit Budget" class="<<button>> <<button_main>>" />
    
        <?php if( @$data->success ): ?>
            <span class="<<success_prompt>>">
                Budget saved.
            </span>
        <?php endif; ?>

    </form>

</section>


<section class="my-8">

<p>Would you like to delete this budget?</p>

<form method="POST" action="/budget/{{id}}/delete" id="delete-form">
    <input type="hidden" name="referer" value="{{referer}}" />
    <input type="hidden" name="id" value="{{id}}" />
    <input type="submit" id="delete-budget" class="<<button>> bg-red-400 text-white" data-clicked="false" value="Delete Budget" />
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