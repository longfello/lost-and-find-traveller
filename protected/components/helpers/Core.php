<?php

class Core {
  public static function getIP() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
      $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
      $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
      $ip = getenv("REMOTE_ADDR");
    } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
      $ip = $_SERVER['REMOTE_ADDR'];
    } else {
      $ip = 'unknown';
    }
    return $ip;
  } // GetIP
  public static function getRequest($name = FALSE, $def = null, $type = type_string, $allow_scripts = FALSE) {
    return self::GetGet($name, self::GetPost($name, $def, $type, $allow_scripts), $type, $allow_scripts);
  }
  public static function getGet($name = FALSE, $def = null, $type = type_string, $allow_scripts = FALSE) {
    if ($name) {
      if (!is_null($def)) {
        $val = isset($_GET[$name]) ? self::castType($_GET[$name], $type, $allow_scripts) : $def;
        return $val;
      } else {
        $val = isset($_GET[$name]) ? $_GET[$name] : trigger_error("Ошибка передачи GET-параметра $name.", E_USER_ERROR);
        $val = self::castType($val, $type, $allow_scripts);
        return $val;
      }
    } else {
      $arr = new stdClass;
      foreach ($_GET as $key => $value) {
        $arr->$key = self::castType($value, $type, $allow_scripts);
      }
      return $arr;
    }
  }
  public static function getImplode($name, $def = null, $type = type_string) {
    $val = self::getGet($name, null, type_string);
    if(is_array($val)) {
      $val = implode('', $val);
    }
    return $val;
  }
  public static function castType($val, $type, $allow_scripts = FALSE) {
    if (is_array($val)) {
      foreach ($val as $key => $one) {
        $val[$key] = self::castType($one, $type);
      }
    } else if (is_object($val)) {
      if ($type != type_object) {
        foreach ($val as $key => $one) {
          $val->$key = self::castType($one, $type);
        }
      }
    } else {
      switch ($type) {
        case type_string  :
          $val = $allow_scripts ? $val : self::xss_clean($val);
          break;
        case type_float   :
          $val = floatval($val);
          break;
        case type_int     :
          $val = intval($val);
          break;
        case type_bool    :
          $val = (bool)$val;
          break;
        case type_object  :
          $val = (object)$val;
          break;
        default           :
          trigger_error('Unknow type of variable for type-cast: ' . $type, E_USER_ERROR);
      } // switch
    }
    return $val;
  }
  public static function xss_clean($data) {
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do {
      // Remove really unwanted tags
      $old_data = $data;
      $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);

    // we are done...
    return $data;
  }
  public static function getPost($name = FALSE, $def = null, $type = type_string, $allow_scripts = FALSE) {
    if ($name) {
      if (!is_null($def)) {
        $val = isset($_POST[$name]) ? self::castType($_POST[$name], $type, $allow_scripts) : $def;
        return $val;
      } else {
        $val = isset($_POST[$name]) ? $_POST[$name] : trigger_error("Ошибка передачи POST-параметра $name.", E_USER_ERROR);
        $val = self::castType($val, $type, $allow_scripts);
        return $val;
      }
    } else {
      $arr = new stdClass;
      foreach ($_POST as $key => $value) {
        $arr->$key = self::castType($value, $type, $allow_scripts);
      }
      return $arr;
    }
  }
  public static function getObject($class, $name, $def = null, $type = type_string, $allow_scripts = FALSE) {
    if (!is_null($def)) {
      if (is_object($class)) {
        $val = property_exists($class, $name) ? self::castType($class->$name, $type, $allow_scripts) : $def;
      } else {
        $val = $def;
      }
      return $val;
    } else {
      $val = property_exists($class, $name) ? self::castType($class->$name, $type, $allow_scripts) : trigger_error("Ошибка объектного параметра $name.", E_USER_ERROR);
      $val = self::castType($val, $type, $allow_scripts);
      return $val;
    }
  }
  public static function getSession($name = FALSE, $def = null, $type = type_string) {
    if ($name) {
      if (!is_null($def)) {
        $val = Yii::app()->session->contains($name) ? self::castType(Yii::app()->session->get($name), $type, TRUE) : $def;
        return $val;
      } else {
        $val = Yii::app()->session->contains($name) ? Yii::app()->session->get($name) : trigger_error("Ошибка передачи SESSION-параметра $name.", E_USER_ERROR);
        $val = self::castType($val, $type, TRUE);
        return $val;
      }
    } else {
      $arr = new stdClass;
      foreach ($_SESSION as $key => $value) {
        $arr->$key = self::castType($value, $type, TRUE);
      }
      return $arr;
    }
  }
  public static function setArrayDefaults($array, $defaults){
    return array_replace_recursive($defaults, $array);
  }
  public static function yiiTerminate(){
    foreach (Yii::app()->log->routes as $route) {
      if($route instanceof CWebLogRoute) {
        $route->enabled = false; // disable any weblogroutes
      }
    }
    Yii::app()->end();
  }
  public static function translit($cyr_str, $allow_dot = false) {
    $tr = array("Ґ" => "G", "Ё" => "YO", "Є" => "E", "Ї" => "YI", "І" => "I",
        "і" => "i", "ґ" => "g", "ё" => "yo", "№" => "#", "є" => "e",
        "ї" => "yi", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
        "Д" => "D", "Е" => "E", "Ж" => "ZH", "З" => "Z", "И" => "I",
        "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
        "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
        "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
        "Ш" => "SH", "Щ" => "SCH", "Ъ" => "'", "Ы" => "YI", "Ь" => "",
        "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
        "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "zh",
        "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
        "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
        "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
        "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "",
        "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
        " " => "-", "," => "", "\r" => "_", "\n" => "_", "*" => 'ALL',
        ";" => "_", "'" => "_", '"' => "_", "(" => "",  ")" => "",
        "+" => "","-" => "",
    );
    if (!$allow_dot) {
      $tr['.'] = '_';
    }
    return strtr($cyr_str, $tr);
  }
  public static function genCode($length, $type = 0) {
    $chars[0] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ123456789_%*";
    $chars[1] = "ABCDEFGHIJKLMNOPRQSTUVWXYZ123456789";
    $chars = $chars[$type];
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length)
    {
      $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
  }
  public static function toArray($data) {
    if ((! is_array($data)) and (! is_object($data))) return array(); //$data;
    $result = array();
    foreach ($data as $key => $value) {
      if (is_object($value)) $value = self::toArray($value);
      if (is_array($value))
        $result[$key] = self::toArray($value);
      else
        $result[$key] = $value;
    }
    return $result;
  }
  public static function getYouYubeCode($url){
    $code = $url;
    $code = preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+|(?<=youtube.com/embed/)[^&\n]+#", $code, $matches);
    $code = isset($matches[0])?$matches[0]:'';
    return $code;
  }
  public static function probability($percent = 50) {
    return (rand(1, 100) < $percent);
  }
  public static function print_r($arr, $return = false, $html = false) {
    $out = array();
    $oldtab = "    ";
    $newtab = $html?"&nbsp;&nbsp;&nbsp;&nbsp;":"  ";

    $lines = explode("\n", print_r($arr, true));

    foreach ($lines as $line) {

      //remove numeric indexes like "[0] =>" unless the value is an array
      if (substr($line, -5) != "Array") {	$line = preg_replace("/^(\s*)\[[0-9]+\] => /", "$1", $line, 1); }

      //garbage symbols
      foreach (array(
                   "Array" => "",
                   "Object" => "",
                   "["     => "",
                   "]"     => "",
                   " =>"   => ":",
               ) as $old => $new) {
        $out = str_replace($old, $new, $out);
      }

      //garbage lines
      if (in_array(trim($line), array("Array", "(", ")", ""))) continue;

      //indents
      $indent = "";
      $indents = floor((substr_count($line, $oldtab) - 1) / 2);
      if ($indents > 0) { for ($i = 0; $i < $indents; $i++) { $indent .= $newtab; } }

      $line = $indent . trim($line);
      $out[] = $html?"<nobr>$line</nobr>":$line;
    }

    $nl  = $html?"<br>":"\n";
    $out = implode($nl, $out) . $nl;
    if ($return == true) return $out;
    echo $out;
  }
  public static function encodeEmail($email, $limiter = 70){
    $short_email = mb_substr($email, 0, $limiter);
    $url = 'mailto:'.$email;
    $safe_email=$safe_url='';
    for($i=0; $i<strlen($short_email); $i++){
      $safe_email .= '&#'.ord($short_email{$i}).';';
    }
    for($i=0; $i<strlen($url); $i++){
      $safe_url .= '&#'.ord($url{$i}).';';
    }
    $string =  "<a href='$safe_url'>$safe_email</a>";

    $base = rand(0,32);
    $ss = array();
    for($i=0; $i<strlen($string); $i++){
      $ss[] = ord($string{$i})-$base;
    }
    $string = core_twig::getInstance()->render('common/email.twig', array(
      't'     => implode(',', $ss),
      'class' => base_convert(sha1($email), 16, 36).$base,
      'base'  => $base
    ));
    return $string;
  }
  public static function encodePhone($phone){
    $url = 'tel:'.$phone;
    $string =  "<a href='$url'>$phone</a>";
    $base = rand(0,32);
    $ss = array();
    for($i=0; $i<strlen($string); $i++){
      $ss[] = ord($string{$i})-$base;
    }
    $string = core_twig::getInstance()->render('common/phone.twig', array(
      't'     => implode(',', $ss),
      'class' => base_convert(sha1($phone), 16, 36).'p'.$base,
      'base'  => $base
    ));
    return $string;
  }

}

// Приведение типов
define('type_int', 'type_int', true);
define('type_float', 'type_float', true);
define('type_string', 'type_string', true);
define('type_bool', 'type_bool', true);
define('type_object', 'type_object', true);
