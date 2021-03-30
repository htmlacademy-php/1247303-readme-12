<?php 
/**
 * Функция получает данные из GET-запроса по параметру
 * @param string $parametr параметр, по которому необходимо получить данные из GET-запроса 
 * @return int число из запроса
 */
function get_data_from_params(string $parametr):?int
{
    $data = $_GET["{$parametr}"];

    if(isset($data)) {

        if(is_numeric($data)) {
            return (int) $data;
        }
        else {
            exit('Неверный параметр в запросе');
        }
    }
    else {
        return NULL;
    }
};