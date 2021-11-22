<?php

require_once('bootstrap.php');

$filter_form_data = null;

$form_errors = null;


if (isset($_SESSION['user_id'])) {
    header("Location: feed.php");
};


if ($_POST) {
    $filter_form_data = array_map('filtered_form_data', $_POST);

    $form_errors = validate_authentication_form($filter_form_data, $connection);

    if (!$form_errors) {
        $form_errors = validate_form_password($connection, $filter_form_data['email'], $filter_form_data['password']);
    };

    if (!$form_errors) {
        session_start();

        authorization_user($connection, $filter_form_data['email']);

        header("Location: feed.php");

        exit();
    };
}


$login_form = include_template(
    'login.php',
    [
        'filter_form_data' => $filter_form_data,
        'form_errors' => $form_errors
     ]
);

$layout_content = include_template(
    'layout.php',
    [
     'content' => $login_form ,
     'title' => 'Регистрация',
     'header_user_nav' => HEADER_AUTH_REG,
     'main_class' => 'login'
    ]
);

print($layout_content);
