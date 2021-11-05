<?php
session_start();

require_once('bootstrap.php');

if(!isset($_SESSION['user_id'])) {
    
    redirect_to_main();
};

$id_user_profile = get_string_from_params('id');

$tabs_active = get_string_from_params('tabs');

if(!isset($tabs_active)){
    $tabs_active = 'posts';
}

$tabs_active_content = [];

$user = get_user($connection, $_SESSION['user_id']);

$user_profile = get_user($connection, $id_user_profile);

$title = "readme. Профиль пользователя {$user_profile['first_name']} {$user_profile['last_name']}";

$quantity_posts = get_quantity_post($connection, (int) $user_profile["id"]);

$quantity_followers = get_quantity_followers($connection, (int) $user_profile["id"]);

$posts = get_posts($connection, NULL, NULL, $user_profile['id']);

$post_id_comments = get_string_from_params('comments-post');

$posts_likes = get_liked_posts($connection, $user_profile['id']);

$post_id_likes = get_data_from_params('post-id-likes');

$user_profile_subscriptions = get_subscritions($connection, $user_profile['id']);

$subcrtions_user =  get_data_from_params('subscriptions');

if(isset($subcrtions_user)) 
{
    toggle_subscription_db($connection, $user_profile['id'], $_SESSION['user_id']);
};


if(isset($post_id_likes))
{
    toggle_likes_db($connection, $_SESSION['user_id'], $post_id_likes);
    
};



switch($tabs_active) {
    case "posts" :
        $tabs_active_content =  [
            'connection' => $connection,
            'user_profile' => $user_profile,
            'posts' => $posts 
        ];
    break;
    case "likes" :
        $tabs_active_content =  [
            'connection' => $connection,
            'posts_likes' => $posts_likes 
        ];
    break;

    case "subscriptions" :

        $tabs_active_content =  [
            'connection' => $connection,
            'user_profile_subscriptions' => $user_profile_subscriptions,
            'user' => $user
        ];
    break;

    

}

$tab_content = include_template("profile-{$tabs_active}.php", $tabs_active_content);


$page_content = include_template('profile.php', 
[
    'user_profile' => $user_profile,
    'quantity_posts' => $quantity_posts,
    'quantity_followers' => $quantity_followers,
    'tabs_active' => $tabs_active,
    'tab_content' => $tab_content,
    'connection' => $connection,
    'user' => $user

] 
);

$layout_content = include_template('layout.php', 
    [
     'user' => $user,  
     'content' => $page_content, 
     'title' => $title,
     'header_user_nav' => ADD_POST,
     'main_class' => 'profile'
    ]
);

print($layout_content);