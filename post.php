<?php 
session_start();

require_once('bootstrap.php');

if(!isset($_SESSION['user_id'])) {
    
    redirect_to_main();
};

$post_id = get_data_from_params('post-id');

$posts = get_posts($connection,NULL,$post_id);

$quantity_likes = get_count_likes($connection, $post_id);

$quantity_comments = get_count_comments($connection, $post_id);

$comments = get_comments($connection, $post_id);

$tags = get_tags_post($connection, $post_id);

$quantity_post_author = get_quantity_post($connection, $posts[0]["user_id"]);

$followers_author = get_quantity_followers($connection, $posts[0]["user_id"]);

$count_views = get_count_views($connection, $posts[0]["id"]);

$post_content = include_template("post-{$posts[0]["class_name"]}.php",['post' => $posts]);

$title = $posts[0]["title"];

$user = get_user($connection, $_SESSION['user_id']);

$avatar_path = $user[0]['avatar_path'];

$post = include_template('post-details.php', 
    [
     'post_content' => $post_content, 
     'post' => $posts, 
     'quantity_likes' => $quantity_likes, 
     'quantity_comments' => $quantity_comments, 
     'comments' => $comments,
     'quantity_post_author' => $quantity_post_author,
     'followers_author' => $followers_author,
     'count_views' => $count_views,
     'tags' => $tags
    ]
);

$layout_content = include_template('layout.php', 
    [
     'user_name' => $user[0]['first_name'] . " " . $user[0]['last_name'],
     'avatar_path' => $avatar_path,
     'content' => $post,
     'title' => $title,
     'header_user_nav' => ADD_POST
    ]
);


print($layout_content);