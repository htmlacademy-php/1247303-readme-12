<?php 
session_start();

require_once('bootstrap.php');

if(!isset($_SESSION['user_id'])) {
    
    redirect_to_main();
};

$posts = [];


$types_content = get_content_types($connection);


$get_id = get_data_from_params('categories-id');

$page =  get_data_from_params('page');

$user = get_user($connection, $_SESSION['user_id']);

$post_id_likes = get_data_from_params('post-id-likes');

$sort = get_string_from_params('sort');

$sort_type = get_string_from_params('type');

$sort_by = get_string_from_params('by');

$sorting = "desc";

$sorting_arr = [];

$offset = 0;

if($page) {
    
    $offset = $page * 6;
};


if(isset($post_id_likes))
{
    toggle_likes_db($connection, $user['id'], $post_id_likes);
    
};

$posts = get_posts($connection, $get_id, null, null, $offset);

if($sort_by && $sort_by === "desc"){
    $sorting = "asc";
};

if($sort) {
    $sorting_arr['type'] = $sort_type;
    $sorting_arr['by'] = mb_strtoupper($sort_by);
    $posts = get_posts($connection, $get_id, null, null, $offset, $sorting_arr);

}



$page_content = include_template('popular.php', 
    [
     'posts' => $posts, 
     'types_content' => $types_content, 
     'get_id' => $get_id,
     'page' => $page,
     'sorting' => $sorting,
     'sort_type' => $sort_type,
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