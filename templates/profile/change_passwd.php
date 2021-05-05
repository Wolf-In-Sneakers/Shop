<?php
if ((empty($change_passwd["error"])) and (!empty($change_passwd["success"]))) : ?>
    <section class="error">
        <h3 class='flex-center'><?= $change_passwd['success'] ?></h3>
    </section>
<?php else : ?>
    <section class="change_product wrapper">
        <div>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="password" name="last_passwd" class="input" placeholder="Старый пароль" required>
                <input type="password" name="passwd" class="input" placeholder="Пароль" required>
                <input type="password" name="passwd_check" class="input" placeholder="Повторный пароль" required>
                <input type="submit" name="change_passwd" class="btn" value="Изменить">
            </form>
        </div>
    </section>
<?php endif; ?>