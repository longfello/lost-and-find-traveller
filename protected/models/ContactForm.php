<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
        public $category;
	public $subject;
        public $body;
	public $email;
	public $phone;
        public $type;
        public $typeLabel = array ('passenger'=>'Пассажир', 'driver'=>'Водитель', 'other'=>'Другое');

	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('category', 'required', 'message'=>'Пожалуйста, выберите категорию'),
                    array('subject', 'required', 'message'=>'Укажите тему сообщения'),
                    array('body', 'required', 'message'=>'Необходимо заполнить сообщение'),
                     array('email', 'required', 'message'=>'Укажите ваш Email для ответа'),
                    array('type', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
                        array('category, subject, body, email, phone, type', 'safe')
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
		);
	}
        
        public function getCategoryOptions()
        {
                return array(
                    array('id'=>63,'text'=>'У меня вопрос по работе сайта1','group'=>'passenger'),
                    array('id'=>64,'text'=>'Я потерял имя пользователя и/или пароль','group'=>'passenger'),
                    array('id'=>65,'text'=>'У меня техническая проблема','group'=>'passenger'),
                    array('id'=>66,'text'=>'Хочу уведомить модератора','group'=>'passenger'),
                    array('id'=>67,'text'=>'Другое','group'=>'passenger'),
                    array('id'=>116,'text'=>'У меня вопрос по работе сайта2','group'=>'driver'),
                    array('id'=>117,'text'=>'Я забыл имя пользователя/пароль','group'=>'driver'),
                    array('id'=>118,'text'=>'У меня технический вопрос','group'=>'driver'),
                    array('id'=>119,'text'=>'Хочу уведомить модератора','group'=>'driver'),
                    array('id'=>120,'text'=>'Другое','group'=>'driver'),
                    array('id'=>201,'text'=>'У меня вопрос по работе сайта3','group'=>'other'),
                    array('id'=>202,'text'=>'Я потерял имя пользователя и/или пароль','group'=>'other'),
                    array('id'=>203,'text'=>'У меня техническая проблема','group'=>'other'),
                    array('id'=>204,'text'=>'Хочу уведомить модератора','group'=>'other'),
                    array('id'=>205,'text'=>'Я журналист','group'=>'other'),
                    array('id'=>206,'text'=>'Я бизнесмен','group'=>'other'),
                    array('id'=>207,'text'=>'Я представитель сообщества','group'=>'other'),
                    array('id'=>208,'text'=>'Я бы хотел предложить партнёрство','group'=>'other'),
                    array('id'=>209,'text'=>'Другое','group'=>'other'),
                );
        }
}