<?php 
$config = require 'config.php';


require_once('functions/db.php');
require_once('functions/template.php');
require_once('functions/request.php');
require_once('functions/date.php');

$is_auth = rand(0, 1);

$user_name = ''; // укажите здесь ваше имя


$connection = db_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);

$types_content = get_content_types($connection);


$get_id = get_data_from_params('categories-id');

$posts = get_posts($connection, $get_id, NULL);


$page_content = include_template('main.php', 
    [
     'posts' => $posts, 
     'types_content' => $types_content, 
     'get_id' => $get_id,
     'connection' => $connection
    ]
);

$layout_content = include_template('layout.php', 
    [
     'is_auth' => $is_auth,
     'content' => $page_content, 
     'title' => 'readme: блог, каким он должен быть'
    ]
);

print($layout_content);