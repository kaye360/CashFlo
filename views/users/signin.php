<?php
$data->title = 'Sign In to CashFlo';
$data->h1 = 'Sign In to CashFlo';
?>
<section class="flex flex-col gap-6 min-h-[50vh]">

    <?= @$data?->errors?->password?->show_error ?>

    <form method="POST" action="/signin" class="flex flex-col items-start gap-4">


    <label>
        <div>Username</div>
        <input type="text" id="username" name="username" class="user-input" value="{{username}}" />
    </label>

    <label>
        <div>Password</div>
        <input type="password" id="password" name="password" class="user-input" />
    </label>


    <input type="submit" value="Sign In" class="btn-secondary-filled" />

    </form>

</section>
