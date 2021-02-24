<?php 

$is_auth = rand(0, 1);

$user_name = ''; // укажите здесь ваше имя

$posts = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'author' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'author' => 'Владик',
        'avatar' => 'userpic.jpg'
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'author' => 'Виктор',
        'avatar' => 'userpic-mark.jpg'
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'author' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'author' => 'Владик',
        'avatar' => 'userpic.jpg'
    ]
];

/**
* Возращает урезанную строку. По умолчанию урезает строку до 300 символов и прибавляет ссылку "Читать далее".
* Принимает два параметра:
* @param  string $str Строка, которую необходима урезать
* @param  int $length Число, количество символов до которого нужно урезать строку. Значение по умолчанию - 300

* Если длина строки ($str) менше установленного количества символов ($length) - будет возвращена исходная строка.
*/
function cutStr(string $str, int $length = 300) : string {

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

$page_content = include_template('main.php', ['posts' => $posts]);

$layout_content = include_template('layout.php', ['is_auth' => $is_auth,'content' => $page_content, 'title' => 'readme: блог, каким он должен быть']);

print($layout_content);