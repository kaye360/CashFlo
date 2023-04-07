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
                Old Password:
            </span>
            <input type="password" name="password" />
        </label>
        
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


        <?php if(
            @$data->errors->password->has_error ||
            @$data->errors->confirm_password_1->has_error
        ): ?>
            <div class="flex flex-col gap-2 text-red-400">

                <?php if($data->errors->password->user_pass_verify): ?>
                    <span>
                        Incorrect password.
                    </span>
                <?php endif; ?>

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

        <div>
            <input type="hidden" name="username" value="<?= Auth::username() ?>" />
            <input type="submit" value="Update settings" class="<<button_main>>" />
        </div>

    </section>

</form>