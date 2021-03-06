<?php
/**
 * Контроллер регистрации.
 * Отвечает за обработку данных, пришедших из формы регистрации.
 */

// Массив с ошибками заполнения формы
$errors = array();

// Нам переданы из формы email & password
if (isset($_POST['email'], $_POST['password'], $_POST['password2'])) {
    // Отсечём пустые символы (пробелы и переносы строк)
    $captcha = trim($_POST['captcha']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    if (($captcha != $_SESSION['rand_code']) || ($captcha == "")) {         //Проверка совпадения введенной капчи со сгенерированной
        $errors[] = 'Введен неверный проверочный код.';                     //При несовпадении выводим ошибку
    }

    // Проверим e-mail на корректность
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $errors[] = 'Введён некорректный e-mail.';
    }

    // Проверим длину пароля
    if (mb_strlen($password) < 6) {
        $errors[] = 'Пароль не может быть менее 6 символов.';
    }

    // Проверим длину имени
    if (mb_strlen($name) < 2) {
        $errors[] = 'Имя не может быть короче 2 символов.';
    }

    if( $_POST['password'] !== $_POST['password2']) {       //Проверяем совпадение паролей
    $errors[] = 'Пароли не совпадают.'; 
    }

    // Найдём пользователя с таким же email в БД
    $user_with_same_email = db_select(
        'SELECT * FROM `users` WHERE `email` = :email LIMIT 1;', array(
        ':email' => $email
    ));

    // Если нашли - добавляем ошибку
    if (!empty($user_with_same_email)) {
        $errors[] = 'Этот e-mail уже занят.';
    }

    // Если ошибок нет - добавим новую запись в таблицу users
    if (empty($errors)) {
        // Мы храним только хэш пароля
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO `users` (`email`, `password_hash`, `name`) VALUES (:email, :password_hash, :name)';

        // Выполним SQL-запрос вставки записи, используя именованные параметры
        $affected_rows = db_query($sql, array(
            ':email' => $email,
            ':password_hash' => $password_hash,
            ':name' => $name
        ));

        // Вызов db_query должен вернуть 1, потому что мы вставляем 1 запись
        if ($affected_rows < 1) {
            $errors[] = 'Не удалось завершить регистрацию.';
        }
    }
       
    // Ошибок не произошло - отправляем пользователя на главную страницу
    if (empty($errors)) {
        $pdo = get_connection();    //Получаем соединение с MySQL
        $_SESSION['user_id'] = (int) $pdo->lastInsertId();      //Задаем id пользователю, который зарегистрировался
        browser_redirect('homepage');
    }
}

// Отображаем шаблон в случае если нам не пришли никакие данные из формы,
// либо при обработке данных формы возникли ошибки.
display_template('signup', array(
    'errors' => $errors
));