<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
header('Content-Type: text/html; charset=utf-8');
// change the following paths if necessary
$yii=dirname(__FILE__).'/YiiRoot/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($yii);
Yii::createWebApplication($config)->run();

//общие функции
function crop_str($string, $limit)
{
 
$result = $string;
if(strlen($string) > $limit  ){

$substring_limited = substr($string,0, $limit);  
$result = substr($substring_limited, 0, strrpos($substring_limited, ' ' ))." ...";   
}
 return $result;
}
function number($n, $titles) {
   return ($titles[($n=($n=$n%100)>19? ($n%10):$n)==1?0 : (($n>1&&$n<=4)?1:2)]);
}
//конец общих функций

?>