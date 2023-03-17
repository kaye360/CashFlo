
<section class="flex flex-col gap-6 min-h-[50vh]">

    <h1 class="text-2xl">Sign Up to Spendly</h1>
    
    <?php if($data->success): ?>
        <span class="block px-8 py-4 w-fit text-green-800 rounded border border-green-600 bg-green-100">
            Account successfully created. {{username}} you may now <a href="/signin" class="underline">Sign In</a>.
        </span>
    <?php endif; ?>

    <form method="POST" action="/signup" class="flex flex-col items-start gap-4">

        <label>
            <div>Username:</div>

            <?php if($data->error_username_is_taken): ?>
                <span class="block text-red-500">
                    This username is already taken.
                </span>
            <?php endif; ?>

            <?php if($data->error_username_has_forbidden_chars): ?>
                <span class="block text-red-500">
                    Username may only include letters, numbers, underscores (_), and dashes (-).
                </span>
            <?php endif; ?>

            <?php if($data->error_username_has_too_many_chars): ?>
                <span class="block text-red-500">
                    Username may be maximum 15 characters in length.
                </span>
            <?php endif; ?>


            <input type="text" id="username" name="username" class="form-input" value="{{username}}" />
        </label>

        <label>
            <div>Password:</div>

            <?php if($data->error_passwords_dont_match): ?>
                <span class="block text-red-500">
                    Password and confirmed password do not match.
                </span>
            <?php endif; ?>

            <?php if($data->error_password_too_short): ?>
                <span class="block text-red-500">
                    Password must be at least 6 characters.
                </span>
            <?php endif; ?>
            
            <input type="password" id="password" name="password" class="form-input" />
        </label>

        <label>
            <div>Confirm Password:</div>
            <input type="password" id="confirm_password" name="confirm_password" class="form-input" />
        </label>

        <?php if($data->error_inputs_missing): ?>
            <span class="block text-red-500">
                All fields be must filled out.
            </span>
        <?php endif; ?>

        <?php if($data->error_with_query): ?>
            <span class="block text-red-500">
                Error with query.
            </span>
        <?php endif; ?>

        <input type="submit" value="Create an account" class="inline-block px-4 py-2 bg-blue-100" />

    </form>
    
</section>
