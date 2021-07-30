<?php 

session_start();


require_once('bootstrap.php');

if(!isset($_SESSION['user_id'])) {

    redirect_to_main();
};

$types_content = get_content_types($connection);

$get_id = get_data_from_params('categories-id');

$posts = get_posts($connection, $get_id, NULL);

$user = get_user($connection, $_SESSION['user_id']);

$avatar_path = $user['avatar_path'];


$page_content = include_template('feed.php', 
    [
     'posts' => $posts, 
     'types_content' => $types_content, 
     'get_id' => $get_id,
     'connection' => $connection
    ]
);

$layout_content = include_template('layout.php', 
    [
     'user_name' => $user['first_name'] . " " . $user['last_name'],
     'is_auth' => (bool) $_SESSION['user_id'],
     'avatar_path' => $avatar_path,
     'content' => $page_content, 
     'title' => 'readme: блог, каким он должен быть',
     'header_user_nav' => ADD_POST,
     'main_class' => 'feed'
    ]
);

print($layout_content);