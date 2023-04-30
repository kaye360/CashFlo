<?php
$data->h1 = 'Sign up to CashFlo';
$data->title = 'Sign up to CashFlo';
?>

<section class="flex flex-col gap-6 min-h-[50vh]">

    <form method="POST" action="/signup" class="grid grid-cols-[auto_1fr] items-end gap-4">

        <label>
            <div>Username:</div>
            <input type="text" id="username" name="username" class="user-input" value="{{username}}" />
        </label>
        
        <div class="">
            <?= @$data?->errors?->username?->show_error ?>
        </div>

        <label>
            <div>Password:</div>
            <input type="password" name="confirm_password_1" class="user-input" />
        </label>
        
        <div>
            <?= @$data?->errors?->confirm_password_1?->show_error ?>
        </div>

        <label>
            <div>Confirm Password:</div>
            <input type="password" name="confirm_password_2" class="user-input" />
        </label>
        
        <div></div>

        <input type="submit" value="Create an account" class="btn-secondary-filled" />

    </form>
    
</section>
