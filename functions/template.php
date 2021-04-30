<?php

function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
* Возращает урезанную строку. По умолчанию урезает строку до 300 символов и прибавляет ссылку "Читать далее".
* Принимает два параметра:
* @param  string $str Строка, которую необходима урезать
* @param  int $length Число, количество символов до которого нужно урезать строку. Значение по умолчанию - 300

* Если длина строки ($str) менше установленного количества символов ($length) - будет возвращена исходная строка.
*/
function cutStr(string $str, int $length = 300) : string 
{

    if(mb_strlen($str) > $length) {

        $words = explode(" ", $str);
   
        $currentTextLength = 0;

        $strOut = [];

        foreach($words as $word) {

            if($currentTextLength < $length) {
                $currentTextLength += mb_strlen($word) + 1;
                $strOut[] = $word;
            } 
            else {
                break;
            }  

        }
        $cuttingStr = implode(" ", $strOut) . "...";

        return "<p>{$cuttingStr}</p><a class='post-text__more-link' href='#'>Читать далее</a>";
    }

    return "<p>{$str}</p>";   
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}


/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id(string $youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}


/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return array Ошибку если валидация не прошла
 */
function check_youtube_url(string $url):array
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {}, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return ['video-url' => "Видео по такой ссылке не найдено. Проверьте ссылку на видео"];
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return ['video-url' => "Видео по такой ссылке не найдено. Проверьте ссылку на видео"];
    }

    return [];
}

/**
 * Возвращает наименование типа контента ('text','quote', 'photo', 'video', 'link')
 * @param array $types_content массив вида [id => 'тип контента']
 * @param int $id - id типа контента
 */
function get_type_from_id(array $types_content, ?int $id):string
{
  foreach($types_content as $type) {
      if((int) $type['id'] === $id) {
          return $type['class_name'];
      }
  }
}