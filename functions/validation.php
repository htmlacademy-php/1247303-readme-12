<?php

require_once('functions/template.php');

$form_errors= [];

/**
 * Проверяет длину строки, возвращает текст ошибки, если символов в строке больше запрашиваемых
 * @param string $str строка, которую необходимо проверить
 * @param int $length_str максимальное количество символов в строке
 */
function check_length_str(string $str, int $length_str)
{

    if(mb_strlen($str) > $length_str) {
        return "Количество символов не должен превышать {$length_str} знаков.";
    };

};


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
};


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
};

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
};

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
};

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
};

/**
 * Проверяет заполнены ли все обязательные поля в форме добавления постов. 
 * Если поля не заполнены, или заполнены некорректно - возвращает текст ошибки в виде массива. 
 * Если ошибок нет - возвращает пустой массив.
 * список обязательных поле в массиве $required_fields
 * @param array $text_inputs массив с данным из формы добавления поста
 * @param string $type_form тип формы добавления поста ('text','quote', 'photo', 'video', 'link')
 */
function check_filled_value(array $text_inputs, string $type_form):array
{
    $errors= [];

    $required_fields = [
        'text' => [
            'text-heading' => 'Заголовок. Это поле должно быть заполнено.',
            'post-text' => 'Текст поста. Это поле должно быть заполнено.'
        ],
        'quote'=> [
            'quote-heading' => 'Заголовок. Это поле должно быть заполнено.',
            'post-quote' => 'Цитата. Это поле должно быть заполнено.',
            'quote-author' => 'Автор цитаты. Это поле должно быть заполнено.'
        ],
        'photo' => [
            'photo-heading' => 'Заголовок. Это поле должно быть заполнено.'
        ],
        'video' => [
            'video-heading' => 'Заголовок. Это поле должно быть заполнено.',
            'video-url' => 'Ссылка Youtube. Это поле должно быть заполнено.'
        ],
        'link' => [
            'link-heading' => 'Заголовок. Это поле должно быть заполнено.',
            'post-link' => 'Ссылка. Это поле должно быть заполнено.'
        ]
    ];

    foreach($required_fields[$type_form] as $key => $field) {
        if(!$text_inputs[$key]) {
            $errors += [$key => $field];
        };
    };

    $error_length_str = check_length_str($text_inputs["{$type_form}-heading"], 50);

     
    if(isset($error_length_str)) {

        $errors += ["{$type_form}-heading" => "Заголовок. {$error_length_str}"];
    };

    $check_symbols = function($value)
    {
    $symbols = [",", "/", ".", "#", "!", "?","_"];//Todo - дополнить массив недопустимых символов

        foreach($symbols as $symbol) {
            if($value === $symbol){
                return true; 
            };
        };
    };

    $tags_form = str_split($text_inputs["{$type_form}-tags"]);

    $unallowable_symbols = array_filter($tags_form, $check_symbols);

    $unallowable_symbols_str = implode(" ", $unallowable_symbols);

    if($unallowable_symbols_str) {
        $errors += ["{$type_form}-tags" => "Символы:  {$unallowable_symbols_str}  недопустимы для тегов"];
    };
    
    return $errors;
};

/**
 * Функция-обертка для htmlspecialchars();
 */
$no_html = function($value) 
{
    return htmlspecialchars($value);
};