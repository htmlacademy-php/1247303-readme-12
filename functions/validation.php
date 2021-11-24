<?php

require_once('bootstrap.php');

$form_errors = [];

/**
 * Проверяет идентичны ли пароль и повтор пароля, если данные не совпадают -
 * возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param string $password пароль (строка)
 * @param string $password_repeat повтор пароля (строка)
 * @return array массив с текстом ошибки или пустой массив, если $password и $password_repeat идентичны
 */
function check_password(string $password, string $password_repeat): array
{
    if ($password != $password_repeat) {
        return ['password' => 'Пароли и повтор пароля должны совпадать'];
    };
    return [];
}



/**
 * Проверяет e-mail пользователя (строку) если e-mail не корректен или он есть в БД -
 * возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param mysqli $connection объект соединения с БД
 * @param string $email строка, содержащая e-mail пользователя
 * @return array массив с текстом ошибки или пустой массив, если все проверки пройдены
 */
function check_email(mysqli $connection, string $email): ?array
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['email' => 'Введите корректный адрес электронной почты'];
    };

    if (get_user_by_mail($connection, $email)) {
        return ['email' => "Пользователь с адресом {$email} уже зарегистрирован"];
    };
    return [];
}
/**
 * Проверяет e-mail пользователя (строку) если e-mail не корректен или его в БД -
 * возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param mysqli $connection объект соединения с БД
 * @param string $email строка, содержащая e-mail пользователя
 * @return array массив с текстом ошибки или пустой массив, если все проверки пройдены
 */
function check_email_autn(mysqli $connection, string $email): ?array
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['email' => 'Введите корректный адрес электронной почты'];
    };

    if (!get_user_by_mail($connection, $email)) {
        return ['email' => "Пользователь с адресом {$email} не зарегистрирован. Необходима регистрация"];
    };
    return [];
}



/**
 * Проверяет длину строки, возвращает текст ошибки, если символов в строке больше запрашиваемых
 * @param string $str строка, которую необходимо проверить
 * @param int $length_str максимальное количество символов в строке
 * @return ?string
 */
function check_length_str(string $str, ?int $max_length_str = null, ?int $min_length_str = null): ?string
{
    if ($max_length_str && mb_strlen($str) > $max_length_str) {
        return "Количество символов не должно превышать {$max_length_str} знаков.";
    } elseif ($min_length_str && mb_strlen($str) < $min_length_str) {
        return "Количество символов не должно быть меньше {$min_length_str} знаков.";
    };
    return null;
}


/**
 * Проверяет ссылку на валидность, возвращает текст ошибки в виде массива, если ссылка не валидна.
 * Если ошибок нет - возвращает пустой массив
 * @param string $link ссылка, которую необходимо проверить
 * @param string $type_fields тип поля, для поставления в текст ошибки
 * @return array
 */
function check_link(string $link, string $type_fields): array
{
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        return ["{$type_fields}" => "Введенные данные не являются валидной ссылкой"];
    }
    return [];
}


/**
 * Проверяет ссылку на файл на валидность и файл по ссылку на доступность для скачивания.
 * В случае ошибок возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param string  $str_url ссылка для проверки
 * @return @array
 */
function check_link_file(string $str_url): array
{
    if (filter_var($str_url, FILTER_VALIDATE_URL)) {
        set_error_handler(function () {
        }, E_WARNING);
        if (!fopen($str_url, 'r')) {
            return ['photo-url' => 'Файл по ссылке недоступен для скачивания'];
        }
        return [];
        restore_error_handler();
    }
    return ['photo-url' => 'Введенные данные не являются валидной ссылкой'];
}

/**
 * Проверяет MIME тип файла, если тип файл не 'gif', 'jpeg', 'png' вернет текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param string $type_file строка, содержащая MIME тип файла
 * @return @array
 */
function check_type_file(string $type_file): array
{
    switch ($type_file) {
        case "image/png":
        case "image/jpeg":
        case "image/gif":
            return [];
            break;
        default:
            return ['file-photo' => 'Недопустимый тип файла. Поддерживаемые форматы изображений: png, jpeg, gif.'];
    };
}

/**
 * Проверяет данные из формы добавления поста "ВИДЕО",если данные не корректны
 * возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param array $text_inputs массив с данным из формы добавления поста "ВИДЕО"
 * @return array
 */
function check_video_form(array $text_inputs): array
{
    if (filter_var($text_inputs["video_path"], FILTER_VALIDATE_URL)) {
        return check_youtube_url($text_inputs["video_path"]);
    }

    return ['video_path' => 'Введенные данные не являются валидной ссылкой'];
}

/**
 * Проверяет данные из формы добавления поста "ФОТО", если данные не корректны -
 * возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param array $text_inputs массив с данным из формы добавления поста "ФОТО"
 * @param array $files_arr массив $_FILES
 * @return array 
 */
