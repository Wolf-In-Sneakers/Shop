<?php
if ((!empty($_SESSION["user"])) && (empty($read_profile["error"]))) : ?>
    <section class="change_product wrapper">
        <div>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" class="input" placeholder="Имя">
                <input type="text" name="login" class="input" placeholder="Login">

                <div class="input_file_wrapper">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    <input name="img[]" type="file" id="input_file" class="field input_file" multiple>

                    <label class="input_file_label_wrapper" for="input_file">
                        <div class="input_file_empty">Файл не вбран</div>
                        <div class="input_file_dtn">Выбрать</div>
                    </label>
                </div>

                <input type="submit" name="update_profile" class="btn" value="Сохранить">
                <a href="change_passwd.php" class="btn">Изменить Пароль</a>
                <a href="delete_acc.php" class="btn">Удалить аккаунт</a>
            </form>
        </div>
    </section>
<?php endif; ?>