<?php

require_once('bootstrap.php');


$types_content = get_content_types($connection);

$get_id = get_data_from_params('categories-id');

$get_type_name = get_type_from_id($types_content, $get_id);

$filter_form_data = null;

$form_errors = null;

if($_POST) {

    $filter_form_data = array_map('filtered_form_data', $_POST);

    $form_errors = check_filled_value($filter_form_data, $get_type_name);

    $tags_arr = get_tags_form($filter_form_data, $get_type_name);

    $error_length_heading = check_length_str($filter_form_data["{$get_type_name}-heading"], 50);

    if(isset($error_length_heading)) {

        $form_errors += ["{$get_type_name}-heading" => "Заголовок. {$error_length_heading}"];
    };

    $tags_field_error = check_symbols($filter_form_data["{$get_type_name}-tags"]);

    if(isset($tags_field_error)) {

        $form_errors += ["{$get_type_name}-tags" => "Символы:  {$tags_field_error}  недопустимы для тегов"];
    };

    switch($get_type_name) {

        case "photo" :

            $error_file_or_link = check_photo_form($_FILES, $filter_form_data);
            $form_errors += $error_file_or_link;

            if(!$_FILES["file-photo"]["error"] && !$form_errors) {

                $filter_form_data += upload_files($_FILES);
            }
            elseif(!$form_errors) {

                $filter_form_data += download_out_files($filter_form_data["photo-url"]);
            };
        break;

        case "video" :

            $error_link_video = check_video_form($filter_form_data);
            $form_errors += $error_link_video;
        break;

        case "link" :

            $error_link = check_link($filter_form_data['post-link'], 'post-link');
            $form_errors += $error_link;
        break;

        case "quote" :

            $error_quote_text = check_length_str($filter_form_data["post-quote"], 70);

            if(isset($error_quote_text)){

                $form_errors +=  ["post-quote" => "Цитата. {$error_quote_text}"];
            };
        break;
    };


    if(!$form_errors){

        switch($get_type_name) {

            case "text" :
                add_post_text_db($connection, $filter_form_data);
            break;

            case "photo" :
                add_post_photo_db($connection, $filter_form_data);
            break;
    
            case "video" :
                add_post_video_db($connection, $filter_form_data);
            break;
    
            case "link" :
                add_post_link_db($connection, $filter_form_data);
            break;
    
            case "quote" :
                add_post_quote_db($connection, $filter_form_data);
            break;
        };

        $post_id = mysqli_insert_id($connection);

        if($tags_arr) {
            add_new_tags_db($connection, $tags_arr);
            add_relation_arr_db($connection, $post_id, $tags_arr);  
        };

        header("Location: post.php?post-id={$post_id}");

        exit();
    };
};

$add_post_form = include_template("add-post-form-{$get_type_name}.php",
    [
        'get_id' => $get_id,
        'filter_form_data' => $filter_form_data,
        'form_errors' => $form_errors
    ]
);


$add_post = include_template('adding-post.php',
    [
     'types_content' => $types_content,
     'get_id' => $get_id,
     'add_post_form' => $add_post_form
    ]
);

$layout_content = include_template('layout.php',
    [
     'user_name' => $user_name,
     'is_auth' => 1,
     'content' => $add_post,
     'title' => 'Добавить публикацию',
     'header_user_nav' => CLOSE_BTN
    ]
);


print($layout_content);

