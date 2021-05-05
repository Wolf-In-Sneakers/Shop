<?php
if (!empty($_SESSION["user"])) :
    $read_profile = $user->read_profile();
    if (empty($read_profile["error"])) :
        $user = $read_profile["user"];
        $imgs = $read_profile["img"];
        ?>
        <section class='wrapper product-img flex-center'>
            <?php if (empty($imgs)) : ?>
                <article>
                    <img src='images/not_image_profile.png' alt='Featured item'>
                </article>
            <?php else : ?>
                <?php foreach ($imgs as $img) : ?>
                    <article>
                        <img src="<?= UPLOAD_DIR, $img["name"] ?>" alt="Item">
                        <?php if (!empty($_SESSION["user"])) : ?>
                            <form action="" method="POST">
                                <button type="submit" class="delete-btn" value="<?= $img["id_image"] ?>"
                                        name="delete_img_profile">Удалить
                                </button>
                                <button type="submit" class="delete-btn" value="<?= $img["id_image"] ?>"
                                        name="set_main_img_profile">Сделать основной
                                </button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="wrapper product-desc">
            <div>
                <h3>ID: <span><?= $user["id_user"] ?></span></h3>
                <h3>Имя: <span><?= $user["name"] ?></span></h3>
                <h3>Login: <span><?= $user["login"] ?></span></h3>
                <h3>Дата последней авторизации: <span><?= $user["last_action"] ?></span></h3>
                <h3>Дата последней смены пароля: <span><?= $user["modified_passwd"] ?></span></h3>
                <h3>Профиль создан: <span><?= $user["created"] ?></span></h3>
            </div>
        </section>
    <?php else : ?>
        <section class="error">
            <h3 class='flex-center'><?= $read_profile['error'] ?></h3>
        </section>
    <?php endif; ?>
<?php endif; ?>