<?php
$data->title = 'Sign Out';
$data->h1 = 'Sign out of your account';
?>
<section class="flex flex-col gap-6 min-h-[50vh]">

    <p>
        Are you sure want to sign out?
    </p>

    <form method="POST" action="/signout">
        <input type="submit" value="Sign Out" class="btn-primary-filled" />
    </form>
    
</section>
