<?php


/**
 * Возвращает массив, содержащий хеш пароля обернутый в принятую в проекте структуру данных.
 * В случае ошибки формирования хеша пароля выводит на экран ошибку.
 * @param string $password Строка, содержащая пользовательский пароль
 * @return string
 *
 */
function set_password_hash(string $password): string
{
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($hash) {
        return $hash;
    } else {
        print("Ошибка формирования пароля");
        exit();
    }
}




/**
 * Возвращает подстроку идущую после заданного символа.
 * @param string $str Строка, из которую нужно извлечь подстроку
 * @param string $separ разделитель
 * @return string
 */
function get_last_elem(string $str, string $separ): string
{
    $parts = explode($separ, $str);

    return array_pop($parts);
}

/**
 * Скачивает внешний файл по ссылке в папку 'uploads'. При успешном скачивании возвращает внутренную ссылку на файл.
 * При ошибке - возвращает false
 * @param string $link ссылка, по которой размещен файл
 * @return array | bool
 */
function download_out_files(string $link): array | bool
{
    $previous_file_name = get_last_elem($link, "/");

    $type_file = get_last_elem($previous_file_name, ".");

    $random_name = mt_rand();

    $file_name = $random_name . '.'. $type_file;

    $file_url = "uploads/" . $file_name;

    $current = file_get_contents($link);

    $result = file_put_contents($file_url, $current);

    if ($result) {
        return ["img_path" => "{$file_url}"];
    }
    return false;
}


/**
 * Скачивает и переименовывает файл прикрепленный к форме. При успешном скачивании возвращает внутренную ссылку на файл.
 * При ошибке - возвращает false
 * @param array $files_arr массив $_FILES
 * @return array | bool
 */
function upload_files(array $files_arr): array | bool
{
    $random_name = mt_rand();

    $file_type = get_last_elem($_FILES["file-photo"]["name"], ".");

    $file_name = $random_name .".". $file_type;

    $file_url = "uploads/" . $file_name;

    $result = move_uploaded_file($files_arr['file-photo']['tmp_name'], $file_url);

    if ($result) {
        return ["img_path" => "{$file_url}"];
    }
    return false;
}


/**
 * Возвращает подстроки из строки в массиве данных с формы добавления поста, трансформируя заглавные буквы в строчные. Разделить - пробел.
 * Если $form_data - пуст - возвращает null
 * @param array $form_data массив данных из формы
 * @param string $type_form тип формы добавления поста ('text','quote', 'photo', 'video', 'link')
 * @return ?array
 */
function get_tags_form(array $form_data, string $type_form): ?array
{
    $tags_str = $form_data["{$type_form}-tags"];

    $tag_low = mb_convert_case($tags_str, MB_CASE_LOWER, "UTF-8");


    if ($tag_low) {
        $tags_arr = explode(" ", $tag_low);
        return $tags_arr;
    }

    return null;
}

/**
 * Запускает процесс регистрации нового пользователя.
 * В случае успешной регистрации возвращает true
 * @param array $filter_form_data массив данных из формы регистрации пользователя и ссылка на файл аватара,
 * если он был добавлен в форму
 * @param mysqli $connection объект соединения с БД
 * @return bool
 */
function register(mysqli $connection, array $filter_form_data): bool
{
    if (!$_FILES["file-photo"]["error"]) {
        $filter_form_data += upload_files($_FILES);
    } else {
        $filter_form_data += ['img_path' => 'img/no-avatar.jpg'];
    };

    $filter_form_data['password'] = set_password_hash($filter_form_data['password']);

    $filter_form_data['password-repeat'] = null;

    add_user_db($connection, $filter_form_data);

    return true;
}
/**
 * Проверяет, совпадение переданного пароля, с имеющимся хешем пароля пользователя в БД (`users`) по переданному e-mail.
 * Если хэши совпадают возвращает true, если пароли не совпадают - false
 * @param mysqli $connection объект соединения с БД
 * @param string $email е-мейл пользователя, по которому нужно проверить совпадения паролей
 * @param string $password_form пароль, который необходимо проверить
 * @return bool
 */
function check_user_db_hash(mysqli $connection, string $email, string $password_form): bool
{
    $hash_db = get_hash_by_mail($connection, $email);

    return password_verify($password_form, $hash_db);
}
/**
 * Записывает в $_SESSION id пользователя по переданному e-mail.
 * @param mysqli $connection объект соединения с БД
 * @param string $email е-мейл пользователя
 */
function authorization_user(mysqli $connection, string $email)
{
    $_SESSION['user_id'] = get_user_by_mail($connection, $email);
}
/**
 * Выполняет редирект на главную страницу (index.php)
 */
function redirect_to_main()
{
    header("Location: index.php");
}

/**
 * Выполняет редирект на предыдущую страницу
 */
function redirect_to_back()
{
    header("Location: {$_SERVER['HTTP_REFERER']}");
}

/**
 * Функция возвращает отфильтрованный массив (список постов) по id типа контента.
 * @param array $posts е-мейл пользователя, по которому нужно проверить совпадения паролей
 * @param int $type_id id типа контента
 * @return array
 */

function filtered_arr_posts_by_types(array $posts, int $type_id): array
{
    $filtered_posts = [];

    foreach ($posts as $post) {
        if ((int) $post['type_id'] === $type_id) {
            array_push($filtered_posts, $post);
        }
    }
    return $filtered_posts;
}
