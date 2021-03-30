<?php

$is_auth = rand(0, 1);

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
