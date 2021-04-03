<?php 

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
};

/**
 * Возвращает количество прошедшего времени от даты публикации поста
 *
 * @param string $date дата публикации поста, cтрока даты в формате 'Y-m-d H:i:s'
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

    if ($timePublication->getTimestamp() >= $today->getTimestamp()) {
        return '0 минут назад';
    }
    if($days >= 35) { 
        $months = floor($days/30);
        $declensionmonths = get_noun_plural_form($months, "месяц", "месяца", "месяцев");
        return "{$months} {$declensionmonths} назад";
    }
    if($days >= 7 && $days < 35) {
        $weeks = floor($days/7);
        $declensionWeeks = get_noun_plural_form($weeks, "неделя", "недели", "недель");
        return "{$weeks} {$declensionWeeks} назад";
    }
    if($days < 7 && $days > 0) {
        $declensionDays = get_noun_plural_form($days, "день", "дня", "дней");
        return "{$days} {$declensionDays} назад";
    }

    if($hours < 24 && $hours > 0) {
        $declensionHours = get_noun_plural_form($hours, "час", "часа", "часов");
        return "{$hours} {$declensionHours} назад";  
    }
    
    $declensionMinutes = get_noun_plural_form($minutes, "минута", "минуты", "минут");
    return "{$minutes} {$declensionMinutes} назад";  
};
