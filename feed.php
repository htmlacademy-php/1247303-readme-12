<?php


session_start();


require_once('bootstrap.php');

if (!isset($_SESSION['user_id'])) {
    redirect_to_main();
}

$user = get_user($connection, $_SESSION['user_id']);


$types_content = get_content_types($connection);

$get_id = get_data_from_params('categories-id');

$user = get_user($connection, $_SESSION['user_id']);

$posts_likes = get_posts_id_for_user_likes($connection, $user['id']);

$post_id_likes = get_data_from_params('post-id-likes');

$repost = get_data_from_params('repost');


if (isset($post_id_likes)) {
    toggle_likes_db($connection, $user['id'], $post_id_likes);
}


if (isset($posts_likes)) {
    $posts = get_posts_for_id($connection, $posts_likes);


    if (isset($get_id)) {
        $filtered_posts = filtered_arr_posts_by_types($posts, $get_id);

        $posts = $filtered_posts;
    }
} else {
    $posts = [];
}

if ($repost) {
    $key_post = get_data_from_params('key');

    add_repost($connection, $posts[$key_post], $user['id']);
}

$page_content = include_template(
    'feed.php',
    [
        'posts' => $posts,
        'types_content' => $types_content,
        'get_id' => $get_id,
        'connection' => $connection,
        'user' => $user
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'user' => $user,
        'content' => $page_content,
        'title' => 'readme. Лента публикаций',
        'header_user_nav' => ADD_POST,
        'main_class' => 'feed'
    ]
);

print($layout_content);
