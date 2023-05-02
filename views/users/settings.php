<?php
use lib\Auth\Auth;
$data->title = 'Settings';
$data->h1 = 'Settings';
?>

<form method="POST" action="/settings" class="flex flex-col gap-8 my-8">
    
    <section class="flex flex-col gap-2 w-fit min-w-[500px]">

        <h2 class="text-xl">Change Password</h2>

        <label class="grid md:grid-cols-2">
            <span>
                New Password:
            </span>
            <input type="password" name="confirm_password_1" class="user-input" />
        </label>

        <label class="grid md:grid-cols-2">
            <span>
                Confirm New Password:
            </span>
            <input type="password" name="confirm_password_2" class="user-input" />
        </label>

    </section>

    <section class="flex flex-col gap-2 w-fit">
    
        <h2 class="text-xl">
            Transactions Per Page
        </h2>

        <select name="transactions_per_page" class="user-input">

            <option value="10"  <?= Auth::settings()->transactions_per_page === 10 ? 'selected' : '' ?>>10</option>
            <option value="25"  <?= Auth::settings()->transactions_per_page === 25 ? 'selected' : '' ?>>25</option>
            <option value="50"  <?= Auth::settings()->transactions_per_page === 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= Auth::settings()->transactions_per_page === 100 ? 'selected' : '' ?>>100</option>

        </select>

    </section>

    <section>

        <input type="hidden" name="username" value="<?= Auth::username(); ?>" />

        <input type="submit" value="Update settings" class="btn-secondary-filled" />

    </section>

</form>