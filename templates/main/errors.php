<?php
if (!empty($errors)) : ?>
    <section class="wrapper error">
        <?php foreach ($errors as $error) {
            echo "<h3 class='flex-center'>$error</h3>";
        } ?>
    </section>
<?php endif; ?>