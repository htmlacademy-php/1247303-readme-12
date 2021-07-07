<?php 

/**
 * Возвращает массив, содержащий хеш пароля обернутый в принятую в проекте структуру данных. 
 * В случае ошибки формирования хеша пароля выводит на экран ошибку.
 * @param string $password Строка, содержащая пользовательский пароль
 * @return array
 * 
 */
function set_password_hash(string $password):string
{
   $hash = password_hash($password, PASSWORD_DEFAULT);
    //var_dump($hash);
   if($hash) {
        return $hash;
   }
   else{
    print("Ошибка формирования пароля");
    exit();
   }; 
}




/**
 * Возвращает подстроку идущую после заданного символа. 
 * @param string $str Строка, из которую нужно извлечь подстроку
 * @param string $separ разделитель
 */
function get_last_elem(string $str, string $separ):string
{
    $parts = explode($separ, $str);

    return array_pop($parts);
}

/**
 * Скачивает внешний файл по ссылке в папку 'uploads'. При успешном скачивании возвращает внутренную ссылку на файл. 
 * При ошибке - возвращает false
 * @param string $link ссылка, по которой размещен файл
 */
function download_out_files($link)
{
    $previous_file_name = get_last_elem($link, "/");
    
    $type_file = get_last_elem($previous_file_name,".");

    $random_name = mt_rand();

    $file_name = $random_name . '.'. $type_file;

    $file_url = "uploads/" . $file_name;

    $current = file_get_contents($link);

    $result = file_put_contents($file_url, $current);

    if($result) {
        return ["file-link" => "{$file_url}"];
    }
    return false;
}


/**
 * Скачивает и переименовывает файл прикрепленный к форме. При успешном скачивании возвращает внутренную ссылку на файл. 
 * При ошибке - возвращает false
 * @param array $files_arr массив $_FILES
 */
function upload_files($files_arr)
{
    $random_name = mt_rand();

    $file_type = get_last_elem($_FILES["file-photo"]["name"], ".");

    $file_name = $random_name .".". $file_type;

    $file_url = "uploads/" . $file_name;
  
    $result = move_uploaded_file($files_arr['file-photo']['tmp_name'], $file_url);
 
    if($result) {
        return ["file-link" => "{$file_url}"];
    }
    return false;
}


/**
 * Возвращает подстроки из строки в массиве данных с формы добавления поста, трансформируя заглавные буквы в строчные. Разделить - пробел.
 * Если $form_data - пуст - возвращает null
 * @param array $form_data массив данных из формы
 * @param string $type_form тип формы добавления поста ('text','quote', 'photo', 'video', 'link')
 */
function get_tags_form(array $form_data, string $type_form):?array
{
    $tags_str = $form_data["{$type_form}-tags"];

    $tag_low = mb_convert_case($tags_str , MB_CASE_LOWER, "UTF-8");

    if($tag_low) {
        $tags_arr = explode(" ", $tag_low);
        return $tags_arr;
    };

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
function register(mysqli $connection, array $filter_form_data):bool
{
    if(!$_FILES["file-photo"]["error"]){

        $filter_form_data += upload_files($_FILES);
    }
    else{
        $filter_form_data += ['file-link' => ''];
    };
          
    $filter_form_data['password'] = set_password_hash($filter_form_data['password']);

    $filter_form_data['password-repeat'] = null;

    add_user_db($connection, $filter_form_data);

    return true;
}

