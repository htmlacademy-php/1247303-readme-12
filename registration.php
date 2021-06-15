<?php

require_once('bootstrap.php');

$filter_form_data = null;

$form_errors = null;

if($_POST){

    $filter_form_data = array_map('filtered_form_data', $_POST);

    $form_errors = check_filled_value($filter_form_data, 'registration');
    
    $form_errors += check_email($connection, $filter_form_data['email']);

    $form_errors += check_password($filter_form_data['password'], $filter_form_data['password-repeat']);

    if(!$_FILES["file-photo"]["error"]){

        $form_errors += check_type_file($_FILES["file-photo"]["type"]);

        (!$form_errors) ? $filter_form_data += upload_files($_FILES) : $filter_form_data;
    };

    if(!$form_errors) {
        $filter_form_data['password'] = set_password_hash($filter_form_data['password']);

        $filter_form_data['password-repeat'] = null;

        $filter_form_data += ['file-link' => ''];

        add_user_db($connection, $filter_form_data);
    };
};




$registration_form = include_template('registration-new-user.php',
    [
        'filter_form_data' => $filter_form_data,
        'form_errors' => $form_errors
     ]
);

$layout_content = include_template('layout.php',
    [
     'user_name' => $user_name,
     'is_auth' => 0,
     'content' => $registration_form,
     'title' => 'Добавить публикацию',
     'neader_user_nav' => HEADER_AUTH_REG
    ]
);


print($layout_content);