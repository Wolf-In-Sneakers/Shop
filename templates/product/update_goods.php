<?php
if ((!empty($_SESSION["user"])) && ((int)$_SESSION["user"]['id_access'] === 1) && (empty($read_goods_one["error"]))) :
    $goods = $read_goods_one["goods"];
    ?>
    <section class="change_product wrapper">
        <div>
            <form action="?id_product=<?= $goods["id_product"] ?>" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" class="input" placeholder="ИМЯ ПРОДУКТА" value="<?= $goods["name"] ?>">

                <select name="id_type" class="select">
                    <option value=""><?= $goods["type"] ?></option>
                    <?php
                    $sql_query = "SELECT * FROM types_products;";
                    $answer = $mysqli->query($sql_query);
                    while ($row = $answer->fetch_assoc()) : ?>
                        <option value="<?= $row["id_type_product"] ?>"> <?= $row['name'] ?> </option>
                    <?php endwhile; ?>
                </select>

                <select name="id_gender" class="select">
                    <?php
                    if (!empty($goods["gender"]))
                        echo "<option value=''>{$goods["gender"]}</option>";
                    ?>
                    <option value=-1>Пол</option>
                    <?php
                    $sql_query = "SELECT * FROM genders;";
                    $answer = $mysqli->query($sql_query);
                    while ($row = $answer->fetch_assoc()) : ?>
                        <option value="<?= $row["id_gender"] ?>"> <?= $row["name"] ?> </option>
                    <?php endwhile; ?>
                </select>

                <select name="id_brand" class="select">
                    <option value=""><?= $goods["brand"] ?></option>
                    <?php
                    $sql_query = "SELECT * FROM brands;";
                    $answer = $mysqli->query($sql_query);
                    while ($row = $answer->fetch_assoc()) : ?>
                        <option value=" <?= $row["id_brand"] ?>"> <?= $row["name"] ?> </option>
                    <?php endwhile; ?>
                </select>

                <div class="input_file_wrapper">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    <input name="img[]" type="file" id="input_file" class="field input_file" multiple>

                    <label class="input_file_label_wrapper" for="input_file">
                        <div class="input_file_empty">Файл не вбран</div>
                        <div class="input_file_dtn">Выбрать</div>
                    </label>
                </div>

                <input type="number" name="price" class="input" value="<?= $goods["price"] ?>" min=1>
                <input type="submit" name="update_goods" class="btn" value="Изменить товар">
            </form>
            <form action="?id_product=<?= $goods["id_product"] ?>" method="POST">
                <button type="submit" name="delete_goods" class="btn" value="delete">Удалить товар</button>
            </form>
        </div>
    </section>
<?php endif; ?>