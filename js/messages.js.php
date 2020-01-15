<?php
header('Content-Type: text/javascript; charset=utf-8');
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../YiiRoot/framework/yii.php';
$config=dirname(__FILE__).'/../../protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
?>
/*messages.js*/
messages = Array();
messages["ac_not_found"] = "<?=Yii::t('messages', 'Ничего не найдено')?>";
messages["submit_error"] = "<?=Yii::t('messages', 'Пожалуйста исправьте указанные ошибки')?>";
messages["date_error"] = "<?=Yii::t('messages', 'Укажите дату')?>";
messages["name_error"] = "<?=Yii::t('messages', 'Укажите Ваше имя')?>";
messages["sname_error"] = "<?=Yii::t('messages', 'Укажите Вашу фамилию')?>";
messages["phone_error"] = "<?=Yii::t('messages', 'Укажите контактный телефон')?>";
messages["id_error"] = "<?=Yii::t('messages', 'Укажите ID найденного объекта')?>";

messages["poput_select_settlement_error"] = "<?=Yii::t('messages', 'Выберите населённый пункт из списка')?>";
messages["poput_place_error"] = "<?=Yii::t('messages', 'Укажите место/ориентир')?>";
messages["poput_days_of_week_error"] = "<?=Yii::t('messages', 'Укажите дни недели')?>";
messages["poput_days_of_month_error"] = "<?=Yii::t('messages', 'Укажите дни месяца')?>";
messages["poput_from"] = "<?=Yii::t('messages', 'Откуда')?>";
messages["poput_city"] = "<?=Yii::t('messages', 'Город')?>";

messages["lf_category"] = "<?=Yii::t('messages', 'Укажите категорию')?>";
messages["lf_thing"] = "<?=Yii::t('messages', 'Укажите объект')?>";
messages["address"] = "<?=Yii::t('messages', 'Заполните адрес')?>";
messages["title"] = "<?=Yii::t('messages', 'Укажите название')?>";