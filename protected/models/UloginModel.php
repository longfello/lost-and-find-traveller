<?php

class UloginModel extends CModel {
    public $id;
    public $identity;
    public $network;
    public $email;
    public $phone;
    public $full_name;
    public $token;
    public $error_type;
    public $error_message;
    public $errors;

    private $uloginAuthUrl = 'http://ulogin.ru/token.php?token=';

    public function rules() {
        return array(
            array('id,identity,network,token', 'required'),
            array('email', 'email'),
            array('phone', 'length', 'max' => 20),
            array('identity,network,email', 'length', 'max'=>255),
            array('full_name', 'length', 'max'=>55),
        );
    }

    public function attributeLabels() {
        return array(
            'network'=>'Сервис',
            'identity'=>'Идентификатор сервиса',
            'email'=>'eMail',
            'full_name'=>'Имя',
        );
    }

    public function getAuthData() {

        $authData = json_decode(file_get_contents($this->uloginAuthUrl.$this->token.'&host='.$_SERVER['HTTP_HOST']),true);

        $this->setAttributes($authData);
        if (isset($authData['first_name']) && isset($authData['last_name'])) {
          $this->full_name = $authData['first_name'].' '.$authData['last_name'];
        }
    }

    public function login() {
        $identity = new UloginUserIdentity();
        if ($identity->authenticate($this)) {
            Yii::app()->user->login($identity,0);
            return true;
        }
        $this->errors = $identity->errors;
        return false;
    }

    public function attributeNames() {
        return array(
            'identity'
            ,'network'
            ,'email'
            ,'phone'
            ,'full_name'
            ,'token'
            ,'error_type'
            ,'error_message'
        );
    }
}