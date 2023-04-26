<?php
$data->title = 'Edit budget';
$data->h1 = 'Edit Budget: ' . $data->budget->name;
?>


<a href="{{referer}}" class="btn-back">
    <span class="material-icons-round">keyboard_backspace</span>
    Back to budgets
</a>

<?= @$data->errors->name->show_error; ?>
<?= @$data->errors->amount->show_error; ?>
<?= @$data->errors->type->show_error; ?>

<section>

    <form method="POST" action="/budget/<?= $data->budget->id ?>/edit" class="grid grid-cols-[6ch_1fr] gap-4 items-center">

        <label for="name">
            <div>Name</div>
        </label>

        <input 
            type="text" 
            name="name" 
            id="name"
            value="<?= $data->budget->name ?>" 
            class="user-input" 
        />


        <label for="amount">
            <div>Amount</div>
        </label>

        <input 
            type="number" 
            name="amount" 
            id="amount"
            value="<?= (float) $data->budget->amount ?>" 
            step="any" 
            class="user-input" 
        />



        <div>
            Type
        </div>

        <div class="flex items-center gap-4">
            <label>
                <input 
                    type="radio" name="type" value="spending" 
                    <?php if($data->budget->type ==='spending') echo 'checked' ?> /> 

                Spending
            </label>

            <label>

                <input 
                    type="radio" name="type" value="income"
                    <?php if($data->budget->type ==='income') echo 'checked' ?> /> 
                    
                Income
            </label>
        </div>

        <input type="hidden" name="referer" value="{{referer}}" />

        <div class="col-span-2">
            <button type="submit" class="btn-primary-filled flex items-center gap-2">
            <span class="material-icons-round">edit_note</span>
                Edit Budget
            </button>
        </div>
    
    </form>

</section>


<section class="my-8 p-4 rounded-lg border border-rose-200 bg-rose-50 w-fit">

    <p class="mb-2">
        Would you like to delete this budget?
    </p>

    <form method="POST" action="/budget/<?= $data->budget->id ?>/delete" id="delete-form">

        <input type="hidden" name="referer" value="{{referer}}" />

        <button 
            type="submit" 
            id="delete-budget" 
            class="btn-delete" 
            data-clicked="false"
        >
            <span class="material-icons-round">delete</span>
            Delete Budget
        </button>
        
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
                deleteBtn.textContent = 'Are you sure?'
            } else {
                deleteBtn.textContent = 'Deleting...'
                deleteForm.submit()
            }
        })
    }

</script>