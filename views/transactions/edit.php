<?php
$data->title = 'Edit Transaction';
$data->h1 = 'Edit Transaction: ' . $data->transaction->name;
?>


<a href="{{referer}}" class="btn-back">
    <span class="material-icons-round">keyboard_backspace</span>
    Back to Transactions
</a>

<?= @$data?->errors?->name?->show_error ?>
<?= @$data?->errors?->amount?->show_error ?>
<?= @$data?->errors?->type?->show_error ?>
<?= @$data?->errors?->budgets?->show_error ?>
<?= @$data?->errors?->date?->show_error ?>

<section>

    <form method="POST" action="/transaction/<?= $data->transaction->id; ?>/edit" class="grid grid-cols-[7ch_1fr] gap-4 items-center">

        <label for="name">
            <h3>Name</h3>
        </label>

        <input 
            type="text" 
            name="name" 
            id="name" 
            class="user-input"
            value="<?= $data->transaction->name; ?>"
        />


        <label for="amount">
            <h3>Amount</h3>
        </label>

        <input 
            type="number" 
            name="amount" 
            id="amount" 
            class="user-input"
            value="<?= $data->transaction->amount; ?>" 
            step="any" 
        />

        <div>
            <h3>Type</h3>
        </div>
    
        <div>

            <label>
                Spending
                
                <input 
                    type="radio" 
                    name="type" 
                    value="spending" 
                    <?= $data->transaction->type ==='spending' ? 'checked' : '' ?> 
                /> 
            </label>

            <label>
                Income
                <input 
                    type="radio" 
                    name="type" 
                    value="income"
                    <?= $data->transaction->type ==='income' ? 'checked' : '' ?> 
                /> 
            </label>
        </div>


        <label for="budgets">
            Budget
        </label>
    
        <select name="budgets" id="budgets" class="user-input">
            <?php foreach( @$data->budgets as $budget ) : ?>
                <option 
                    value="<?= $budget->name; ?>"
                    <?= $budget->name === $data->transaction->budget ? 'selected' : ''; ?>
                >
                    <?= $budget->name; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="date">
            Date
        </label>
    
        <input 
            type="date" 
            name="date"
            id="date"
            class="user-input"
            value="<?= $data->transaction->date; ?>" 
        />

        <div class="col-span-2">

            <input type="hidden" name="referer" value="{{referer}}" />
            
            <button type="submit" class="btn-primary-filled flex items-center justify-center gap-2 w-full md:w-auto">
                <span class="material-icons-round">edit_note</span>
                Edit Transaction
            </button>
        </div>

    </form>

</section>


<section class="my-8 p-4 rounded-lg border border-rose-200 bg-rose-50 w-full md:w-fit">

    <p class="mb-2">Would you like to delete this transaction?</p>

    <form method="POST" action="/transaction/<?= $data->transaction->id; ?>/delete" id="delete-form">
        <input type="hidden" name="referer" value="{{referer}}" />
        <button type="submit" id="delete-transaction" class="btn-delete justify-center w-full md:w-auto" data-clicked="false">
            <span class="material-icons-round">delete</span>
            Delete Transaction
        </button>
        
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
                deleteBtn.textContent = 'Are you sure?'
            } else {
                deleteBtn.textContent = 'Deleting...'
                deleteForm.submit()
            }
        })
    }

</script>