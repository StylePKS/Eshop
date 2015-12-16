<h3>Регистрация</h3>

<form method="POST" action="./index.php?action=signup">
    <?php require __DIR__ .'/_form-errors.php';?>
    <p>
        <label>Nickname: <input type="text" name="nickname"></label>
    </p>
    <p>
        <label>E-mail: <input type="text" name="email"></label>
    </p>
    <p>
        <label>Пароль: <input type="password" name="password"></label>
    </p>
    <p>
        <label>Введите проверочный код:</label>                 
    </p>
    <p>
        <img src="captcha/captcha.php" alt="Капча" />
    </p>
    <p>
        <input type="text" name="captcha" />
    </p>
    <p>
        <button type="submit">Зарегистрироваться</button>
    </p>
</form>