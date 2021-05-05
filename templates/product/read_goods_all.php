<?php
$read_goods_all = $product->read_goods_all();
if (empty($read_goods_all['error'])) : ?>
    <section class="wrapper featured-items">
        <?php foreach ($read_goods_all["goods"] as $goods) : ?>
            <article class="item">
                <a href="product.php?id_product=<?= $goods["id_product"] ?>">
                    <div class="wrapper-img">
                        <img src="<?= (!empty($goods['image_name'])) ? UPLOAD_DIR . $goods["image_name"] : "images/not_image.png"; ?>"
                             alt="Изображение товара">
                    </div>
                    <p class="item-name"><?= $goods["name"] ?></p>
                    <div class="item-icons">
                        <span class="item-price"><?= $goods["price"] ?> руб</span>
                        <div>
                            <span class="item-like"> <i class="fa fa-thumbs-o-up"
                                                        aria-hidden="true"></i><?= $goods["liked"] ?></span>
                            <span class="item-viewed"> <i class="fa fa-eye"
                                                          aria-hidden="true"></i><?= $goods["viewed"] ?></span>
                        </div>
                    </div>
                </a>
                <form action="" method="POST">
                    <button type="button" class="delete-btn add_in_basket" data-id_product=<?= $goods["id_product"] ?>>
                        Добавить в корзину
                    </button>
                    <?php if ((!empty($_SESSION["user"])) && ((int)$_SESSION["user"]['id_access'] === 1)) : ?>
                        <input type="hidden" name="id_product" value="<?= $goods["id_product"] ?>">
                        <button type="submit" name="delete_goods" class="delete-btn" value="Удалить товар">Удалить
                        </button>
                    <?php endif; ?>
                </form>
            </article>
        <?php endforeach; ?>
    </section>
<?php else : ?>
    <section class="wrapper error">
        <h3 class='flex-center'><?= $read_goods_all['error'] ?></h3>
    </section>
<?php endif;
