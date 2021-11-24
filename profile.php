<?php

session_start();

require_once('bootstrap.php');

if (!isset($_SESSION['user_id'])) {
    redirect_to_main();
};


$id_user_profile = get_string_from_params('id');

$tabs_active = get_string_from_params('tabs');

$tabs_active_content = [];

$user = get_user($connection, $_SESSION['user_id']);

$user_profile = get_user($connection, $id_user_profile);

$is_follow = get_id_from_followers_id_and_from_user_id($connection, $user_profile['id'], $user['id']);

$title = "readme. Профиль пользователя {$user_profile['first_name']} {$user_profile['last_name']}";

$quantity_posts = get_quantity_post($connection, (int) $user_profile["id"]);

$quantity_followers = get_quantity_followers($connection, (int) $user_profile["id"]);

$posts = get_posts($connection, null, null, $user_profile['id']);

$post_id_comments = get_data_from_params('comments-post');

$posts_likes = get_liked_posts($connection, $user_profile['id']);

$post_id_likes = get_data_from_params('post-id-likes');

$user_profile_subscriptions = get_subscritions($connection, $user_profile['id']);

$subcrtions_user =  get_data_from_params('subscriptions');

$view_comment = get_data_from_params('view');

$view_all_comment = get_data_from_params('all');

$repost = get_data_from_params('repost');

$filter_form_data = null;

$form_errors = null;


if (!isset($tabs_active)) {
    $tabs_active = 'posts';
}

if (isset($subcrtions_user)) {
    toggle_subscription_db($connection, $user_profile['id'], $_SESSION['user_id']);
};


if (isset($post_id_likes)) {
    toggle_likes_db($connection, $_SESSION['user_id'], $post_id_likes);
};

if ($_POST) {
    $filter_form_data = array_map('filtered_form_data', $_POST);

    $form_errors = check_filled_value($filter_form_data, 'comment');

    $error_length_heading = check_length_str($filter_form_data["comment-text"], null, 4);



    if (isset($error_length_heading)) {
        $form_errors += ["comment-text" => "Текст комментария. {$error_length_heading}"];
    };

    if (!$form_errors) {
        add_comment_post_db($connection, $filter_form_data["comment-text"], $user['id'], $post_id_comments);
    }
};

switch ($tabs_active) {
    case "posts":
        $tabs_active_content =  [
            'connection' => $connection,
            'user_profile' => $user_profile,
            'user' => $user,
            'posts' => $posts,
            'post_id_likes' => $post_id_likes,
            'view_comment' => $view_comment,
            'view_all_comment' => $view_all_comment,
            'filter_form_data' => $filter_form_data,
            'form_errors' => $form_errors
        ];
    break;
    case "likes":
        $tabs_active_content =  [
            'connection' => $connection,
            'posts_likes' => $posts_likes
        ];
    break;

    case "subscriptions":

        $tabs_active_content =  [
            'connection' => $connection,
            'user_profile_subscriptions' => $user_profile_subscriptions,
            'user' => $user
        ];
    break;



}

if ($repost) {
    $key_post = get_data_from_params('key');

    add_repost($connection, $posts[$key_post], $user['id']);
};

$tab_content = include_template("profile-{$tabs_active}.php", $tabs_active_content);


$page_content = include_template(
    'profile.php',
    [
    'user_profile' => $user_profile,
    'quantity_posts' => $quantity_posts,
    'quantity_followers' => $quantity_followers,
    'tabs_active' => $tabs_active,
    'tab_content' => $tab_content,
    'connection' => $connection,
    'user' => $user,
    'is_follow' => $is_follow

]
);

$layout_content = include_template(
    'layout.php',
    [
     'user' => $user,
     'content' => $page_content,
     'title' => $title,
     'header_user_nav' => ADD_POST,
     'main_class' => 'profile'
    ]
);

print($layout_content);
