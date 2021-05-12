<?php

require_once('bootstrap.php');

$form_errors= [];

/**
 * Проверяет длину строки, возвращает текст ошибки, если символов в строке больше запрашиваемых
 * @param string $str строка, которую необходимо проверить
 * @param int $length_str максимальное количество символов в строке
 */
function check_length_str(string $str, int $length_str):?string
{
    if(mb_strlen($str) > $length_str) {
        return "Количество символов не должен превышать {$length_str} знаков.";
    }
    return null;
}


/**
 * Проверяет ссылку на валидность, возвращает текст ошибки в виде массива, если ссылка не валидна. 
 * Если ошибок нет - возвращает пустой массив
 * @param string $link ссылка, которую необходимо проверить
 * @param string $type_fields тип поля, для поставления в текст ошибки
 */
function check_link(string $link, string $type_fields):array
{
    if(!filter_var($link, FILTER_VALIDATE_URL)) {
        return ["{$type_fields}" => "Введенные данные не являются валидной ссылкой"];
    }
    return [];  
}


/**
 * Проверяет ссылку на файл на валидность и файл по ссылку на доступность для скачивания. 
 * В случае ошибок возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param string  $str_url ссылка для проверки
 */
function check_link_file(string $str_url):array
{

    if(filter_var($str_url, FILTER_VALIDATE_URL)){

        set_error_handler(function () {}, E_WARNING);
         if(!fopen($str_url, 'r')){
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
 */
function check_type_file(string $type_file):array
{

    switch($type_file) {
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
 */
function check_video_form(array $text_inputs):array
{

    if(filter_var($text_inputs["video-url"], FILTER_VALIDATE_URL)) {
        return check_youtube_url($text_inputs["video-url"]);
    }

    return ['video-url' => 'Введенные данные не являются валидной ссылкой'];
}

/**
 * Проверяет данные из формы добавления поста "ФОТО", если данные не корректны -
 * возвращает текст ошибки в виде массива, если ошибок нет - возвращает пустой массив
 * @param array $text_inputs массив с данным из формы добавления поста "ФОТО"
 * @param array $files_arr массив $_FILES 
 */
function check_photo_form(array $files_arr, array $text_inputs):array
{

    if($files_arr["file-photo"]["error"] && !$text_inputs['photo-url']) {

       return [
                'photo-url' => 'Добавьте файл изображения или вставьте ссылку на него',
                'file-photo' => 'Добавьте файл изображения или вставьте ссылку на него',                                              
              ];
    };


    if($text_inputs['photo-url'] && $files_arr["file-photo"]["error"]) {

        return check_link_file($text_inputs['photo-url']);
    };

    if(!$files_arr["file-photo"]["error"]) {

        return check_type_file($files_arr["file-photo"]["type"]);
    };
      
    return [];
}


/**
 * Проверяет строку на наличие допустимых символов. Массив с недопустимы символоми в константе UNALLOWABLE_SYMBOLS
 * @param string $value строка
 * @return string возвращает строку с найденными недопустими символами или null если недопустимых символов не обнаружено
 */
function check_symbols(string $value):?string
{
    $tags_form = str_split($value);

    $finded_symbols = array_filter($tags_form, function($value){

        foreach(UNALLOWABLE_SYMBOLS as $symbol) {

            if($value === $symbol){
                return true; 
            };
        };
    });

    if($finded_symbols){

        $finded_symbols_str = implode(" ", $finded_symbols);

        return $finded_symbols_str;
    };

    return null;
}

/**
 * Проверяет заполнены ли все обязательные поля в форме добавления постов. 
 * Если поля не заполнены - возвращает текст ошибки в виде массива. 
 * Если ошибок нет - возвращает пустой массив.
 * массив обязательных поле в константе массиве REQUIRED_FIELDS
 * @param array $text_inputs массив с данным из формы добавления поста
 * @param string $type_form тип формы добавления поста ('text','quote', 'photo', 'video', 'link')
 */
function check_filled_value(array $text_inputs, string $type_form):array
{
    $errors = [];

    foreach(REQUIRED_FIELDS[$type_form] as $key => $field) {
        if(!$text_inputs[$key]) {
            $errors += [$key => $field];
        };
    };
    
    return $errors;
}


/**
 * Фильтрует текстовые данные
 * Если строка пустая - присваивает null + работа htmlspecialchars
 * @param string $value 
 */
function filtered_form_data(?string $value):string
{
    $data = $value ?? null;

    $filtered = htmlspecialchars($data);

    return $filtered;
}