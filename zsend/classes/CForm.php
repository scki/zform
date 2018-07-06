<?php
/**
 * Created by PhpStorm.
 * User: krong
 * Date: 27.06.2018
 * Time: 10:59
 */

namespace zsend\classes;

/**
 * Class CForm
 * Класс служит для автоматической генерации форм на странице
 * Форму вожможно создавать как автоматически с помощью методов __construct() и run(),
 * так и вручную через методы beginForm(), endForm().
 * Для создания полей в ручном режиме, можно воспользоваться методами:
 * textAreaField(), textField(), numberField(), checkboxField(), radioField().
 *
 * @package zsend\classes
 */
Class CForm
{
    /**
     * Поле для ввода Имени.
     * @var null
     */
    public $name;
    /**
     * Поле для ввода Телефона.
     * @var null
     */
    public $phone;
    /**
     * Поле для ввода Электронной почты.
     * @var null
     */
    public $email;
    /**
     * Поле для ввода Комментария.
     * @var null
     */
    public $message;
    /**
     * Поле согласия на обработку персональных данных.
     * @var null
     */
    public $agreed;

    /**
     * Путь до папки с иконками для полей name, phone,
     * email и message.
     * @var string
     */
    public $srcIcons = 'zsend/img/';

    /**
     * @var string
     */
    public $classForm = 'form-default';

    /**
     * CForm constructor.
     * Принимает будущие поля формы, для автоматической генерации.
     * @param null $name
     * @param null $phone
     * @param null $email
     * @param null $message
     * @param null $agreed
     * @param null $srcIcons
     */
    public function __construct(
        $name = null,
        $phone = null,
        $email = null,
        $message = null,
        $agreed = null,
        $srcIcons = null
    )
    {
        if ($name) $this->name = $name;
        if ($phone) $this->phone = $phone;
        if ($email) $this->email = $email;
        if ($message) $this->message = $message;
        if ($agreed) $this->agreed = $agreed;

        if ($srcIcons) $this->srcIcons = $srcIcons;
    }

    /**
     * Метод для начала формы.
     * Генерирует тег form (начало формы) вида <form>
     * Принимает массив с параметрами, содержащими внутри себя:
     * id,
     * class - по умолчанию $this->classForm,
     * action,
     * method
     * name.
     * Параметр $horizontally добавляет класс 'horizontally', для размещения формы горизонтально.
     * @param array $params
     * @param bool $horizontally
     * @return string
     */
    public function beginForm(array $params = [], $horizontally = false)
    {
        $className = ' cform';
        $horizontally ? $className .= ' horizontally' : $className;
        if (!$params['class'])  $params['class'] = $this->classForm;

        $params['id'] ? $id = 'id="' . $params['id'] . '"' : $id = '';
        $params['class'] ? $class = 'class="' . $params['class'] . $className . '"' : $class = '';
        $params['action'] ? $action = 'action="' . $params['action'] . '"' : $action = '';
        $params['method'] ? $method = 'method="' . $params['method'] . '"' : $method = '';
        $params['name'] ? $name = 'name="' . $params['name'] . '"' : $name = '';


        return '<form ' . $action . $class . $id . $method . $name . '>';
    }

    /**
     * Метод закрывает тег form и добавляет кнопку с надписью $btnName.
     * @param $btnName
     * @return string
     */
    public function endForm($btnName = 'Отправить')
    {

        return '<button class="send-button" type="submit" disabled>' . $btnName . '</button> </form>';
    }

    /**
     * Метод служит для нормализации вида аттрибутов id, class, style, required, value, checked, placeholder
     * передаваемых в массиве $attributes для поля $fieldName
     *
     * @param $fieldName
     * @param array $attributes
     * @return string
     */
    public function addDefaultAttributes($fieldName, array $attributes = [])
    {
        $fieldName ? $name = 'name="' . $fieldName . '"' : $name = '';

        $attributes['id'] ? $id = 'id="' . $attributes['id'] . '"' : $id = 'input-' . $fieldName;
        $attributes['class'] ? $class = 'class="' . $attributes['class'] . '"' : $class = '';
        $attributes['style'] ? $style = 'style="' . $attributes['style'] . '"' : $style = '';
        $attributes['required'] ? $required = 'required="' . $attributes['required'] . '"' : $required = '';
        $attributes['value'] ? $value = 'value="' . $attributes['value'] . '"' : $value = '';
        $attributes['checked'] ? $checked = 'checked="' . $attributes['checked'] . '"' : $checked = '';
        $attributes['placeholder'] ? $placeholder = 'placeholder="' . $attributes['placeholder'] . '"' : $placeholder = '';

        return $id . $class . $name . $placeholder . $required . $value . $checked . $style;

    }

    /**
     * Метод генерирует поле типа $fieldType, с имененм $fieldName и атрибутами $attributes.
     * Аттрибуты передаются для нормализации в метод addDefaultAttributes.
     * @param $fieldName
     * @param $fieldType
     * @param array $attributes
     * @return string
     */
    public function field($fieldName, $fieldType, array $attributes = [])
    {
        $attributes['id'] ? $id = $attributes['id'] : $id = 'input-' . $fieldName;

        $field = '<div class="input-field">';
        $attributes['icon'] ? $field .= '<img src="' . $this->srcIcons . $fieldName . '.png">' : $field .= '';
        $field .= $attributes['label'] ?
            $label = '<label for="' . $id . '" style="' . $attributes['labelOptions']['style'] . '">' . $attributes['label'] . '</label>' :
            $label = '';

        $field .= '<input type="' . $fieldType . '"' . $this->addDefaultAttributes($fieldName, $attributes) . '/>';
        $field .= '</div>';

        return $field;
    }

    /**
     * Метод генерирует поле text с именем $fieldName и атрибутами $attributes.
     * Аттрибуты передаются для нормализации в метод addDefaultAttributes.
     * @param $fieldName
     * @param array $attributes
     * @return string
     */
    public function textAreaField($fieldName, array $attributes = [])
    {
        $attributes['id'] ? $id = $attributes['id'] : $id = 'input-' . $fieldName;
        $attributes['rows'] ? $rows = 'rows="' . $attributes['rows'] . '"' : $rows = '';
        $attributes['cols'] ? $cols = 'cols="' . $attributes['cols'] . '"' : $cols = '';
        $attributes['text'] ? $text = $attributes['text'] : $text = '';

        $field = '<div class="input-field">';
        $attributes['icon'] ? $field .= '<img src="' . $this->srcIcons . $fieldName . '.png" style="top: 15px;">' : $field .= '';
        $field .= $attributes['label'] ?
            $label = '<label for="' . $id . '">' . $attributes['label'] . '</label>' :
            $label = '';

        $field .= '<textarea ' . $this->addDefaultAttributes($fieldName, $attributes) . $rows . $cols . ' name="text">' . $text . '</textarea>';
        $field .= '</div>';

        return $field;
    }

    /**
     * Метод для создания текстового поля <input type='text'> с массивом аттрибутов $params
     * @param $fieldName
     * @param array $params
     * @return string
     */
    public function textField($fieldName, array $params = [])
    {
        return $this->field($fieldName, 'text', $params);
    }

    /**
     * @param $fieldName
     * @param array $params
     * @return string
     */
    public function numberField($fieldName, array $params = [])
    {
        return $this->field($fieldName, 'number', $params);
    }

    /**
     * Метод для создания чекбоксов <input type='checkbox'> с массивом аттрибутов $params
     * @param $fieldName
     * @param array $params
     * @return string
     */
    public function checkboxField($fieldName, array $params = [])
    {
        return $this->field($fieldName, 'checkbox', $params);
    }

    /**
     * Метод для создания радиокнопок <input type='radio'> с массивом аттрибутов $params
     * @param $fieldName
     * @param array $params
     * @return string
     */
    public function radioField($fieldName, array $params = [])
    {
        return $this->field($fieldName, 'radio', $params);
    }

    /**
     * Метод генерирует форму с параметрами, передаваемыми в массиве $params.
     * Поля формы берутся из метода __construct, в который передаются именя полей.
     * В параметры передаются
     * @param array $params
     */
    public function run(array $params = [])
    {
        echo $this->beginForm($params);

        if ($this->name) {
            echo $this->textField('name', [
                'id' => $params['id'] . '-name',
                'class' => '',
                'required' => false,
                'icon' => true,
                'placeholder' => 'Представьтесь',
                'label' => '',
            ]);
        }

        if ($this->email) {
            echo $this->textField('email', [
                'id' => $params['id'] . '-email',
                'class' => '',
                'required' => false,
                'icon' => true,
                'placeholder' => 'Ваш e-mail',
                'label' => '',
            ]);
        }

        if ($this->phone) {
            echo $this->textField('phone', [
                'id' => $params['id'] . '-phone',
                'class' => '',
                'required' => false,
                'icon' => true,
                'placeholder' => 'Ваш телефон',
                'label' => '',
            ]);
        }

        if ($this->message) {
            echo $this->textAreaField('message', [
                'id' => $params['id'] . '-message',
                'class' => '',
                'required' => false,
                'rows' => 1,
                'cols' => 1,
                'icon' => true,
                'placeholder' => 'Опишите вашу задачу',
                'label' => '',
            ]);
        }

        if ($this->agreed) {
            echo $this->checkboxField('agreed', [
                'id' => $params['id'] . '-agreed',
                'class' => '',
                'placeholder' => 'Соглашение',
                'checked' => true,
                'icon' => false,
                'style' => 'width: 20px; position: absolute; top: -4px; left: -35px;',
                'label' => 'С соглашением о <a href="#winpolit">персональных данных</a> ознакомлен и согласен',
                'labelOptions' => [
                    'style' => 'margin-left: 44px; display: block;',
                ]
            ]);
        }

        echo $this->endForm();
    }
}
