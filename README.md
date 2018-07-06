# Создание формы
## Подключение формы
Форма отправки заявки почты на электронный адрес.  
Для установки формы на странице, необходимо скопировать файлы каталог **_zsend_** в корневой каталог сайта.  
Для подключения формы в файле **index.php** нужно подключить основной класс формы, вставив в самое начало файла следующие строчки:

```php
<?php
include_once 'zsend/classes/CForm.php';
use zsend\classes\CForm;
?>
```
Так же необходимо подключить, в тег <*head*> скрипты и файлы стилей, использующиеся в форме:
```html
<head>
    <link href="zsend/css/style.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="zsend/js/script.js"></script>
</head>
```
Форма использует плагин *JQuery*, если он уже подключен, то подключать повторно не обязательно.

## Создание формы
Для создании формы, необходимо создать экземпляр формы:
```php
<?php $form = new CForm('name', 'email', 'phone', 'message', 'agreed') ?>
```
В экземпляр формы передаются следующие параметры:  
**_name_**  - поле с именем;  
**_email_** - поле с электронной почтой;  
**_phone_** - поле с телефоном;  
**_message_** - поле с комментарием;  
**_agreed_** - поле согласия с обработкой персональных данных;  

Для того, что бы убрать какое-либо поле, на его месте необходимо написать **_false_** или **_null_**
Например:
```php
<?php $form = new CForm('name', 'phone', null, false, 'agreed') ?>
```

Далее необходимо запустить форму:
```php
<?php $form->run(['id' => 'main-form'])?>
```
Метод **_run()_** может принимать в себя следующие параметры:  
**_id_** — id формы;  
**_class_** — класс формы;  
**_action_** — экшен формы;  
**_method_** — метод форм;  
**_name_** — имя формы;  

## Настройки отправки письма  
Для отправки писем необходимо сконфигурировать файл **send.php**:  
**_mailTheme_** - тема письма;  
**_mailTo_** - адрес на который будут приходить письма;
