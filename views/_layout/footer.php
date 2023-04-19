<?php use lib\utils\Prompt\Prompt; ?>

</main>

</div>


<footer class="mt-4 py-24 text-center bg-primary-800 text-slate-100 rounded-lg">

    <span>
        Made by <a href="https://joshkaye.dev" class="underline">Josh Kaye</a>
    </span>

</footer>

</div>

<?php if( Prompt::is_set() ): ?>

    <div class="prompt
        <?= Prompt::get_type() === 'success' ? 'prompt-success' : '' ?>
        <?= Prompt::get_type() === 'error'   ? 'prompt-error'   : '' ?>
    ">

        <span class="material-icons-round">

            <?php if( Prompt::get_type() === 'success' ): ?>
                check_circle_outline

            <?php elseif( Prompt::get_type() === 'error' ): ?>
                error_outline

            <?php endif; ?>

        </span>

        <div class="prompt-message">
            <?= Prompt::get_message() ?>
        </div>
        
    </div>

<?php endif ?>

</body>
</html>
