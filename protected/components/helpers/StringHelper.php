<?php

class StringHelper {

    public static function cut($string, $length = 200, $url = null) {
        if ($length && strlen($string) > $length) {
            $str = strip_tags($string);
            $str = substr($str, 0, $length);
            $pos = strrpos($str, ' ');
            if($url == null){
                return substr($str, 0, $pos) . '…';
            } else {
                return substr($str, 0, $pos) . '…<a href="'.$url.'">читать далее</a>';
            }
        }
        return $string;
    }

}
?>