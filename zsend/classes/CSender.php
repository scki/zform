<?php
/**
 * Created by PhpStorm.
 * User: krong
 * Date: 28.06.2018
 * Time: 13:34
 */

namespace zsend\classes;


/**
 * Класс для отправки письма
 * Class CSender
 * @package classes
 */
class CSender
{
    /**
     * @var mixed|string
     */
    public $mailTheme = 'Заявка с лендинга';
    /**
     * @var mixed|string
     */
    public $mailTo = '';

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $agreed;

    /**
     * @var mixed
     */
    private $lidEmail;

    /**
     * @var array
     */
    private $urlData;

    /**
     * CSender constructor.
     * @param array $data
     * @param array $urlData
     * @param array $mailData
     */
    public function __construct(array $data, array $urlData, array $mailData = [])
    {
        if ($data['name']) $this->name = '<li>Имя: <b>' . $data['name'] . '</b></li>';
        if ($data['phone']) $this->phone = '<li>Телефон: <b>' . $data['phone'] . '</b></li>';
        if ($data['email']) $this->email = '<li>E-mail: <b>' . $data['email'] . '</b></li>';
        if ($data['message']) $this->message = '<li>Описание задачи: <b>' . $data['message'] . '</b></li>';
        if ($data['agreed']) $this->agreed = '<li>Согласие: <b> дано (' . $data['agreed'] . ')</b></li>';

        $this->lidEmail = $data['email'];

        if ($urlData) $this->urlData = $urlData;

        if ($mailData['mailTheme']) $this->mailTheme = $mailData['mailTheme'];
        if ($mailData['mailTo']) $this->mailTo = $mailData['mailTo'];
    }

    /**
     * Метод отправляет письмо получателю по заданному формату.
     */
    public function sendMail()
    {
        $subject = $this->mailTheme; //Загаловок сообщения
        $message = $utm = '';

        if (is_array($this->urlData)) {
            foreach ($this->urlData as $key => $item) {
                $utm .= '<li>' . $key . ': ' . $item . '</li>';
            }
        } else $utm .= '<li>UTM-меток не обнаружено</li>';

        /* Текст нащего сообщения можно использовать HTML теги */
        $message .= '<html>';
        $message .= '<head>';
        $message .= '<title>' . $this->mailTheme . '</title>';
        $message .= '</head>';
        $message .= '<body>';
        $message .= '<h4> Данные по лиду:</h4>';
        $message .= '<ul>';
        $message .= $this->name;
        $message .= $this->email;
        $message .= $this->phone;
        $message .= $this->message;
        $message .= $this->agreed;
        $message .= '<hr>';
        $message .= '</ul>';
        $message .= '<h4>ЮТМ метки:</h4>';
        $message .= '<ul>';
        $message .= $utm;
        $message .= '<hr>';
        $message .= '</ul>';
        $message .= '</body>';
        $message .= '</html>';
        /* КОНЕЦ Текст нащего сообщения можно использовать HTML теги */

        $headers = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
        $headers .= "From: Заявка на подбор оборудования <'$this->mailTo'>\r\n"; //Наименование и почта отправителя
        mail($this->mailTo, $subject, $message, $headers); //Отправка письма с помощью функции mail

        if ($this->lidEmail) {
            $subject = 'Вы оставии заявку на сайте dixten.ru'; //Загаловок сообщения

            /* Текст нащего сообщения можно использовать HTML теги */
            $message = '<html>';
            $message .= '<head>';
            $message .= '<title>Вы оставии заявку на сайте dixten.ru</title>';
            $message .= '</head>';
            $message .= '<body>';
            $message .= '<p>Вы оставили заявку на сайте <a href="http://dixten.com/catalog/"></a>dixten.ru</p>';
            $message .= '<p>Наши менеджеры скоро свяжутся с Вами</p>';
            $message .= '</body>';
            $message .= '</html>';
            /* КОНЕЦ Текст нащего сообщения можно использовать HTML теги */

            $headers = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
            $headers .= "From: <'$this->mailTo'>\r\n"; //Наименование и почта отправителя
            mail($this->lidEmail, $subject, $message, $headers); //Отправка письма с помощью функции mail
        }
    }
}