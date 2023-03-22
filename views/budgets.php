

<h2 class="text-lg font-medium">Add a budget</h2>

<form method="POST" action="/budgets" class="flex flex items-center gap-12 mb-8">

    <div>
        <label>
            <div>Name:</div>

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

            <input type="text" name="name" class="border" value="{{name}}" />
        </label>
    
        <label>
            <div>Amount:</div>

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

            <input type="number" name="amount" value="{{amount}}" step="0.01" class="border " />
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


<h2 class="text-lg font-medium">Your Budgets</h2>