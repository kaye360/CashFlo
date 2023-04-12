<?php
use lib\Auth\Auth;
$data->title = 'Settings';
$data->h1 = 'Settings';
?>

<form method="POST" action="/settings" class="flex flex-col gap-8 my-8">
    
    <?php if( @$data->success ): ?>
        <section class="block px-8 py-4 w-fit text-green-800 rounded border border-green-600 bg-green-100">
            Changes saved.
        </section>
    <?php endif; ?>

    <section class="flex flex-col gap-2 w-fit min-w-[500px]">

        <h2 class="text-xl">Change Password</h2>

        <label class="grid grid-cols-2">
            <span>
                New Password:
            </span>
            <input type="password" name="confirm_password_1" />
        </label>

        <label class="grid grid-cols-2">
            <span>
                Confirm New Password:
            </span>
            <input type="password" name="confirm_password_2" />
        </label>


        <?php if( @$data->errors->confirm_password_1->has_error ): ?>

            <div class="flex flex-col gap-2 text-red-400">

                <?php if($data->errors->confirm_password_1->min): ?>
                    <span>
                        Password must be at least 6 characters.
                    </span>
                <?php endif; ?>

                <?php if($data->errors->confirm_password_1->confirm_password): ?>
                    <span>
                        Password and confirm password must match.
                    </span>
                <?php endif; ?>

            </div>

        <?php endif; ?>
        
    </section>

    <section class="flex flex-col gap-2 w-fit">
    
        <h2 class="text-xl">
            Transactions Per Page
        </h2>

        <select name="transactions_per_page">

            <option value="10"  <?= Auth::settings()->transactions_per_page === 10 ? 'selected' : '' ?>>10</option>
            <option value="25"  <?= Auth::settings()->transactions_per_page === 25 ? 'selected' : '' ?>>25</option>
            <option value="50"  <?= Auth::settings()->transactions_per_page === 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= Auth::settings()->transactions_per_page === 100 ? 'selected' : '' ?>>100</option>

        </select>

    </section>

    <section>

        <input type="hidden" name="username" value="<?= Auth::username(); ?>" />

        <input type="submit" value="Update settings" class="<<button_main>>" />

    </section>

</form>