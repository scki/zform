<?php
/**
 * Created by PhpStorm.
 * User: krong
 * Date: 27.06.2018
 * Time: 10:59
 */

namespace zsend\classes;


/**
 * Класс служит для валидации полей phone, email, name, agreed
 * Class CFormValidate
 * @package zsend\classes
 */
class CFormValidate
{
    /**
     * Метод принимает в себя любую строку, убирает все символы, кроме цифр.
     * Если цифр 10, то нормализует номер телефона в вид +7 (ХХХ) ХХХ-ХХ-ХХ.
     * Если цифр 11 и первые цифры равны 7 или 8, так же нормализует вид, при этом заменив 8 на 7 в начале номера.
     * Иначе выдает ошибку 'wrong', для последующей обработки в js-скрипте.
     * @param $phone
     * @return mixed|string
     */
    public static function phone($phone)
    {
        $phone = preg_replace("/[^0-9]/", '', $phone);
        $firstChar = mb_substr($phone, 0, 1);

        if (mb_strlen($phone) == 10) {
            $phone = preg_replace('/^(\d{3})(\d{3})(\d{2})(\d+)$/iu', '+7 ($1) $2-$3-$4', $phone);
        } elseif (
            mb_strlen($phone) == 11 &&
            ($firstChar == '7' || $firstChar == '8')
        ) {
            if ($firstChar == '8') $firstChar = '7';

            $phone = preg_replace('/^(\d{1})(\d{3})(\d{3})(\d{2})(\d+)$/iu', '+'.$firstChar . ' ($2) $3-$4-$5', $phone);
        } else {
            $phone = 'wrong';
        }

        return $phone;
    }

    /**
     * Метод валидации электронной почты по регулярному выражению.
     * @param $email
     * @return string
     */
    public static function email($email)
    {
        $patternEmail = '/[0-9a-z._-]+@[0-9a-z]+.[a-z]{2,6}/';

        if (preg_match($patternEmail, $email)) {
            $emailReplace = $email;
        } else {
            $emailReplace = 'wrong';
        }

        return $emailReplace;
    }

    /**
     * Метод валидации имени.
     * @param $name
     * @return string
     */
    public static function name($name)
    {
        if (!empty($name)) {
            $nameReplace = $name;
        } else {
            $nameReplace = 'wrong';
        }

        return $nameReplace;
    }

    /**
     * Метод валидации галочки 'согласие'
     * @param $agreed
     * @return string
     */
    public static function agreed($agreed)
    {
        if ($agreed) {
            $agreedReplace = $agreed;
        } else {
            $agreedReplace = 'wrong';
        }

        return $agreedReplace;
    }
}
