<?php require_once 'layout/header.php'; ?>

<section class="flex flex-col items-start gap-4">
    
    <h1>Unauthorized</h1>
    
    <p>
        This page is protected. You must be logged in to view this page.
    </p>

    <a href="/signin" class="inline-block px-4 py-2 bg-blue-200">
        Sign In
    </a>
</section>

<?php require_once 'layout/footer.php'; ?>