<?php
/**
 * Created by PhpStorm.
 * User: krong
 * Date: 27.06.2018
 * Time: 14:22
 */
include_once 'classes/CFormValidate.php';

use zsend\classes\CFormValidate;
//use classes\CFormValidate;

$response = $fields = [];

parse_str($_POST['formData'], $formData);
parse_str($_POST['urlData'], $urlData);

foreach ($_POST['fields'] as $field) {
    $fields[$field] = $field;
}


if ($fields['phone']) $response['phone'] = CFormValidate::phone($formData['phone']);
if ($fields['email']) $response['email'] = CFormValidate::email($formData['email']);
if ($fields['name']) $response['name'] = CFormValidate::name($formData['name']);
if ($fields['agreed']) $response['agreed'] = CFormValidate::agreed($formData['agreed']);

if ($formData['message']) $response['message'] = $formData['message'];

if (array_search('wrong', $response)){
    print_r(json_encode($response));
} else {
    $response = array_merge($response, ['answer' => 'validate success']);
    echo json_encode($response);
}

