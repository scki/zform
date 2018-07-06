<?php
include_once 'classes\CSender.php';
use zsend\classes\CSender;

parse_str($_POST['urlData'], $urlData);

$sender = new CSender($_POST['formData'], $urlData, [
    'mailTheme' => 'Заявка с лендинга',
    'mailTo' => 'your_mail@mail.ru'
]);

$sender->sendMail();
echo 'send success';
