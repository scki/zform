<?php
include_once 'zsend/classes/CForm.php';
use zsend\classes\CForm;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link href="zsend/css/style.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="zsend/js/script.js"></script>
</head>
<body>
    <?php $form = new CForm('name', 'email', 'phone', 'message', 'agreed') ?>
    <?php $form->run(['id' => 'main-form'])?>
    <?php $form2 = new CForm('name', 'phone', null, false, 'agreed') ?>
    <?php $form2->run(['id' => 'main-form'])?>
</body>
</html>
