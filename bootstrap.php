<?php
$config = require 'config.php';

require_once('functions/db.php');
require_once('functions/template.php');
require_once('functions/request.php');
require_once('functions/date.php');

$is_auth = rand(0, 1);

$user_name = 'Иван Тестов'; // укажите здесь ваше имя

$connection = db_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);

const UNALLOWABLE_SYMBOLS = [",", "/", ".", "#", "!", "?","_"];

const REQUIRED_FIELDS = [
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

