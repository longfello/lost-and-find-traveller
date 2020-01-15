<?php

class UloginWidget extends CWidget
{
    //параметры по-умолчанию
    private $params = array(
        'display'       =>  'small',
        'fields'        =>  'phone,first_name,last_name,email',
        'providers'     =>  'facebook,vkontakte,odnoklassniki,twitter,google,yandex,liveid,instagram',
        'hidden'        =>  'other',
        'redirect'      =>  '',
        'logout_url'    =>  '/ulogin/logout',
        'id'            =>  'uLogin',
    );

    public function run()
    {
        //подключаем JS скрипт
        Yii::app()->clientScript->registerScriptFile('http://ulogin.ru/js/ulogin.js', CClientScript::POS_HEAD);
        $this->render('uloginWidget', $this->params);
    }

    public function setParams($params)
    {
        $this->params = array_merge($this->params, $params);
    }
}
