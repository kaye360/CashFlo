

<section>

    <form method="POST" action="/budget/{{id}}/edit" class="flex flex-col gap-4 items-start">

        <label>
            
            <div>Name</div>

            <input type="text" name="name" value="{{name}}" />

        </label>

        <label>

            <div>Amount</div>

            <input type="number" name="amount" value="{{amount}}" class="border" />

        </label>


        <div>Type</div>

            <label>
                <input type="radio" name="type" value="spending" checked /> Spending
            </label>
            <label class="block">
                <input type="radio" name="type" value="income" /> Income
            </label>

        <input type="submit" value="Edit Budget" class="<<button>> <<button_main>>" />

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