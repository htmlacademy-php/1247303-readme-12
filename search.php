<?php

session_start();

require_once('bootstrap.php');

if(!isset($_SESSION['user_id'])) {
    
    redirect_to_main();
};

$user = get_user($connection, $_SESSION['user_id']);

$search_query = null;

$posts = null;

$search_template = null;

$page_content = null;

$get_query = get_string_from_params('query');

$get_tag = get_string_from_params('tag');

$get_tag_id =  get_data_from_params('id');

$post_id_likes = get_data_from_params('post-id-likes');



if(isset($post_id_likes))
{
    toggle_likes_db($connection, $user['id'], $post_id_likes);

};



if(isset($get_query)){

    $search_query = trim($get_query);

    $posts = search_posts_db($connection, $search_query);
};

if(isset($get_tag) && isset($get_tag_id)){

    $posts_id = get_posts_id_for_tags_id($connection, $get_tag_id);

    $posts = get_posts_for_id($connection, $posts_id);

    $search_query = "#".trim($get_tag);

}


if(isset($posts)) 
{

    $page_content = include_template('search-results.php', 
        [
        'search_query' => $search_query,
         'posts' => $posts,
         'connection' => $connection
        ]
    );
    
}
else {
    $page_content = include_template('search-no-results.php', 
        [
        'search_query' => $search_query 
        ]
    );
}



$layout_content = include_template('layout.php', 
    [
     'user' => $user,  
     'content' => $page_content, 
     'title' => 'readme: блог, каким он должен быть',
     'header_user_nav' => ADD_POST,
     'main_class' => 'search-results'
    ]
);

print($layout_content);