<?php 
require_once('bootstrap.php');

$post_id = get_data_from_params('post-id');

$post = get_posts($connection,NULL,$post_id);

$quantity_likes = get_count_likes($connection, $post_id);

$quantity_comments = get_count_comments($connection, $post_id);

$comments = get_comments($connection, $post_id);

$tags = get_tags_post($connection, $post_id);

$quantity_post_author = get_quantity_post($connection, $post[0]["user_id"]);

$followers_author = get_quantity_followers($connection, $post[0]["user_id"]);

$count_views = get_count_views($connection, $post[0]["user_id"]);

$post_content = include_template("post-{$post[0]["class_name"]}.php",['post' => $post]);

$title = $post[0]["title"];


$post = include_template('post-details.php', 
    [
     'post_content' => $post_content, 
     'post' => $post, 
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
     'user_name' => $user_name,
     'is_auth' => $is_auth,
     'content' => $post,
     'title' => $title,
     'button_close' => ''
    ]
);

print($layout_content);