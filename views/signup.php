
<section class="flex flex-col gap-6 min-h-[50vh]">

    <h1 class="text-2xl">Sign Up to Spendly</h1>

    <?php if($data->validator->success): ?>
        <span class="block px-8 py-4 w-fit text-green-800 rounded border border-green-600 bg-green-100">
            Account successfully created. {{username}} you may now <a href="/signin" class="underline">Sign In</a>.
        </span>
    <?php endif; ?>

    <form method="POST" action="/signup" class="flex flex-col items-start gap-4">

        <label>
            <div>Username:</div>

            <?php if($data->validator->errors->username->unique): ?>
                <span class="block text-red-500">
                    This username is already taken.
                </span>
            <?php endif; ?>

            <?php if($data->validator->errors->username->has_forbidden_chars): ?>
                <span class="block text-red-500">
                    Username may only include letters, numbers, underscores (_), and dashes (-).
                </span>
            <?php endif; ?>

            <?php if($data->validator->errors->username->max): ?>
                <span class="block text-red-500">
                    Username may be maximum 15 characters in length.
                </span>
            <?php endif; ?>


            <input type="text" id="username" name="username" class="form-input" value="{{username}}" />
        </label>

        <label>
            <div>Password:</div>

            <?php if($data->validator->errors->password->confirm_password): ?>
                <span class="block text-red-500">
                    Password and confirmed password do not match.
                </span>
            <?php endif; ?>

            <?php if($data->validator->errors->password->min): ?>
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

        <input type="submit" value="Create an account" class="inline-block px-4 py-2 bg-blue-100" />

    </form>
    
</section>
