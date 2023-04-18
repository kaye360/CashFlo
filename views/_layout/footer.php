

</main>

</div>


<footer class="mt-4 py-24 text-center bg-primary-800 text-slate-100 rounded-lg">

    <span>
        Made by <a href="https://joshkaye.dev" class="underline">Josh Kaye</a>
    </span>

</footer>

</div>

<?php if( @$data->prompt ): ?>
    <div class="prompt
        <?= @$data->prompt === 'success' ? 'prompt-success' : ''; ?>
        <?= @$data->prompt === 'error'   ? 'prompt-error'   : ''; ?>
        <?= @$data->prompt === 'success' ? 'prompt-success' : ''; ?>
    ">

        <span class="material-icons-round">
            <?php if( @$data->prompt === 'success' ): ?>
                check_circle_outline
            <?php elseif( @$data->prompt === 'error' ): ?>
                error_outline
            <?php endif; ?>
        </span>

        <div class="prompt-message">
            <?= @$data->promt_message; ?>
            This is a test message
        </div>
    </div>
<?php endif; ?>

</body>
</html>