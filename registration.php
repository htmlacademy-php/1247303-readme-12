<?php
session_start();

require_once('bootstrap.php');

$filter_form_data = null;

$form_errors = null;

if($_POST){

    $filter_form_data = array_map('filtered_form_data', $_POST);

    $form_errors = validate_registration_form($filter_form_data, $_FILES, $connection);


    if(!$form_errors) {
        
        if(register($connection, $filter_form_data)){
            header("Location: popular.php");
        };
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
     'content' => $registration_form,
     'title' => 'Регистрация',
     'header_user_nav' => HEADER_AUTH_REG,
     'main_class' => 'registration'
    ]
);


print($layout_content);