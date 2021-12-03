<?php

describe('Функция check_length_str', function () {
    context('при передачи строки корректной длины', function () {
        it('возвращает null', function () {
            $str = "123456789";
            $result = check_length_str($str, 10);
            expect($result)->toBe(null);
        });
    });

    context('при передачи строки длины превышающей допустимое значение', function () {
        it('возвращает текст ошибки', function () {
            $str = "123456789";
            $result = check_length_str($str, 5);
            expect($result)->toBe("Количество символов не должен превышать 5 знаков.");
        });
    });
});
