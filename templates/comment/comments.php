<section class="comments wrapper">
    <form action="?id_product=<?= $id_product; ?>" method="POST">
        <?php if (empty($_SESSION["user"])) : ?>
            <input type="text" name="author" class="input" placeholder="ИМЯ" required>
        <?php endif ?>
        <textarea name="comment" cols="30" rows="10" class="textarea" placeholder="Ваш комментарий" required></textarea>
        <input type="submit" name="add_comment" class="btn" value="Добавить">
    </form>

    <?php
    $read_comment = $comment->read_comments($id_product);
    if (empty($read_comment['error'])) :
        foreach ($read_comment['comments'] as $comm) : ?>
            <article class="comment">
                <p class="comment_text">
                    <span class="comment_author"><?= $comm["author"] ?></span>:
                    <?= $comm["text"] ?>
                </p>
                <?php if ((!empty($_SESSION["user"])) && ((int)$_SESSION["user"]['id_access'] === 1)) : ?>
                    <form action="?id_product=<?= $id_product; ?>" method="POST">
                        <button type="submit" name="delete_comment" class="delete-btn"
                                value=<?= $comm["id_comment"]; ?>>Удалить
                            коммент
                        </button>
                    </form>
                <?php endif; ?>
            </article>
        <?php endforeach;
    else : ?>
        <section class="wrapper error">
            <h3 class='flex-center'><?= $read_comment['error'] ?></h3>
        </section>
    <?php endif; ?>
</section>