<?php
$read_goods_one = $product->read_goods_one($id_product);

if (empty($read_goods_one['error'])) :
    $goods = $read_goods_one["goods"]; ?>

    <h3 class="product-name flex-center wrapper"><?= $goods["name"] ?></h3>

    <section class='wrapper product-img flex-center'>
        <?php if (empty($read_goods_one["img"])) : ?>
            <article>
                <img src='images/not_image.png' alt='Featured item'>
            </article>
        <?php else : ?>
            <?php foreach ($read_goods_one["img"] as $img) : ?>
                <article>
                    <img src="<?= UPLOAD_DIR, $img["name"] ?>" alt="Item">
                    <?php if ((!empty($_SESSION["user"])) && ((int)$_SESSION["user"]['id_access'] === 1)) : ?>
                        <form action="?id_product=<?= $goods["id_product"] ?>" method="POST">
                            <button type="submit" class="delete-btn" value="<?= $img["id_image"] ?>" name="delete_img">
                                Удалить
                            </button>
                            <button type="submit" class="delete-btn" value="<?= $img["id_image"] ?>"
                                    name="set_main_img">Сделать основной
                            </button>
                        </form>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section class="wrapper product-desc">
        <div>
            <h3>Цена: <span><?= $goods["price"] ?></span></h3>
            <h3>Просмотров: <span><?= ++$goods["viewed"] ?></span></h3>
            <h3>Like: <span class="btn_like_value"><?= $goods["liked"] ?></span>
                <?php if (!empty($_SESSION["user"])) : ?>
                    <button class="btn_like delete-btn" data-id_product=<?= $goods["id_product"] ?>>Like</button>
                <?php endif; ?>
            </h3>
            <h3>Артикль: <span><?= $goods["id_product"] ?></span></h3>
            <h3>Тип: <span><?= $goods["type"] ?></span></h3>
            <?php if (!empty($goods["gender"])) : ?>
                <h3>Пол: <span><?= $goods["gender"] ?></span></h3>
            <?php endif; ?>
            <h3>Бренд: <span><?= $goods["brand"] ?></span></h3>
            <br>
            <div>
                <button class="btn add_in_basket" data-id_product=<?= $goods["id_product"] ?>>Добавить в корзину
                </button>
            </div>
        </div>
    </section>
<?php else : ?>
    <section class="wrapper error">
        <h3 class='flex-center'><?= $read_goods_one['error'] ?></h3>
    </section>
    <?php exit;
endif;
