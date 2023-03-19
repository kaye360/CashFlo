
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

            <?php if($data->errors->username->has_error): ?>
                <div class="flex flex-col items-start gap-0 text-red-500">
                    <?php if($data->errors->username->unique): ?>
                        <span>
                            This username is already taken.
                        </span>
                    <?php endif; ?>

                    <?php if($data->errors->username->has_forbidden_chars): ?>
                        <span>
                            Username may only include letters, numbers, underscores (_), and dashes (-).
                        </span>
                    <?php endif; ?>

                    <?php if($data->errors->username->max): ?>
                        <span>
                            Username may be maximum 15 characters in length.
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>


            <input type="text" id="username" name="username" class="form-input" value="{{username}}" />
        </label>

        <label>
            <div>Password:</div>

            <?php if($data->errors->password->has_error): ?>
                <div class="flex flex-col items-start gap-0 text-red-500">
                    <?php if($data->errors->password->confirm_password): ?>
                        <span>
                            Password and confirmed password do not match.
                        </span>
                    <?php endif; ?>

                    <?php if($data->errors->password->min): ?>
                        <span>
                            Password must be at least 6 characters.
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <input type="password" id="password" name="password" class="form-input" />
        </label>

        <label>
            <div>Confirm Password:</div>
            <input type="password" id="confirm_password" name="confirm_password" class="form-input" />
        </label>

        <input type="submit" value="Create an account" class="inline-block px-4 py-2 bg-blue-100" />

        <?php if($data->errors->query): ?>
            <span class="block text-red-500">
                Error with query.
            </span>
        <?php endif; ?>

    </form>
    
</section>
