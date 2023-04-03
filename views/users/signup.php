<?php
$data->h1 = 'Sign up to Spendly';
$data->title = 'Sign up to Spendly';
?>

<section class="flex flex-col gap-6 min-h-[50vh]">

    <?php if( @$data->success ): ?>
        <span class="block px-8 py-4 w-fit text-green-800 rounded border border-green-600 bg-green-100">
            Account successfully created. {{username}} you may now <a href="/signin" class="underline">Sign In</a>.
        </span>
    <?php endif; ?>

    <form method="POST" action="/signup" class="flex flex-col items-start gap-4">

        <label>
            <div>Username:</div>

            <?php if( @$data->errors->username->has_error ): ?>

                <div class="flex flex-col items-start gap-0 text-red-500">
                    
                    <?php if($data->errors->username->unique): ?>
                        <span>
                            This username is already taken.
                        </span>
                    <?php endif; ?>

                    <?php if($data->errors->username->required): ?>
                        <span>
                            Username is required.
                        </span>
                    <?php endif; ?>

                    <?php if($data->errors->username->has_forbidden_chars): ?>
                        <span>
                            Username may only include letters, numbers, underscores (_), and dashes (-).
                        </span>
                    <?php endif; ?>

                    <?php if(
                        $data->errors->username->max ||
                        $data->errors->username->min
                    ): ?>
                        <span>
                            Username must be 6-15 characters in length.
                        </span>
                    <?php endif; ?>

                </div>

            <?php endif; ?>


            <input type="text" id="username" name="username" class="form-input" value="{{username}}" />
        </label>

        <label>
            <div>Password:</div>

            <?php if( @$data->errors->confirm_password_1->has_error ): ?>

                <div class="flex flex-col items-start gap-0 text-red-500">

                    <?php if($data->errors->confirm_password_1->confirm_password): ?>
                        <span>
                            Password and confirmed password do not match.
                        </span>
                    <?php endif; ?>

                    <?php if($data->errors->confirm_password_1->required): ?>
                        <span>
                            Password is required.
                        </span>
                    <?php endif; ?>

                    <?php if($data->errors->confirm_password_1->min): ?>
                        <span>
                            Password must be at least 6 characters.
                        </span>
                    <?php endif; ?>

                </div>

            <?php endif; ?>
            
            <input type="password" name="confirm_password_1" class="form-input" />
        </label>

        <label>
            <div>Confirm Password:</div>

            <?php if( @$data->errors->confirm_password_2->has_error ): ?>

                <div class="flex flex-col items-start gap-0 text-red-500">

                    <?php if($data->errors->confirm_password_2->required): ?>
                        <span>
                            Confirm Password is required.
                        </span>
                    <?php endif; ?>

                </div>

            <?php endif; ?>

            
            <input type="password" name="confirm_password_2" class="form-input" />
        </label>

        <input type="submit" value="Create an account" class="inline-block px-4 py-2 bg-blue-100" />

        <?php if( @$data->errors->query ): ?>
            <span class="block text-red-500">
                Error with query.
            </span>
        <?php endif; ?>

    </form>
    
</section>
