<?php
if (!empty($_SESSION["user"])) : ?>
    <section class="auth wrapper">
        <a href="index.php" class="btn basket">Главная</a>
        <a href="profile.php" class="btn profile"><?= $_SESSION["user"]["name"] ?></a>
        <a href="basket.php" class="btn basket"> Корзина </a>
        <a href="exit.php" class="btn"><i class="fa fa-power-off" aria-hidden="true"></i></a>
    </section>
<?php else : ?>
    <section class="auth wrapper">
        <form action="" method="POST">
            <a href="index.php" class="btn">Главная</a>
            <a href="basket.php" class="btn"> Корзина </a>
            <input type="text" name="login" class="input" placeholder="Логин" required>
            <input type="password" name="password" class="input" placeholder="Пароль" required>
            <button type="submit" name="enter" class="btn" value="Войти">Войти<button>
        </form>
        <a href="registration.php" class="btn">Зарегестрироваться</a>
    </section>
<?php endif;
