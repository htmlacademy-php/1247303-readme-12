<?php 
session_start();

require_once('bootstrap.php');

if(!isset($_SESSION['user_id'])) {
    
    redirect_to_main();
};

$posts = [];


$types_content = get_content_types($connection);


$get_id = get_data_from_params('categories-id');

$posts = get_posts($connection, $get_id, NULL);

$user = get_user($connection, $_SESSION['user_id']);

$post_id_likes = get_data_from_params('post-id-likes');

$page_up =  get_data_from_params('page-up');

$page_count = 1;




if(isset($post_id_likes))
{
    toggle_likes_db($connection, $user['id'], $post_id_likes);
    
};




$page_content = include_template('popular.php', 
    [
     'posts' => $posts, 
     'types_content' => $types_content, 
     'get_id' => $get_id,
     'page_count' => $page_count,
     'connection' => $connection
    ]
);

$layout_content = include_template('layout.php', 
    [
     'user' => $user,  
     'content' => $page_content, 
     'title' => 'readme: блог, каким он должен быть',
     'header_user_nav' => ADD_POST,
     'main_class' => 'popular'
    ]
);

print($layout_content);