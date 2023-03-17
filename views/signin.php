
<section class="flex flex-col gap-6 min-h-[50vh]">

    <h1 class="text-2xl">Sign In to Spendly</h1>

    <?php if($data->success): ?>
        <span class="block px-8 py-4 w-fit text-green-800 rounded border border-green-600 bg-green-100">
            Logged in successfully. <a href="/dashboard" class="underline">Continue to Dashboard</a>.
        </span>
    <?php endif; ?>

    <form method="POST" action="/signin" class="flex flex-col items-start gap-4">

    <label>
        <div>Username</div>
        <input type="text" id="username" name="username" class="form-input" value="{{username}}" />
    </label>

    <label>
        <div>Password</div>
        <input type="password" id="password" name="password" class="form-input" />
    </label>

    <?php if($data->error_invalid_username_password): ?>
        <span class="text-red-500">
            Incorrect username or password.
        </span>
    <?php endif; ?>

    <input type="submit" value="Sign In" class="inline-block px-4 py-2 bg-blue-100" />

    </form>

</section>
