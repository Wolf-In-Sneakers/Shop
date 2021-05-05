<?php
if ((empty($delete_acc["error"])) and (!empty($delete_acc["success"]))) : ?>
    <section class="error">
        <h3 class='flex-center'><?= $delete_acc['success'] ?></h3>
    </section>
<?php else : ?>
    <section class="change_product wrapper">
        <div>
            <form action="" method="POST" enctype="multipart/form-data">
                <h3>После удаления аккаунта востановить его будет невозможным!</h3>
                <input type="password" name="passwd" class="input" placeholder="Пароль" required>
                <input type="submit" name="delete_acc" class="btn" value="Удалить аккаунт">
            </form>
        </div>
    </section>
<?php endif; ?>