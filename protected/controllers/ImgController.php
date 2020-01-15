<?php

class ImgController extends AController
{
  const FUNC_IN    = 'in';
  const FUNC_NONE  = '';
  const FUNC_EXACT = 'exacly';

  public $resize = false;
  public $path   = false;
  public $pathNa = 'no-photo.png';
  public $layout = false;
  public $baseImageDir;
  public $baseCacheDir;
  public $func   = self::FUNC_NONE;

  public $defaultQuality = 80;

  public function __construct($id,$module=null){
    parent::__construct($id,$module);
    $this->baseImageDir = Yii::getPathOfAlias('webroot').'/images/';
    $this->baseCacheDir = Yii::getPathOfAlias('webroot').Yii::app()->easyImage->cachePath;
  }

  public function actionThumb(){

    $this->func   = Core::getGet('func', self::FUNC_NONE);
    $this->resize = Core::getGet('size', false);
    $this->path   = Core::getGet('path', $this->path);

    // print_r($this->resize);
    // print_r($_GET);
    // die();

    if (!file_exists($this->baseImageDir.$this->path) || !is_readable($this->baseImageDir.$this->path)) {
      $this->path = $this->pathNa;
    }
    $filename = $this->baseImageDir.$this->path;
    $outfile = $this->getCachedFilename();

    $timestamp = file_exists($outfile)?filemtime($filename):0;
    $tsstring = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';
    $etag = md5($timestamp);

    $offset = 3600 * 24;
    $expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";

    header ('Content-type: image/png');
    header("Last-Modified: $tsstring");
    header("ETag: {$etag}");
    header($expire);
    header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0, max-age=$offset");
    header("Pragma: public");

    if($this->checkCache($outfile, $filename)) {
      // есть в кєше - проверяем, есть ли в кеше браузера
      $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
      $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
      if ((($if_none_match && $if_none_match == $etag) || (!$if_none_match)) &&
          ($if_modified_since && $if_modified_since == $tsstring))
      {
        header('HTTP/1.1 304 Not Modified');
        Yii::app()->end();
      }
    } else {
      $image = new EasyImage($filename);
      if ($this->resize) {
        list($x, $y) = array_pad( explode('x', $this->resize), 2, false );
        $y = $y?$y:$x;

        if ($this->func == self::FUNC_IN) {
          $image->resize($x, $y, EasyImage::RESIZE_AUTO);
        }
        if ($this->func == self::FUNC_EXACT) {
          $image->scaleAndCrop($x, $y);
        }
      }
      $image->save($outfile);
      touch($outfile, filemtime($filename));
    }
    readfile($outfile);
  }

  private function checkCache($outfile, $filename){
    if (!file_exists($outfile) || !is_readable($outfile)) return false;
    $t1 = filemtime($filename);
    $t2 = filemtime($outfile);
    return ($t1 == $t2);
  }
  private function getCachedFilename(){
    $folder  = $this->resize?$this->resize:'original';
    $folder .= $this->func?"_{$this->func}":"";
    $path = $this->baseCacheDir."thumb/{$folder}/".$this->path;

    if (!is_dir(dirname($path))) {
      mkdir(dirname($path), 0777, true);
    }

    return $path;
  }
}