function check_photo_form(array $files_arr, array $text_inputs): array
{
    if ($files_arr["file-photo"]["error"] && !$text_inputs['photo-url']) {
        return [
                'photo-url' => 'Добавьте файл изображения или вставьте ссылку на него',
                'file-photo' => 'Добавьте файл изображения или вставьте ссылку на него',
              ];
    };


    if ($text_inputs['photo-url'] && $files_arr["file-photo"]["error"]) {
        return check_link_file($text_inputs['photo-url']);
    };

    if (!$files_arr["file-photo"]["error"]) {
        return check_type_file($files_arr["file-photo"]["type"]);
    };

    return [];
}


/**
 * Проверяет строку на наличие допустимых символов. Массив с недопустимы символоми в константе UNALLOWABLE_SYMBOLS
 * @param string $value строка
 * @return ?string возвращает строку с найденными недопустими символами или null если недопустимых символов не обнаружено
 */
function check_symbols(string $value): ?string
{
    $tags_form = str_split($value);

    $finded_symbols = array_filter($tags_form, function ($value) {
        foreach (UNALLOWABLE_SYMBOLS as $symbol) {
            if ($value === $symbol) {
                return true;
            };
        };
    });

    if ($finded_symbols) {
        $finded_symbols_str = implode(" ", $finded_symbols);

        return $finded_symbols_str;
    };

    return null;
}

/**
 * Проверяет заполнены ли все обязательные поля в форме.
 * Если поля не заполнены - возвращает текст ошибки в виде массива.
 * Если ошибок нет - возвращает пустой массив.
 * массив обязательных поле в константе массиве REQUIRED_FIELDS
 * @param array $text_inputs массив с данным из формы добавления поста
 * @param string $type_form тип формы ('text','quote', 'photo', 'video', 'link', полный список типов форм в REQUIRED_FIELDS/bootstrap.php)
 * @return ?array
 */
function check_filled_value(array $text_inputs, string $type_form): ?array
{
    $errors = [];

    foreach (REQUIRED_FIELDS[$type_form] as $key => $field) {
        if (!$text_inputs[$key]) {
            $errors += [$key => $field];
        };
    };

    return $errors;
}


/**
 * Фильтрует текстовые данные
 * Если строка пустая - присваивает null + работа htmlspecialchars
 * @param string 
 */
function filtered_form_data(?string $value): string
{
    $data = $value ?? null;

    $filtered = htmlspecialchars($data);

    return $filtered;
}


/**
 * Осуществляет валидацию формы обратной связи - регистрации нового пользователя.
 * Если при валидации возникли ошибки, возвращает их в виде массива с установленной в проекте структурой.
 * Если ошибок нет - возвращает пустой массив.
 * @param array $filter_form_data массив с данным из формы добавления регистрации пользователя
 * @param array глобальный массив $_FILES
 * @param mysqli $connection объект соединения с БД
 * @return array массив с ошибками, или пустой массив, если ошибок нет
 */
function validate_registration_form(array $filter_form_data, array $files, mysqli $connection): array
{
    $form_errors = check_filled_value($filter_form_data, 'registration');


    $form_errors += check_email($connection, $filter_form_data['email']);

    $form_errors += check_password($filter_form_data['password'], $filter_form_data['password-repeat']);

    if (!$files["file-photo"]["error"]) {
        $form_errors += check_type_file($files["file-photo"]["type"]);
    };

    return $form_errors;
}

/**
 * Осуществляет валидацию формы обратной связи - входа пользователя на сайт.
 * Если при валидации возникли ошибки, возвращает их в виде массива с установленной в проекте структурой.
 * Если ошибок нет - возвращает пустой массив.
 * @param array $filter_form_data массив с данным из формы добавления регистрации пользователя
 * @param mysqli $connection объект соединения с БД
 * @return array массив с ошибками, или пустой массив, если ошибок нет
 */
function validate_authentication_form(array $filter_form_data, mysqli $connection): array
{
    $form_errors = check_filled_value($filter_form_data, 'authentication');

    $form_errors += check_email_autn($connection, $filter_form_data['email']);

    return $form_errors;
}

/**
 * Осуществляет валидацию пароля пользователя.
 * Если при валидации возникли ошибки, возвращает их в виде массива с установленной в проекте структурой.
 * Если ошибок нет - возвращает пустой массив.
 * @param array $filter_form_data массив с данным из формы добавления регистрации пользователя
 * @param string $email email пользователя
 * @param string $password_form пароль, который необходит проверить на соответствии
 * @return array массив с ошибками, или пустой массив, если ошибок нет
 */
function validate_form_password($connection, $email, $password_form)
{
    $status_form_password = check_user_db_hash($connection, $email, $password_form);

    if ($status_form_password) {
        return [];
    }
    return ["password" => "Введен неверный пароль"];
}
