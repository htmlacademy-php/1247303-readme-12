<?php

session_start();

require_once('bootstrap.php');

if (!isset($_SESSION['user_id'])) {
    redirect_to_main();
};

$post_id = get_data_from_params('post-id');

$posts = get_posts($connection, null, $post_id);

$quantity_likes = get_count_likes($connection, $post_id);

$quantity_comments = get_count_comments($connection, $post_id);

$comments = get_comments($connection, $post_id);

$tags = get_tags_post($connection, $post_id);

$quantity_post_author = get_quantity_post($connection, $posts[0]["user_id"]);

$followers_author = get_quantity_followers($connection, $posts[0]["user_id"]);

$count_views = get_count_views($connection, $posts[0]["id"]);

$post_content = include_template("post-{$posts[0]["class_name"]}.php", ['post' => $posts]);

$title = $posts[0]["title"];

$user = get_user($connection, $_SESSION['user_id']);

$post_id_likes = get_data_from_params('post-id-likes');

$view_all_comments = get_data_from_params('view-all-comments');

$filter_form_data = null;

$form_errors = null;

$repost = get_data_from_params('repost');


if (isset($post_id_likes)) {
    toggle_likes_db($connection, $user['id'], $post_id_likes);
};

if ($view_all_comments) {
    $comments = get_comments($connection, $post_id, null);
}

if ($_POST) {
    $filter_form_data = array_map('filtered_form_data', $_POST);

    $form_errors = check_filled_value($filter_form_data, 'comment');

    $error_length_heading = check_length_str($filter_form_data["comment-text"], null, 4);


    if (isset($error_length_heading)) {
        $form_errors += ["comment-text" => "Текст комментария. {$error_length_heading}"];
    };

    if (!$form_errors) {
        add_comment_post_db($connection, $filter_form_data["comment-text"], $user['id'], $posts[0]["id"]);

        redirect_to_back();
    }
};

if ($repost) {
    add_repost($connection, $posts[0], $user['id']);
}


$post = include_template(
    'post-details.php',
    [
     'post_content' => $post_content,
     'post' => $posts,
     'user' => $user,
     'quantity_likes' => $quantity_likes,
     'quantity_comments' => $quantity_comments,
     'comments' => $comments,
     'quantity_post_author' => $quantity_post_author,
     'followers_author' => $followers_author,
     'count_views' => $count_views,
     'tags' => $tags,
     'view_all_comments' => $view_all_comments,
     'filter_form_data' => $filter_form_data,
     'form_errors' => $form_errors,
     'connection' => $connection

    ]
);

$layout_content = include_template(
    'layout.php',
    [
     'user' => $user,
     'content' => $post,
     'title' => $title,
     'header_user_nav' => ADD_POST,
     'main_class' => 'publication'
    ]
);


print($layout_content);
