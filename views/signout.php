<?php require_once 'layout/header.php'; ?>

<section class="flex flex-col gap-6 min-h-[50vh]">
    <h1 class="text-2xl">Sign out of your account</h1>

    <p>
        Are you sure want to sign out?
    </p>

    <form method="POST" action="/signout">
        <input type="submit" value="Sign Out" class="px-4 py-2 bg-blue-100" />
    </form>

    <?php if($data->success): ?>
        <span class="block px-8 py-4 w-fit text-green-800 rounded border border-green-600 bg-green-100">
            Sign out successful <a href="/" class="underline">Back to homepage</a>.
        </span>
    <?php endif; ?>

    
</section>

<?php require_once 'layout/footer.php'; ?>