<?php if ((!empty($_SESSION["user"])) && ((int)$_SESSION["user"]['id_access'] === 1)) : ?>
    <section class="add-product wrapper">
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" class="input" placeholder="ИМЯ ТОВАРА" required>

            <select name="id_type" class="select" required="required">
                <option value="">Тип</option>
                <?php
                $sql_query = "SELECT * FROM types_products;";
                $answer = $mysqli->query($sql_query);
                while ($row = $answer->fetch_assoc()) : ?>
                    <option value="<?= $row['id_type_product'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <select name="id_gender" class="select">
                <option value="">Пол</option>
                <?php
                $sql_query = "SELECT * FROM genders;";
                $answer = $mysqli->query($sql_query);
                while ($row = $answer->fetch_assoc()) : ?>
                    <option value="<?= $row['id_gender'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <select name="id_brand" class="select" required="required">
                <option value="">Бренд</option>
                <?php
                $sql_query = "SELECT * FROM brands;";
                $answer = $mysqli->query($sql_query);
                while ($row = $answer->fetch_assoc()) : ?>
                    <option value="<?= $row['id_brand'] ?>"><?= $row['name'] ?></option>
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

            <input type="number" name="price" class="input" value="1" min=1 required>
            <input type="submit" name="add_goods" class="btn" value="Добавить товар">
        </form>
    </section>
<?php endif;
