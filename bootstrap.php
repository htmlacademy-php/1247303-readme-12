<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$config = require 'config.php';

require_once('functions/db.php');
require_once('functions/template.php');
require_once('functions/request.php');
require_once('functions/date.php');
require_once('functions/template.php');
require_once('functions/validation.php');
require_once('functions/actions.php');


$connection = db_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);

const ADD_POST =  "<li><a class='header__post-button button button--transparent' href='add.php?categories-id=3'>Пост</a></li>"; 

const CLOSE_BTN = "<li><a class='header__post-button button header__post-button--active button--transparent' href='index.php'>Закрыть</a><li>";

const HEADER_AUTH_REG = "<li class='header__authorization'>
                        <a class='header__user-button header__authorization-button button' href='login.php'>Вход</a>
                     </li>
                     <li>
                        <a class='header__user-button header__user-button--active header__register-button button' href='registration.php' >Регистрация</a>
                     </li>";


const UNALLOWABLE_SYMBOLS = [",", "/", ".", "#", "!", "?","_"];

const REQUIRED_FIELDS = [
    'text' => [
        'title' => 'Заголовок. Это поле должно быть заполнено.',
        'content' => 'Текст поста. Это поле должно быть заполнено.'
    ],
    'quote'=> [
        'title' => 'Заголовок. Это поле должно быть заполнено.',
        'content' => 'Цитата. Это поле должно быть заполнено.',
        'author_quote' => 'Автор цитаты. Это поле должно быть заполнено.'
    ],
    'photo' => [
        'title' => 'Заголовок. Это поле должно быть заполнено.'
    ],
    'video' => [
        'title' => 'Заголовок. Это поле должно быть заполнено.',
        'video_path' => 'Ссылка Youtube. Это поле должно быть заполнено.'
    ],
    'link' => [
        'title' => 'Заголовок. Это поле должно быть заполнено.',
        'site_path' => 'Ссылка. Это поле должно быть заполнено.'
    ],
    'registration' => [
        'email' => 'Электронная почта. Это поле должно быть заполнено.',
        'login' => 'Логин. Это поле должно быть заполнено.',
        'first_name' => 'Имя. Это поле должно быть заполнено.',
        'last_name' => 'Фамилия. Это поле должно быть заполнено.',
        'password' => 'Пароль. Это поле должно быть заполнено.',
        'password-repeat' => 'Повтор пароля. Это поле должно быть заполнено.'
    ],
    'authentication' => [
        'email' => 'E-mail. Это поле должно быть заполнено.',
        'password' => 'Пароль. Это поле должно быть заполнено.'
    ],
    'comment' => [
        'comment-text' => 'Текст комментария. Это поле должно быть заполнено.'
    ]
];



