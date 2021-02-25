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

function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
    
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Возвращает количество прошедшего времени от даты публикации поста
 *
 * @param string $date дата публикации поста Строка даты в формате 'Y-m-d H:i:s'
 * @return string Относительное количество времени в формате:
 *
 * если от $date до текущего времени прошло меньше 60 минут, то формат будет вида “% минут назад”;
 * если от $date до текущего времени прошло больше 60 минут, но меньше 24 часов, то формат будет вида “% часов назад”;
 * если от $date до текущего времени прошло больше 24 часов, но меньше 7 дней, то формат будет вида “% дней назад”;
 * если от $date до текущего времени прошло больше 7 дней, но меньше 5 недель, то формат будет вида “% недель назад”;
 * если от $date до текущего времени прошло больше 5 недель, то формат будет вида “% месяцев назад”.
 */
function relativeDate(string $date): string 
{
    $today = new DateTime('now');
    $timePublication = new DateTime($date);
    $interval = $today->diff($timePublication);
    
    $days = intval($interval->format("%a"));
    $hours = intval($interval->format("%H"));
    $minutes = intval($interval->format("%i"));
    
    if($days > 35) { 
        $monts = floor($days/30);
        $declensionMonts = get_noun_plural_form($monts, "месяц", "месяца", "месяцев");
        return "{$monts} {$declensionMonts} назад";
    }
    if($days > 7 & $days < 35) {
        $weeks = floor($days/4);
        $declensionWeeks = get_noun_plural_form($weeks, "неделя", "недели", "недель");
        return "{$weeks} {$declensionWeeks} назад";
    }
    if($days < 7 && $days > 0) {
        $declensionDays = get_noun_plural_form($days, "день", "дня", "дней");
        return "{$days} {$declensionDays} назад";
    }
    if ($days === 0) {
        if($hours < 24 & $hours > 0) {
            $declensionHours = get_noun_plural_form($hours, "час", "часа", "часов");
            return "{$hours} {$declensionHours} назад";  
        }
        if($hours === 0) {
            $declensionMinutes = get_noun_plural_form($minutes, "минута", "минуты", "минут");
            return "{$minutes} минут назад";  
        }
    }
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