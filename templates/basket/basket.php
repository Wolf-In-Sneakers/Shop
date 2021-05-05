<h2 class="basket-header wrapper">Корзина</h2>
<section class="wrapper basket-items">
    <?php
    if (!empty($_SESSION["basket"])) :
        foreach ($_SESSION["basket"] as $goods) : ?>
            <article class="basket-item" data-id_product=<?= $goods["id_product"] ?>>
                <a href="product.php?id_product=<?= $goods["id_product"] ?>" class="wrapper-img">
                    <img src="<?= (!empty($goods['image_name'])) ? UPLOAD_DIR_SMALL . $goods["image_name"] : "images/not_image.png"; ?>"
                         alt="Изображение товара">
                </a>
                <a href="product.php?id_product=<?= $goods["id_product"] ?>" class="item-name"><?= $goods["name"] ?></a>
                <input type="number" class="basket-quantity input" value="<?= $goods["quantity"] ?>" min=0>
                <div>
                    <span class="item-price"
                          data-price="<?= $goods["price"] ?>"><?= $goods["price"] ?>руб * <?= $goods["quantity"] ?> = <?= $goods["price"] * $goods["quantity"] ?>руб</span>
                </div>
                <button class="delete-btn delete_in_basket">Удалить</button>
            </article>
        <?php endforeach; ?>
        <p class="total-price flex-center"> Итого: <?= $basket->basket_total() ?>руб </p>
        <div class="flex-center">
            <a href="order.php" class="order_btn btn">Оформить заказ</a>
        </div>
        <br>
        <div class="flex-center">
            <button class="clear_basket delete-btn">Очистить корзину
            </button>
        </div>
    <?php endif; ?>
</section>