<?php

session_start();

require_once('bootstrap.php');

$form_errors = null;

$filter_form_data = null;


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
        authorization_user($connection, $filter_form_data['email']);

        header("Location: feed.php");
    };
}


$layout_content = include_template(
    'main.php',
    [
        'title' => 'readme: блог, каким он должен быть',
        'filter_form_data' => $filter_form_data,
        'form_errors' => $form_errors
    ]
);

print($layout_content);
