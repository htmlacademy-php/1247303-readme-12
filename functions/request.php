<?php

/**
 * Функция получает и возвращает данные (число) из GET-запроса по параметру
 * @param string $param
 * @return ?int число из запроса
 */
function get_data_from_params(string $param): ?int
{
    $data = $_GET[$param]?? null;

    if (is_null($data)) {
        return null;
    }
    if (is_numeric($data)) {
        return (int) $data;
    } else {
        exit('Неверный параметр в запросе');
    }
}

/**
 * Функция получает и возвращает данные (строку) из GET-запроса по параметру
 * @param string $param
 * @return string|null строка из запроса
 */
function get_string_from_params(string $param): ?string
{
    $data = $_GET[$param]?? null;

    if (is_null($data)) {
        return null;
    }
    if (true) {
        return $data;
    } else {
        exit('Неверный параметр в запросе');
    }
}
